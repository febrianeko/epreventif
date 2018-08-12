<?php

namespace App\Http\Controllers;

use App\Services\Response\ServiceResponseDto;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getJsonResponse(ServiceResponseDto $response) {
        try {
            if ($response->isSuccess()) {
                return response(['success'=>true,'result'=>$response->getResult()],200);
            } else {
                return response([
                    'errorMessage'=>$response->getErrorMessages()
                ],500);
            }
        } catch (\Exception $ex) {
            $message = ['Invalid Proccess'];

            $response->addErrorMessage($message);

            return response([
                'errorMessage'=>$ex->getMessage()
            ],500);
        }
    }

    protected function getPaginationParams() {
        $param = [
            'pageIndex' => intval(Input::get('current')), 'pageSize' => intval(Input::get('rowCount')), 'sort' => Input::get('sort'), 'searchPhrase' => Input::get('searchPhrase')
        ];

        return $param;
    }

    protected function parsePaginationResultToGridJson(ServiceResponseDto $paginationResponseDto) {
        $result = [
            'current' => $paginationResponseDto->getCurrentPage(),
            'rowCount' => $paginationResponseDto->getPageSize(),
            'rows' => $paginationResponseDto->getResult(),
            'total' => $paginationResponseDto->getTotalCount(),
        ];

        return response()->json($result);
    }

    protected function getResponseAutoComplete(ServiceResponseDto $response) {
        try {
            if ($response->isSuccess()) {
                return response()->json($response->getResult());
            } else {
                return response()->json(['msg' => $response->getErrorMessages()]);
            }
        } catch (\Exception $ex) {
            $message = ['invalid Proccess'];

            $response->addErrorMessage($message);

            return response()->json(['msg' => $response->getErrorMessages()]);
        }
    }
}
