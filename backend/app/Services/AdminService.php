<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    public function getAllUsers($perPage = 10)
    {
        return User::with('tasks')->paginate($perPage);
    }
    
    public function getUsersWithStats($perPage = 10)
    {
        $users = User::paginate($perPage);
        
        $users->getCollection()->transform(function ($user) {
            $user->stats = $user->getTaskStats();
            return $user;
        });
        
        return $users;
    }
}