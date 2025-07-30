<?php

namespace App\Handlers\AccessHandlers;

use App\Handlers\AccessHandlersInterface;
use Illuminate\Support\Facades\Http;

class GitlabHandlers implements AccessHandlersInterface{
    public function GrantAccess($record): mixed{
       

        return $response;
    }

    public function RevokeAccess($record): mixed{

    }
}