<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $UserService;

    // Dependency Injection via Constructor
    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }
    public function index()
    {

        $users = $this->UserService->getAdmins();
        return $users;
    }
    public function store(StoreUserRequest $request)
    {
        $user = $this->UserService->addUser($request);
        return $user;
    }
    public function delete($id)
    {
        $deleted_user = $this->UserService->deleteUser($id);
        return $deleted_user;
    }

    //
}
