<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class RolesController extends Controller
{

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(){
        return view('roles.index');
    }

    public function create(){
        $result  = $this->roleService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->roleService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->roleService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->roleService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param= $this->getPaginationParams();
        $result = $this->roleService->pagination($param);

        return $this->parsePaginationResultToGridJson($result);
    }
}
