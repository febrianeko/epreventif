<?php

namespace App\Http\Controllers;

use App\Services\RegionalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class RegionalController extends Controller
{
    protected $regionalService;

    public function __construct(RegionalService $regionalService)
    {
        $this->regionalService = $regionalService;
    }

    public function index(){
        return view('regional.index');
    }

    public function create(){
        $result= $this->regionalService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->regionalService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->regionalService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->regionalService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param = $this->getPaginationParams();
        $result = $this->regionalService->pagination($param);

        return $this->parsePaginationResultToGridJson($result);
    }
}
