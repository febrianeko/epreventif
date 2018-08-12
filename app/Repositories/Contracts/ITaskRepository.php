<?php

namespace App\Repositories\Contracts;


use App\Repositories\Contracts\Pagination\PaginationParam;

interface ITaskRepository extends IBaseRepository
{
    public function isTaskExist($siteId,$date,$id = null);

    public function paginationByStatus(PaginationParam $param,$status);
}