<?php 

namespace App\Repositories\Contracts\Pagination;

class PaginationParam {
    
    const ASCENDING_ORDER = "asc";
    const DECENDING_ORDER = "desc"; 
    
    private $_keyword = "";
    private $_sortBy = "";
    private $_sortOrder = self::ASCENDING_ORDER;
    private $_pageIndex = 0;
    private $_pageSize = 10;
    
    public function setKeyword($keyword) {
        $this->_keyword = $keyword;
    }
    
    public function getKeyword() {
        return $this->_keyword;
    }

    public function setSortBy($sortBy) {
        $this->_sortBy = $sortBy;
    }

    public function getSortBy() {
        return $this->_sortBy;
    }
    
    public function setSortOrder($sortOrder) {
        $this->_sortOrder = $sortOrder;
    }

    public function getSortOrder() {
        return $this->_sortOrder;
    }
    
    public function setPageIndex($pageIndex) {
        if ($pageIndex != 0)
            $pageIndex--;
        $this->_pageIndex = $pageIndex;
    }

    public function getPageIndex() {
        return $this->_pageIndex;
    }
    
    public function setPageSize($pageSize) {
        $this->_pageSize = $pageSize;
    }

    public function getPageSize() {
        return $this->_pageSize;
    }
    
}
