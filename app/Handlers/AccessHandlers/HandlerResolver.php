<?php

namespace App\Handlers\AccessHandlers;

use App\Handlers\AccessHandlers\GitlabHandlers;
use App\Handlers\AccessHandlers\AccessHandlersInterface;

class HandlerResolver{
    public static function resolve($service): AccessHandlersInterface{
        return match ($service){
            'gitlab_repo' => new GitlabHandlers(),
            default => throw new \Exception("No handler found for the service"),
        };
    }
}