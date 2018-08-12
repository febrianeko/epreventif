<?php 

namespace App\Services\Response;

class ServiceResponseDto {
    
    private $errorMessages =[];
    private $result;

    public function getErrorMessages() {
        return $this->errorMessages;
    }

    public function getResult() {
        return $this->result;
    }

    public function isSuccess() {
        return count($this->errorMessages) == 0;
    }
    
    public function addErrorMessage($message) {
        $this->errorMessages[] = $message;
    }

    public function setResult($result) {
        $this->result = $result;
    }

}
