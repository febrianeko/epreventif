<?php

namespace App\Repositories\Contracts;


interface IRoleRepository extends IBaseRepository
{
    public function checkExistingRoleName($name,$id =null);
}