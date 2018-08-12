<?php

namespace App\Http\Controllers;

use App\Services\SiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SitesController extends Controller
{
    protected $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function index(){
        return view('sites.index');
    }

    public function formSites(){
        return view('sites.create');
    }

    public function create(){
        $result = $this->siteService->create(Input::all());

        return $this->getJsonResponse($result);
    }

    public function read($regional,$area){
        $result = $this->siteService->readByRegionalArea($regional,$area);

        return $this->getJsonResponse($result);
    }

    public function showAll(){
        $result = $this->siteService->showAll();

        return $this->getJsonResponse($result);
    }

    public function update(){
        $result = $this->siteService->update(Input::all());

        return $this->getJsonResponse($result);
    }

    public function delete($id){
        $result = $this->siteService->delete($id);

        return $this->getJsonResponse($result);
    }

    public function pagination(){
        $param = $this->getPaginationParams();
        $result = $this->siteService->pagination($param);

        return $this->parsePaginationResultToGridJson($result);
    }
}
