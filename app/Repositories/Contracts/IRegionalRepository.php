<?php

namespace App\Repositories\Contracts;


interface IRegionalRepository extends IBaseRepository
{
    public function isRegionalExisting($regionalName,$id=null);
}