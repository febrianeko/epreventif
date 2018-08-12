<?php

namespace App\Services;


use App\Repositories\Contracts\IRegionalRepository;
use App\Services\Response\ServiceResponseDto;

class RegionalService extends BaseService
{
    protected $regionalRepository;

    public function __construct(IRegionalRepository $regionalRepository)
    {
        $this->regionalRepository = $regionalRepository;
    }

    protected function isRegionalExist($regionalName,$id = null){
        $response = new ServiceResponseDto();

        $response->setResult($this->regionalRepository->isRegionalExisting($regionalName,$id));

        return $response;
    }

    public function create($input){
        $response = new ServiceResponseDto();
        $isRegionalNameExist = $this->isRegionalExist($input['regionalName'])->getResult();

        if($isRegionalNameExist){
            $message = ['Regional Name Already Exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->regionalRepository->create($input)){
                $message = ['Failed create new regional'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function read($id){
        return $this->readObject($this->regionalRepository,$id);
    }

    public function showAll(){
        return $this->getAllObject($this->regionalRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();
        $isRegionalNameExist = $this->isRegionalExist($input['regionalName'],$input['id'])->getResult();

        if($isRegionalNameExist){
            $message = ['Regional Name Already Exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->regionalRepository->update($input)){
                $message = ['Failed update existing regional'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->regionalRepository,$id);
    }

    public function pagination($param){
        return $this->getPaginationObject($this->regionalRepository,$param);
    }
}