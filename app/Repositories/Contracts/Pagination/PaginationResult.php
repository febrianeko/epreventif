<?php 

namespace App\Repositories\Contracts\Pagination;

class PaginationResult {
    
    private $_totalCount = 0;
    private $_result;
    private $_currentPageIndex = 0;
    private $_currentPageSize = 10;
    
    public function setResult($result) {
        $this->_result = $result;
    }
    
    public function getResult() {
        return $this->_result;
    }

    public function setTotalCount($totalCount) {
        $this->_totalCount = $totalCount;
    }
    
    public function getTotalCount() {
        return $this->_totalCount;
    }
    
    public function setCurrentPageIndex($currentPageIndex){
        $this->_currentPageIndex = $currentPageIndex;
    }
    
    public function getCurrentPageIndex(){
        return $this->_currentPageIndex;
    }
    
    public function setCurrentPageSize($currentPageSize){
        $this->_currentPageSize = $currentPageSize;
    }
    
    public function getCurrentPageSize(){
        return $this->_currentPageSize;
    }
    
}
