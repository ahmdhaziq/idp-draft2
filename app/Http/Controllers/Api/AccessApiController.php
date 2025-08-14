<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\RequestsController;
use App\Models\AccessRequests;

class AccessApiController extends Controller
{
    
    public function listAccessRequests(){
        $request = AccessRequests::select(
        'requestName',
        'userId',
        'assetId',
        'access_action',
        'access_type',
        'context',
        'rejection_remark',
        'status',
        'duration',
        'access_level',
        'request_metadata'
        )
        ->get();

        return response()->json($request);


    }

    public function accessRequestbyAsset($assetid){
        $request = RequestsController::getRequestsByAssets($assetid);
        return response()->json($request);
    }

    public function accessRequestDetail($id){
        $detail = AccessRequests::where('id',$id)->select(
        'requestName',
        'userId',
        'assetId',
        'access_action',
        'access_type',
        'context',
        'rejection_remark',
        'status',
        'duration',
        'access_level',
        'request_metadata'
        )
        ->get();

        return response()->json($detail);
    }
}
