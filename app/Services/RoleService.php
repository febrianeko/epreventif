<?php

namespace App\Services;


use App\Repositories\Contracts\IRoleRepository;
use App\Services\Response\ServiceResponseDto;

class RoleService extends BaseService
{

    protected $roleRepository;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    protected function isRoleExist($roleName,$id=null){
        $response = new ServiceResponseDto();

        $response->setResult($this->roleRepository->checkExistingRoleName($roleName,$id));

        return $response;
    }

    public function create($input){
        $response = new ServiceResponseDto();

        $isRoleExist = $this->isRoleExist($input['roleName']);
        if($isRoleExist->getResult()){
            $message =['Role already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->roleRepository->create($input)){
                $message =['Failed add new role'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function read($id){
        return $this->readObject($this->roleRepository,$id);
    }

    public function showAll(){
        return $this->getAllObject($this->roleRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();

        $isRoleExist = $this->isRoleExist($input['roleName'],$input['id']);
        if($isRoleExist->getResult()){
            $message =['Role already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->roleRepository->update($input)){
                $message =['Failed update existing role'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->roleRepository,$id);
    }

    public function pagination($param){
        return $this->getPaginationObject($this->roleRepository,$param);
    }

}