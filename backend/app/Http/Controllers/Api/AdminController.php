<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('tasks')->get();
        return response()->json($users);
    }

    public function userStatistics($userId)
    {
        $user = User::with('tasks')->findOrFail($userId);
        $totalTasks = $user->tasks->count();
        $completedTasks = $user->tasks->where('status', 'completed')->count();
        $pendingTasks = $user->tasks->where('status', 'pending')->count();

        return response()->json([
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
        ]);
    }
}