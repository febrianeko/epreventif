<?php

namespace App\Repositories\Actions;


use App\User;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    protected function generateCode($length = 8)
    {
        $chars = "ABCDEFGHJKLMNPQRSTWXYZabcdefgijkmnopqrstwxyz0123456789";
        $final_rand = '';

        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $final_rand;
    }

    public function create($input)
    {
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($this->generateCode(6));
        $user->role_id = $input['roleId'];
        $user->position = $input['position'];
        $user->regional_id = $input['regionalId'];
        $user->created_at = date('Y-m-d H:i:s');

        return $user->save();
    }

    public function update($input)
    {
        $user = User::find($input['id']);
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->role_id = $input['roleId'];
        $user->position = $input['position'];
        $user->regional_id = $input['regionalId'];
        $user->updated_at = date('Y-m-d H:i:s');

        return $user->save();
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }

    public function read($id)
    {
        return User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
            ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
            ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
            ->where('users.id', '=', $id)
            ->first();
    }

    public function readByRegionalArea($regional, $area)
    {
        $users = User::where('regional_id','=',$regional)
            ->where('area_id','=',$area)
            ->get();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'text' => $user->name
            ];
        }

        return $data;
    }


    public function showAll()
    {
        $users = User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
            ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
            ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
            ->get();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'label' => $user->name
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

        $result->setTotalCount(User::oin('ep_roles', 'ep_roles.id', '=', 'users.role_id')
            ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
            ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = User::join('user_role', 'user_role.id', '=', 'users.role_id')
                    ->join('master_institution', 'master_institution.id', '=', 'users.institution_id')
                    ->select('users.*', 'user_role.role_name', 'master_institution.institution_name')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = User::join('user_role', 'user_role.id', '=', 'users.role_id')
                    ->join('master_institution', 'master_institution.id', '=', 'users.institution_id')
                    ->select('users.*', 'user_role.role_name', 'master_institution.institution_name')
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data = User::join('user_role', 'user_role.id', '=', 'users.role_id')
                    ->join('master_institution', 'master_institution.id', '=', 'users.institution_id')
                    ->select('users.*', 'user_role.role_name', 'master_institution.institution_name')
                    ->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('user_role.role_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('users.address', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('users.sex', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('master_institution.institution_name', 'like', '%' . $param->getKeyword() . '%')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = User::join('user_role', 'user_role.id', '=', 'users.role_id')
                    ->join('master_institution', 'master_institution.id', '=', 'users.institution_id')
                    ->select('users.*', 'user_role.role_name', 'master_institution.institution_name')
                    ->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('user_role.role_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('users.address', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('users.sex', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('master_institution.institution_name', 'like', '%' . $param->getKeyword() . '%')
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

    public function paginationByUserLevel(PaginationParam $param, $userLevel)
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

        $result->setTotalCount(User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
            ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
            ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
            ->where('users.role_id', '=', $userLevel)
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
                    ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
                    ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
                    ->where('users.role_id', '=', $userLevel)
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
                    ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
                    ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
                    ->where('users.role_id', '=', $userLevel)
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data = User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
                    ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
                    ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
                    ->where('users.role_id', '=', $userLevel)
                    ->where(function ($q) use ($param) {
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('users.position', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%');
                    })
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = User::join('ep_roles', 'ep_roles.id', '=', 'users.role_id')
                    ->join('ep_master_regional', 'ep_master_regional.id', '=', 'users.regional_id')
                    ->select('users.*', 'ep_roles.role_name', 'ep_master_regional.regional_name')
                    ->where('users.role_id', '=', $userLevel)
                    ->where(function ($q) use ($param) {
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('users.position', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%');
                    })
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

    public function checkExistEmail($email, $id = null)
    {
        if ($id == null) {
            $result = User::where('email', '=', $email)->count();
        } else {
            $result = User::where('email', '=', $email)->where('id', '<>', $id)->count();
        }

        return ($result > 0);
    }

    public function checkUserPassword($email, $password)
    {
        $hashedPassword = User::where('email', '=', $email)->value('password');

        return (Hash::check($password, $hashedPassword));
    }

    public function changePassword($email, $password)
    {
        return User::where('email', '=', $email)->update(['password' => bcrypt($password)]);
    }

}