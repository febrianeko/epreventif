<?php

namespace App\Repositories\Actions;


use App\Models\EpRolesModel;
use App\Repositories\Contracts\IRoleRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;

class RoleRepository implements IRoleRepository
{

    public function create($input)
    {
        $role = new EpRolesModel();
        $role->role_name = $input['roleName'];
        $role->description = $input['description'];
        $role->created_at = date('Y-m-d h:m:s');

        return $role->save();
    }

    public function update($input)
    {
        $role = EpRolesModel::find($input['id']);
        $role->role_name = $input['roleName'];
        $role->description = $input['description'];
        $role->updated_at = date('Y-m-d h:m:s');

        return $role->save();
    }

    public function delete($id)
    {
        return EpRolesModel::find($id)->delete();
    }

    public function read($id)
    {
        $roles = EpRolesModel::find($id);

        return [
            'roleId'=>$roles->id,
            'roleName'=>$roles->role_name,
            'description'=>$roles->description,
        ];
    }

    public function showAll()
    {
        $roles =  EpRolesModel::all();
        $data = [];

        foreach ($roles as $role){
            $data[]=[
                'id'=>$role->id,
                'label'=>$role->role_name
            ];
        }

        return $data;
    }

    public function paginationData(PaginationParam $param)
    {
        $result = new PaginationResult();


        $sortBy = ($param->getSortBy() == '' ? 'id' : $param->getSortBy());

        $sortOrder = ($param->getSortOrder() == '' ? 'asc' : $param->getSortOrder());


        //setup skip data for paging

        if ($param->getPageSize() == -1) {
            $skipCount = 0;
        } else {
            $skipCount = ($param->getPageIndex() * $param->getPageSize());
        }

        //get total count data
        $result->setTotalCount(EpRolesModel::count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpRolesModel::take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data =EpRolesModel::skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data =EpRolesModel::where('role_name', 'like', '%' . $param->getKeyword() . '%')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpRolesModel::where('role_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orderBy($sortBy, $sortOrder)
                    ->skip($skipCount)->take($param->getPageSize())
                    ->get();

            }

        }


        $result->setCurrentPageIndex($param->getPageIndex());
        $result->setCurrentPageSize($param->getPageSize());
        $result->setResult($data);


        return $result;
    }

    public function checkExistingRoleName($name, $id = null)
    {
        if($id == null){
            $result = EpRolesModel::where('role_name','=',$name)->count();
        }else{
            $result = EpRolesModel::where('role_name','=',$name)->where('id','<>',$id)->count();
        }

        return ($result>0);
    }
}