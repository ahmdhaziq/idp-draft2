<?php

namespace App\Handlers;

use App\Handlers\AccessHandlers\GitlabHandlers;

class HandlerResolver{
    public static function resolve($service): AccessHandlersInterface{
        return match ($service){
            'gitlab_repo' => new GitlabHandlers(),
            default => throw new \Exception("No handler found for the service"),
        };
    }
}