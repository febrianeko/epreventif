<?php

namespace App\Services;


use App\Repositories\Contracts\IUserRepository;
use App\Services\Response\ServicePaginationResponseDto;
use App\Services\Response\ServiceResponseDto;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($input){
        $response = new ServiceResponseDto();

        $validateEmail = $this->isEmailExist($input['email']);
        if($validateEmail->getResult()){
            $message = ['Email already exist'];
            $response->addErrorMessage($message);
        }else{


            if(!$this->userRepository->create($input)){
                $message = ['Failed to create new user'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function read($id){
        return $this->readObject($this->userRepository,$id);
    }

    public function readByRegionalArea($regional,$area){
        $response = new ServiceResponseDto();

        $response->setResult($this->userRepository->readByRegionalArea($regional,$area));

        return $response;
    }

    public function showAll(){
        return $this->getAllObject($this->userRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();

        $isEmailExist = $this->isEmailExist($input['email'],$input['id']);
        if($isEmailExist->getResult()){
            $message = ['Email already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->userRepository->update($input)){
                $message = ['Failed to update existing user'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->userRepository,$id);
    }

    public function pagination($param){
        return $this->getPaginationObject($this->userRepository,$param);
    }

    public function paginationByUserLevel($param,$userLevel){
        $response = new ServicePaginationResponseDto();

        $pagingResult = $this->userRepository->paginationByUserLevel($this->parsePaginationParam($param), $userLevel);
        $response->setCurrentPage($param['pageIndex']);
        $response->setPageSize($param['pageSize']);
        $response->setTotalCount($pagingResult->getTotalCount());
        $response->setResult($pagingResult->getResult());

        return $response;
    }

    protected function checkHashedPassword($email,$password){
        $response= new ServiceResponseDto();

        $response->setResult($this->userRepository->checkUserPassword($email,$password));

        return $response;
    }

    public function changePassword($email,$password){
        $response = new ServiceResponseDto();

        $isPasswordValid = $this->checkHashedPassword($email,$password);
        if($isPasswordValid->getResult()){
            if(!$this->userRepository->changePassword($email,$password)){
                $message =['Failed change password'];
                $response->addErrorMessage($message);
            }
        }else{
            $message = ['Old password not valid'];
            $response->addErrorMessage($message);
        }

        return $response;
    }

    protected function isEmailExist($email,$id = null){
        $response = new ServiceResponseDto();

        $response->setResult($this->userRepository->checkExistEmail($email,$id));

        return $response;
    }

    public function setLogLastVisited($email){
        $response = new ServiceResponseDto();

        $response->setResult($this->userRepository->lastVisitedLog($email));

        return $response;
    }

    public function checkLogin($email,$password,$remember){
        $response = new ServiceResponseDto();

        $isExist = $this->isEmailExist($email);
        if($isExist->getResult()){
            if (!$token = JWTAuth::attempt(['email'=>$email,'password'=>$password])) {
                $message =['Invalid credentials'];
                $response->addErrorMessage($message);
            }else{
                Auth::attempt(['email'=>$email,'password'=>$password],true);
                $response->setResult($token);
            }
        }else{
            $message = ['Cannot login, cannot find email you entered'];
            $response->addErrorMessage($message);
        }
        return $response;
    }
}