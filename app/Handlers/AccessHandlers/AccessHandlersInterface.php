<?php

namespace App\Handlers\AccessHandlers;

interface AccessHandlersInterface{
    public function GrantAccess($record): mixed;
    public function RevokeAccess($record): mixed;
}


