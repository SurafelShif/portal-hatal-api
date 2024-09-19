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
    public function store($id)
    {
        $user = $this->UserService->addAdmin($id);
        return $user;
    }
    public function delete($id)
    {
        $deleted_user = $this->UserService->deleteUser($id);
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
