<?php

namespace App\Http\Controllers;

use App\Handlers\AccessHandlers\GitlabHandlers;
use App\Handlers\AccessHandlers\HandlerResolver;
use App\Models\AccessRequests;
use App\Models\ServiceAssets;
use App\Models\services;
use App\Models\User;
use DB;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $accessRequests->access_action = $data['access_action'];
        $accessRequests->access_type = $data['access_type'];
        $accessRequests->requestName = $assetName . '-' . $requestor . '-Requests' . rand(1,10000);
        $accessRequests->assetId = ServiceAssets::where('id',$data['assetId'])->value('id');
        $accessRequests->context = $data['context'] ?? null;
        $accessRequests->status = 'Pending';
        $accessRequests->duration = $data['duration'] ?? null;
        $accessRequests->access_level = $data['access_level'];
        $accessRequests->request_metadata = $data['request_metadata'] ?? [];
        $accessRequests->save();

        return $accessRequests;
    }


    public static function requestGitlabRepo($data){
        $userid = $data['userid'];

        if (empty($data['gitlabuserId'])){
            $gitlabUserId = RequestsController::getUserId($userid);
        }else {
            $gitlabUserId = $data['gitlabuserId'];
        }

        $valid = GitlabHandlers::checkGitlabUserIdValidity($gitlabUserId);

        if (!$valid){
            return response()->json( [
                'error' => 'Fail to send requests.',
                'message' => 'The Gitlab User ID used is invalid or cannot be found. Please try again.'
            ]);
        }else {
            $data['gitlabuserId'] = $gitlabUserId;
        }

        $projMetadata = ServiceAssets::where('id',$data['assetId'])->value('metadata');
        $projId = $projMetadata['project_id'];
        $accessLevel = GitlabHandlers::getAccessLevel($data,$gitlabUserId,$projId);

        if (isset($accessLevel['error'])){
            if ($data['access_action']== 'Modify'){
                return response()->json([
                    'error' => 'Invalid Requests',
                    'message' => 'No existing access for the accounts. Please requests new access.'
                ]);
            }

            $currentAccessLevel = null;
        }else{
            if ($data ['access_action'] == 'New'){
                $data['access_action'] = 'Modify';
            }
            $currentAccessLevel = $accessLevel['access_level'] ?? null;
        }

        $data['request_metadata']= [
            'gitlabuserId' => $gitlabUserId,
            'currentAccessLevel' => $currentAccessLevel ?? null
            ];

        $requests = RequestsController::newRequests($data);

        return response()->json([
            'message' => 'Successfully Submitted Requests',
            'requestData' => $requests
        ]);


    }

   

    public static function getRequests($requestId,$view){
        return $accessrequests = AccessRequests::where('id',$requestId);
    }

    public static function getRequestsByAssets ($assetId){
        return $accessrequests = AccessRequests::where('assetId',$assetId);
    }

    public static function approveRequests($record){
        

        $serviceId = ServiceAssets::where('id',$record->assetId)->value('serviceId');
        $handler = HandlerResolver::resolve(services::where('id',$serviceId)->value('service_identifier'));
        $response = $handler->GrantAccess($record);
        
        $record->update(
            [
            'status' => 'Approved',
            ]
        );

        return $response instanceof JsonResponse
        ? $response->getData(true)
        : $response;

    }

    public static function rejectRequests ($record,$data){
        $rejectRemark = $data['rejection_remark'];
        $record->update(
            [
                'status' => 'Rejected',
                'rejection_remark' => $rejectRemark,
            ]);

    }

    public static function getUserId($userId){

        $email = User::where('id',$userId)->value('email');
        
        $response = Http::withToken(env('GITLAB_TOKEN'))
        ->get('https://gitlab.teratotech.com/api/v4/users',[
        'search' => $email,
        ]);
        
        $user = $response->json();
        $gitlabUserId = $user[0]['id'];
        
        return $gitlabUserId ?? null;

     }


     public static function updateStatus ($status,$record){
        $record->update([
            'status'=> $status,
        ]);
     }



}
