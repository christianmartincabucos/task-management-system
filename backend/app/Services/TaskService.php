<?php

namespace App\Services;

use App\Events\TaskUpdated;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getUserTasks($filters = [])
    {
        return $this->taskRepository->getAllForUser(Auth::id(), $filters);
    }

    public function getTask($id)
    {
        $task = $this->taskRepository->getById($id);
        
        if ($task->user_id !== Auth::id() && !Auth::user()->is_admin) {
            throw new AuthorizationException('You are not authorized to access this task');
        }
        
        return $task;
    }

    public function createTask(array $data)
    {
        $data['user_id'] = Auth::id();
        return $this->taskRepository->create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = $this->getTask($id);
        $task = $this->taskRepository->update($id, $data);
                
        return $task;
    }

    public function deleteTask($id)
    {
        $this->getTask($id);
        
        return $this->taskRepository->delete($id);
    }

    public function updateTaskOrder(array $orderedTasks)
    {
        // Validate that all tasks belong to the current user or user is admin
        if (!Auth::user()->isAdmin()) {
            $taskIds = array_column($orderedTasks, 'id');
            $userTaskCount = $this->taskRepository->model->whereIn('id', $taskIds)
                ->where('user_id', Auth::id())
                ->count();
                
            if ($userTaskCount !== count($taskIds)) {
                throw new \Exception('Unauthorized to reorder these tasks');
            }
        }
        
        return $this->taskRepository->updateOrder($orderedTasks);
    }
}