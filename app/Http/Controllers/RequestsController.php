<?php

namespace App\Http\Controllers;

use App\Models\AccessRequests;
use App\Models\ServiceAssets;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    //save requests to database

    public static function newRequests($data){
        $userid = $data ['userid'];
        $requestor = User::where('id',$userid)->value('name');
        $assetid = $data['assetId'];

        $assetName = ServiceAssets::where('id',$assetid)->value('resource_name');

        $accessRequests = new AccessRequests();
        $accessRequests->userId = $userid;
        $accessRequests->requestName = $assetName . '-' . $requestor . '-Requests' . rand(1,10000);
        $accessRequests->assetId = ServiceAssets::where('id',$data['assets'])->value('id');
        $accessRequests->context = $data['context'] ?? null;
        $accessRequests->status = 'Pending';
        $accessRequests->duration = $data['duration'];
        $accessRequests->access_level = $data['access_level'];
        
        $saveSuccess = $accessRequests->save();

        if ($saveSuccess){
            Notification::make()
            ->title('Requests Successfully Sent!')
            ->body('Your request has been successfuly sent for review.')
            ->success()
            ->send();
        } else {
            Notification::make()
            ->title('Request failed to go through.')
            ->body('Please try again later.')
            ->error()
            ->send();
        }

    }

    public static function getRequests($requestId,$view){
        return $accessrequests = AccessRequests::where('id',$requestId);
    }

    public static function getRequestsByAssets ($assetId){
        return $accessrequests = AccessRequests::where('assetId',$assetId);
    }

    public static function approveRequests($record){
        $record->update(
            [
            'status' => 'Approved',
            ]
        );
    }

    public static function rejectRequests ($record,$data){
        $rejectRemark = $data['rejection_remark'];
        $record->update(
            [
                'status' => 'Rejected',
                'rejection_remark' => $rejectRemark,
            ]
            );
    }



}
