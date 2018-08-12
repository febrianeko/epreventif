<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\Pagination\PaginationParam;

interface IBaseRepository {

    public function create($input);

    public function update($input);

    public function delete($id);

    public function read($id);

    public function showAll();

    public function paginationData(PaginationParam $param);
}
