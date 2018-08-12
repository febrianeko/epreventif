<?php

namespace App\Repositories\Actions;


use App\Models\EpRegionalModel;
use App\Repositories\Contracts\IRegionalRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;

class RegionalRepository implements IRegionalRepository
{

    public function create($input)
    {
        $regional = new EpRegionalModel();
        $regional->regional_name = $input['regionalName'];
        $regional->created_at = date('Y-m-d H:i:s');
        
        return $regional->save();
    }

    public function update($input)
    {
        $regional = EpRegionalModel::find($input['id']);
        $regional->regional_name = $input['regionalName'];
        $regional->updated_at = date('Y-m-d H:i:s');

        return $regional->save();
    }

    public function delete($id)
    {
        return EpRegionalModel::find($id)->delete();
    }

    public function read($id)
    {
        return EpRegionalModel::find($id);
    }

    public function showAll()
    {
        $regionals = EpRegionalModel::all();
        $data = [];

        foreach ($regionals as $regional) {
            $data[]=[
                'id'=>$regional->id,
                'text'=>$regional->regional_name
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
        $result->setTotalCount(EpRegionalModel::count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpRegionalModel::take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data =EpRegionalModel::skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data =EpRegionalModel::where('regional_name', 'like', '%' . $param->getKeyword() . '%')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpRegionalModel::where('regional_name', 'like', '%' . $param->getKeyword() . '%')
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

    public function isRegionalExisting($regionalName, $id = null)
    {
        if($id == null){
            $result = EpRegionalModel::where('regional_name','=',$regionalName)->count();
        }else{
            $result = EpRegionalModel::where('regional_name','=',$regionalName)->where('id','<>',$id)->count();
        }

        return ($result >0);
    }
}