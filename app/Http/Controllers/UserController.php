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
    public function store($uuid)
    {
        $user = $this->UserService->addAdmin($uuid);
        return $user;
    }
    public function delete($uuid)
    {
        $deleted_user = $this->UserService->deleteUser($uuid);
        return $deleted_user;
    }
    public function user()
    {
        $logged_user = $this->UserService->getLoggedUser();
        return $logged_user;
    }
    public function getUsers()
    {

        $users = $this->UserService->getUsers();
        return $users;
    }

    //
}
