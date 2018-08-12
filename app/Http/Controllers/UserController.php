<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService,RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index($roleId){
        $role = $this->roleService->read($roleId)->getResult();

        return view('users.index')->with('role',$role);
    }

    public function create(){
        $result = $this->userService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function read($id){
        $result = $this->userService->read($id);

        return $this->getJsonResponse($result);
    }

    public function readByRegionalArea($regional,$area){
        $result = $this->userService->readByRegionalArea($regional,$area);

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->userService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->userService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->userService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param = $this->getPaginationParams();
        $result = $this->userService->paginationByUserLevel($param,Input::get('roleId'));
        return $this->parsePaginationResultToGridJson($result);
    }
}
