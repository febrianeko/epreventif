<?php
/**
 * Created by PhpStorm.
 * User: syaikhul
 * Date: 05/08/2018
 * Time: 14.16
 */

namespace App\Repositories\Actions;


use App\Models\EpTaskModel;
use App\Repositories\Contracts\ITaskRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;

class TaskRepository implements ITaskRepository
{

    public function create($input)
    {
        $task = new EpTaskModel();
        $task->site_id = $input['siteId'];
        $task->regional_id = $input['regionalId'];
        $task->area_id = $input['areaId'];
        $task->engineer_id = $input['engineerId'];
        $task->date_task = $input['dateTask'];

        return $task->save();
    }

    public function update($input)
    {
        $task = EpTaskModel::find($input['id']);
        $task->site_id = $input['siteId'];
        $task->engineer_id = $input['engineerId'];
        $task->date_task = $input['dateTask'];

        return $task->save();
    }

    public function delete($id)
    {
        return EpTaskModel::find($id)->delete();
    }

    public function read($id)
    {
        return EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
            ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
            ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
            ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
            ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                'users.name')
            ->first();
    }

    public function showAll()
    {
        return EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
            ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
            ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
            ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
            ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                'users.name')
            ->get();
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
        $result->setTotalCount(EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
            ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
            ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
            ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
            ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                'users.name')
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data =EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data =EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where(function ($q)use($param){
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%');
                    })
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where(function ($q)use($param){
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%');
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

    public function isTaskExist($siteId,$date,$id = null)
    {
        if($id == null){
            $result = EpTaskModel::where('site_id','=',$siteId)->where('date_task','=',$date)->count();
        }else{
            $result = EpTaskModel::where(function ($q)use($siteId,$date){
                $q->where('site_id','=',$siteId)->where('date_task','=',$date);
            })->where('id','<>',$id)->count();
        }

        return ($result>0);
    }

    public function paginationByStatus(PaginationParam $param, $status)
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
        $result->setTotalCount(EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
            ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
            ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
            ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
            ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                'users.name')
            ->where('is_finish','=',$status)
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where('is_finish','=',$status)
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data =EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where('is_finish','=',$status)
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data =EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where('is_finish','=',$status)
                    ->where(function ($q)use($param){
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%');
                    })
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpTaskModel::join('users','users.id','=','ep_task.engineer_id')
                    ->join('ep_master_site','ep_master_site.id','=','ep_task.site_id')
                    ->join('ep_master_regional','ep_master_regional.id','=','ep_master_site.regional_id')
                    ->join('ep_master_area','ep_master_area.id','=','ep_master_site.area_id')
                    ->select('ep_task.*','ep_master_site.site_name','ep_master_regional.regional_name','ep_master_area.area_name',
                        'users.name')
                    ->where('is_finish','=',$status)
                    ->where(function ($q)use($param){
                        $q->where('users.name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                            ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%');
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


}