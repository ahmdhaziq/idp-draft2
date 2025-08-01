<?php

namespace App\Handlers\AccessHandlers;

use App\Handlers\AccessHandlersInterface;
use App\Models\ServiceAssets;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class GitlabHandlers implements AccessHandlersInterface{
    public function GrantAccess($record): mixed{

        $ProjMetadata = ServiceAssets::where('id', $record->assetId)->value('metadata');
        $ProjId = $ProjMetadata['project_id'];
        $gitlabUserId = $record->request_metadata['gitlabuserId'];
        $duration = $record->duration;
        
        $response = Http::withToken( env('GITLAB_TOKEN'))
        ->post('https://gitlab.teratotech.com/haziq.shahril/learning-project/api/v4/projects/'.$ProjId.'/members',[
            'user_id' => $gitlabUserId,
            'access_level' => $record->access_level,
            'expires_at' => $duration
        ]);


        return $response;
    }

    public function RevokeAccess($record): mixed{
        return $record;
    }



}