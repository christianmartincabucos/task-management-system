<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    public function getAllForUser($userId, $filters = [])
    {
        $cacheKey = "tasks.user.{$userId}." . md5(json_encode($filters));
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($userId, $filters) {
            $query = $this->model->where('user_id', $userId);
            
            if (isset($filters['status'])) {
                $query->status($filters['status']);
            }
            
            if (isset($filters['priority'])) {
                $query->priority($filters['priority']);
            }
            
            if (isset($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', "%{$filters['search']}%")
                      ->orWhere('description', 'like', "%{$filters['search']}%");
                });
            }
            
            return $query->orderByPosition()->get();
        });
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        // Get the highest order for the user
        $maxOrder = $this->model->where('user_id', $data['user_id'])->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;
        
        $task = $this->model->create($data);
        $this->clearUserCache($data['user_id']);
        
        return $task;
    }

    public function update($id, array $data)
    {
        $task = $this->getById($id);
        $task->update($data);
        
        $this->clearUserCache($task->user_id);
        
        return $task;
    }

    public function delete($id)
    {
        $task = $this->getById($id);
        $userId = $task->user_id;
        
        $task->delete();
        $this->clearUserCache($userId);
        
        return true;
    }

    public function updateOrder(array $orderedTasks)
    {
        DB::beginTransaction();
        
        try {
            foreach ($orderedTasks as $task) {
                $this->model->where('id', $task['id'])->update(['order' => $task['order']]);
            }
            
            DB::commit();
            
            // Clear cache for the user
            if (!empty($orderedTasks)) {
                $firstTask = $this->getById($orderedTasks[0]['id']);
                $this->clearUserCache($firstTask->user_id);
            }
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function clearUserCache($userId)
    {
        // Clear all cached queries for this user
        Cache::forget("tasks.user.{$userId}");
        // Also clear any filtered queries
        Cache::flush();
    }
}