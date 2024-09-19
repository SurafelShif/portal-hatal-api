<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $AdminService;

    // Dependency Injection via Constructor
    public function __construct(AdminService $AdminService)
    {
        $this->AdminService = $AdminService;
    }
    public function index()
    {

        $admins = $this->AdminService->getAdmins();
        return $admins;
    }
    public function store(StoreAdminRequest $request)
    {
        $admin = $this->AdminService->addAdmin($request);
        return $admin;
    }
    //
}
