<?php

namespace App\Repositories\Actions;


use App\Models\EpAreaModel;
use App\Repositories\Contracts\IAreaRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;

class AreaRepository implements IAreaRepository
{

    public function isAreaExist($areaName, $regionalId, $id = null)
    {
        if($id == null){
            $result = EpAreaModel::where('area_name','=',$areaName)->where('regional_id','=',$regionalId)->count();
        }else{
            $result = EpAreaModel::where(function ($q)use($areaName,$regionalId){
                $q->where('area_name','=',$areaName)->where('regional_id','=',$regionalId);
            })->where('id','<>',$id)->count();
        }

        return ($result>0);
    }

    public function create($input)
    {
        $area = new EpAreaModel();
        $area->area_name = $input['areaName'];
        $area->regional_id =$input['regionalId'];
        $area->created_at = date('Y-m-d H:i:s');

        return $area->save();
    }

    public function update($input)
    {
        $area = EpAreaModel::find($input['id']);
        $area->area_name = $input['areaName'];
        $area->regional_id =$input['regionalId'];
        $area->updated_at = date('Y-m-d H:i:s');

        return $area->save();
    }

    public function delete($id)
    {
        return EpAreaModel::find($id)->delete();
    }

    public function read($id)
    {
        $areas = EpAreaModel::where('regional_id','=',$id)->get();
        $data = [];

        foreach ($areas as $area){
            $data[]= [
                'id'=>$area->id,
                'text'=>$area->area_name
            ];
        }

        return $data;
    }

    public function showAll()
    {
        
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
        $result->setTotalCount(EpAreaModel::join('ep_master_regional','ep_master_regional.id','=','ep_master_area.regional_id')
            ->select('ep_master_area.*','ep_master_regional.regional_name')
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpAreaModel::join('ep_master_regional','ep_master_regional.id','=','ep_master_area.regional_id')
                    ->select('ep_master_area.*','ep_master_regional.regional_name')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data =EpAreaModel::join('ep_master_regional','ep_master_regional.id','=','ep_master_area.regional_id')
                    ->select('ep_master_area.*','ep_master_regional.regional_name')
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data =EpAreaModel::join('ep_master_regional','ep_master_regional.id','=','ep_master_area.regional_id')
                    ->select('ep_master_area.*','ep_master_regional.regional_name')
                    ->where('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpAreaModel::join('ep_master_regional','ep_master_regional.id','=','ep_master_area.regional_id')
                    ->select('ep_master_area.*','ep_master_regional.regional_name')
                    ->where('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%')
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