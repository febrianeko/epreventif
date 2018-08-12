<?php

namespace App\Http\Controllers;

use App\Services\AreaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AreaController extends Controller
{
    protected $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }

    public function index(){
        return view('areas.index');
    }

    public function create(){
        $result = $this->areaService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function read($id){
        $result = $this->areaService->read($id);

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->areaService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->areaService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->areaService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param = $this->getPaginationParams();
        $result = $this->areaService->pagination($param);

        return $this->parsePaginationResultToGridJson($result);
    }
}
