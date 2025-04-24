<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks(Auth::id(), $request->query());
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $task = $this->taskService->createTask($request->validated(), Auth::id());

            DB::commit();
            return new TaskResource($task);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], 422);
        }
        
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        try {
            DB::beginTransaction();

            $task = $this->taskService->updateTask($task, $request->validated());
            DB::commit();

            return new TaskResource($task);

        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Task not found',
                'error' => $e->getMessage(),
            ], 404);
        }
        
    }

    public function destroy($id)
    {
        $id = (int) $id;
        
        try {
            $this->taskService->deleteTask($id);
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function reorder(Request $request)
    {
        $this->taskService->reorderTasks($request->input('order'), Auth::id());
        return response()->noContent();
    }
}