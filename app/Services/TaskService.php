<?php

namespace App\Services;


use App\Repositories\Contracts\ITaskRepository;
use App\Services\Response\ServicePaginationResponseDto;
use App\Services\Response\ServiceResponseDto;

class TaskService extends BaseService
{
    protected $taskRepository;

    public function __construct(ITaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    protected function isTaskExist($siteId,$date,$id = null){
        $response = new ServiceResponseDto();

        $response->setResult($this->taskRepository->isTaskExist($siteId,$date,$id));

        return $response;
    }

    public function create($input){
        $response = new ServiceResponseDto();
        $isTaskExist = $this->isTaskExist($input['siteId'],$input['dateTask'])->getResult();

        if($isTaskExist){
            $message = ['Task already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->taskRepository->create($input)){
                $message=['Failed add new task'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function read($id){
        return $this->readObject($this->taskRepository,$id);
    }

    public function showAll(){
        return $this->getAllObject($this->taskRepository);
    }

    public function update($input){
        $response = new ServiceResponseDto();
        $isTaskExist = $this->isTaskExist($input['siteId'],$input['dateTask'],$input['id'])->getResult();

        if($isTaskExist){
            $message = ['Task already exist'];
            $response->addErrorMessage($message);
        }else{
            if(!$this->taskRepository->update($input)){
                $message=['Failed update existing task'];
                $response->addErrorMessage($message);
            }
        }

        return $response;
    }

    public function delete($id){
        return $this->deleteObject($this->taskRepository,$id);
    }

    public function pagination($param,$status){
        $response = new ServicePaginationResponseDto();

        $pagingResult = $this->taskRepository->paginationByStatus($this->parsePaginationParam($param), $status);
        $response->setCurrentPage($param['pageIndex']);
        $response->setPageSize($param['pageSize']);
        $response->setTotalCount($pagingResult->getTotalCount());
        $response->setResult($pagingResult->getResult());

        return $response;
    }
}