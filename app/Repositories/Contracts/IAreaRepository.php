<?php

namespace App\Repositories\Contracts;


interface IAreaRepository extends IBaseRepository
{
    public function isAreaExist($areaName,$regionalId,$id = null);
}