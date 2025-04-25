<?php

namespace App\Repositories;

use App\Events\TaskUpdated;
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
        $this->clearUserCache($data['user_id']);
        $task = $this->model->create($data);
        
        event(new TaskUpdated($task, 'created'));
        
        return $task;
    }

    public function update($id, array $data)
    {
        $task = $this->getById($id);
        $task->update($data);
        
        $this->clearUserCache($task->user_id);
        event(new TaskUpdated($task, 'updated'));

        return $task;
    }

    public function delete($id)
    {
        $task = $this->getById($id);
        $userId = $task->user_id;
        
        $task->delete();
        $this->clearUserCache($userId);
        event(new TaskUpdated($task, 'deleted'));
        
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
        Cache::forget("tasks.user.{$userId}");
        Cache::flush();
    }
}