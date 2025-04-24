<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskOrderRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);
        $tasks = $this->taskService->getUserTasks($filters);
        
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->getTask($id);
        
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        
        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function updateOrder(TaskOrderRequest $request)
    {
        $this->taskService->updateTaskOrder($request->tasks);
        
        return response()->json(['message' => 'Task order updated successfully']);
    }
}