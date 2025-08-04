<?php

namespace App\Handlers\AccessHandlers;

use App\Handlers\AccessHandlers\AccessHandlersInterface;
use App\Models\ServiceAssets;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class GitlabHandlers implements AccessHandlersInterface{
    public function GrantAccess($record): mixed{

        $ProjMetadata = ServiceAssets::where('id', operator: $record->assetId)->value('metadata');
        $ProjId = $ProjMetadata['project_id'];
        $gitlabUserId = $record->request_metadata['gitlabuserId'];
        $duration = $record->duration ? \Carbon\Carbon::parse($record->duration)->toDateString() : null;


        $action = $record->access_action;
        
        //$type = $record->access_type;

        /*
            - $action == 'New' --> for Users with no current access to the projects/repository
            - $action == 'Modify' --> for users that currently have access to request different access level (e.g Developer -> Maintainer)
            - Temporary/Permanent Access is handled through the 'expires_at' data in the payload sent to the endpoints. If null, permanent access will be provided.
        */

        if ($action == 'New'){
            $payload = [
                'user_id' => $gitlabUserId,
                'access_level' => (int)$record->access_level
            ];
            if (!empty($duration)){
                $payload['expires_at'] = $duration;
            }

            $response = Http::withToken( env('GITLAB_TOKEN'))
            ->post('https://gitlab.teratotech.com/api/v4/projects/'.$ProjId.'/members',$payload); 
      
        } else if ($action == 'Modify'){
            $payload = [
                'access_level' => (int)$record->access_level
            ];
            if (!empty($duration)){
                $payload['expires_at'] = $duration;
            }
            $response = Http::withToken(env('GITLAB_TOKEN'))
            ->put('https://gitlab.teratotech.com/api/v4/projects/'.$ProjId.'/members/'. $gitlabUserId,$payload
            );
        }

        
       
        if ($response->successful()){
            return $response->json();
        }else {
            return $response =response()->json([
                'error' => 'Failed to add user',
                'details' => $response->json(),
                'status'=> $response->status()
            ],500);
        }
        
    }

    public function RevokeAccess($record): mixed{
        return $record;
    }



}