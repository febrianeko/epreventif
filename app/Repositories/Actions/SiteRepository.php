<?php


namespace App\Repositories\Actions;


use App\Models\EpSiteModel;
use App\Repositories\Contracts\ISiteRepository;
use App\Repositories\Contracts\Pagination\PaginationParam;
use App\Repositories\Contracts\Pagination\PaginationResult;

class SiteRepository implements ISiteRepository
{

    public function create($input)
    {
        $site = new EpSiteModel();
        $site->site_name = $input['siteName'];
        $site->site_id = $input['siteId'];
        $site->regional_id = $input['regionalId'];
        $site->area_id = $input['areaId'];
        $site->longitude = $input['longitude'];
        $site->latitude = $input['latitude'];
        $site->address = $input['address'];

        return $site->save();
    }

    public function update($input)
    {
        $site = EpSiteModel::find($input['id']);
        $site->site_name = $input['siteName'];
        $site->site_id = $input['siteId'];
        $site->regional_id = $input['regionalId'];
        $site->area_id = $input['areaId'];
        $site->longitude = $input['longitude'];
        $site->latitude = $input['latitude'];
        $site->address = $input['address'];

        return $site->save();
    }

    public function delete($id)
    {
        return EpSiteModel::find($id)->delete();
    }

    public function read($id)
    {
        $sites = EpSiteModel::all();
        $data = [];

        foreach ($sites as $site) {
            $data[] = [
                'id' => $site->id,
                'text' => $site->site_name
            ];
        }

        return $data;
    }

    public function getSiteByRegionalArea($regional, $area)
    {
        $sites = EpSiteModel::where('regional_id','=',$regional)->where('area_id','=',$area)->get();
        $data = [];

        foreach ($sites as $site) {
            $data[] = [
                'id' => $site->id,
                'text' => $site->site_name
            ];
        }

        return $data;
    }


    public function showAll()
    {
        $sites = EpSiteModel::all();
        $data = [];

        foreach ($sites as $site) {
            $data[] = [
                'id' => $site->id,
                'text' => $site->site_name
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
        $result->setTotalCount(EpSiteModel::join('ep_master_regional', 'ep_master_regional.id', '=', 'ep_master_site.regional_id')
            ->join('ep_master_area', 'ep_master_area.id', '=', 'ep_master_site.area_id')
            ->select('ep_master_site.*','ep_master_regional.regional_name','ep_master_area.area_name')
            ->count());


        //get data

        if ($param->getKeyword() == '') {


            if ($skipCount == 0) {

                $data = EpSiteModel::join('ep_master_regional', 'ep_master_regional.id', '=', 'ep_master_site.regional_id')
                    ->join('ep_master_area', 'ep_master_area.id', '=', 'ep_master_site.area_id')
                    ->select('ep_master_site.*','ep_master_regional.regional_name','ep_master_area.area_name')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpSiteModel::join('ep_master_regional', 'ep_master_regional.id', '=', 'ep_master_site.regional_id')
                    ->join('ep_master_area', 'ep_master_area.id', '=', 'ep_master_site.area_id')
                    ->select('ep_master_site.*','ep_master_regional.regional_name','ep_master_area.area_name')
                    ->skip($skipCount)->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            }

        } else {

            if ($skipCount == 0) {

                $data = EpSiteModel::join('ep_master_regional', 'ep_master_regional.id', '=', 'ep_master_site.regional_id')
                    ->join('ep_master_area', 'ep_master_area.id', '=', 'ep_master_site.area_id')
                    ->select('ep_master_site.*','ep_master_regional.regional_name','ep_master_area.area_name')
                    ->where('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_site.site_id', 'like', '%' . $param->getKeyword() . '%')
                    ->take($param->getPageSize())
                    ->orderBy($sortBy, $sortOrder)
                    ->get();

            } else {

                $data = EpSiteModel::join('ep_master_regional', 'ep_master_regional.id', '=', 'ep_master_site.regional_id')
                    ->join('ep_master_area', 'ep_master_area.id', '=', 'ep_master_site.area_id')
                    ->select('ep_master_site.*','ep_master_regional.regional_name','ep_master_area.area_name')
                    ->where('ep_master_regional.regional_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_area.area_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_site.site_name', 'like', '%' . $param->getKeyword() . '%')
                    ->orWhere('ep_master_site.site_id', 'like', '%' . $param->getKeyword() . '%')
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

    public function isSiteIdExist($siteId, $id = null)
    {
        if($id == null){
            $result = EpSiteModel::where('site_id','=',$siteId)->count();
        }else{
            $result = EpSiteModel::where('site_id','=',$siteId)->where('id','<>',$id)->count();
        }

        return ($result>0);
    }
}