<?php

namespace App\Repositories\Contracts;


use App\Repositories\Contracts\Pagination\PaginationParam;

interface IUserRepository extends IBaseRepository
{
    public function paginationByUserLevel(PaginationParam $param,$userLevel);

    public function checkExistEmail($email,$id = null);

    public function checkUserPassword($email,$password);

    public function changePassword($email,$password);

    public function readByRegionalArea($regional,$area);
}