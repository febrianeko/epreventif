<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(){
        return view('task.index');
    }

    public function create(){
        $result = $this->taskService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function read($id){
        $result = $this->taskService->read($id);

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->taskService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->taskService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->taskService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param = $this->getPaginationParams();
        $result = $this->taskService->pagination($param,Input::get('status'));

        return $this->parsePaginationResultToGridJson($result);
    }
}
