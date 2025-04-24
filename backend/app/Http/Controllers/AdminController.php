<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->middleware('admin');
        $this->adminService = $adminService;
    }

    public function users(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users = $this->adminService->getUsersWithStats($perPage);
        
        return UserResource::collection($users);
    }
}