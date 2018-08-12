<?php

namespace App\Repositories\Contracts;


interface ISiteRepository extends IBaseRepository
{
    public function isSiteIdExist($siteId,$id = null);

    public function getSiteByRegionalArea($regional,$area);
}