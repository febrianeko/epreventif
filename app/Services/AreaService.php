<?php

namespace App\Services;


use App\Repositories\Contracts\IAreaRepository;
use App\Services\Response\ServiceResponseDto;

class AreaService extends BaseService
{
    protected $areaRepository;

    public function __construct(IAreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    protected function isAreaNameExist($areaName,$regionalId,$id = null){
        $response = new ServiceResponseDto();

        $response->setResult($this->areaRepository->isAreaExist($areaName,$regionalId,$id));

        return $response;
    }

    public function create($input){
        $response = new ServiceResponseDto();
        $isAreaNameExist = $this->isAreaNameExist($input['areaName'],$input['regionalId'])->getResult();

        if($isAreaNameExist){
            $message= ['Area already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->areaRepository->create($input)){
                $message = ['Failed to add new area'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function read($id){
        return $this->readObject($this->areaRepository,$id);
    }

    public function showAll(){
        return $this->getAllObject($this->areaRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();
        $isAreaNameExist = $this->isAreaNameExist($input['areaName'],$input['regionalId'],$input['id'])->getResult();

        if($isAreaNameExist){
            $message= ['Area already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->areaRepository->update($input)){
                $message = ['Failed to edit existing area'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->areaRepository,$id);
    }

    public function pagination($param){
        return $this->getPaginationObject($this->areaRepository,$param);
    }
}