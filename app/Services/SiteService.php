<?php

namespace App\Services;


use App\Repositories\Contracts\ISiteRepository;
use App\Services\Response\ServiceResponseDto;

class SiteService extends BaseService
{
    protected $siteRepository;

    public function __construct(ISiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    protected function isSiteIdExist($siteId,$id =null){
        $response = new ServiceResponseDto();

        $response->setResult($this->siteRepository->isSiteIdExist($siteId,$id));

        return $response;
    }

    public function create($input){
        $response = new ServiceResponseDto();
        $isSiteIdExist = $this->isSiteIdExist($input['siteId'])->getResult();

        if($isSiteIdExist){
            $message =['Site id already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->siteRepository->create($input)){
                $message =['Failed add new site'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function readByRegionalArea($regional,$area){
        $response = new ServiceResponseDto();

        $response->setResult($this->siteRepository->getSiteByRegionalArea($regional,$area));

        return $response;
    }

    public function showAll(){
        return $this->getAllObject($this->siteRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();
        $isSiteIdExist = $this->isSiteIdExist($input['siteId'],$input['id'])->getResult();

        if($isSiteIdExist){
            $message =['Site id already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->siteRepository->update($input)){
                $message =['Failed update existing site'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->siteRepository,$id);
    }

    public function pagination($param){
        return $this->getPaginationObject($this->siteRepository,$param);
    }
}