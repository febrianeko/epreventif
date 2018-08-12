<?php 

namespace App\Services\Response;

use App\Services\Response\ServiceResponseDto;

class ServicePaginationResponseDto extends ServiceResponseDto {
    
    private $_currentPage = 0;
    private $_pageSize = 0;
    private $_totalCount = 0;
    
    public function setCurrentPage($currentPage) {
        $this->_currentPage = $currentPage;
    }
    
    public function getCurrentPage() {
        return $this->_currentPage;
    }
    
    public function setPageSize($pageSize) {
        $this->_pageSize = $pageSize;
    }
    
    public function getPageSize() {
        return $this->_pageSize;
    }
    
    public function setTotalCount($totalCount) {
        $this->_totalCount = $totalCount;
    }
    
    public function getTotalCount() {
        return $this->_totalCount;
    }

}
