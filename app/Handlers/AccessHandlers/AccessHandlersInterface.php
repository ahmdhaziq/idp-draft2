<?php

namespace App\Handlers;

interface AccessHandlersInterface{
    public function GrantAccess($record): mixed;
    public function RevokeAccess($record): mixed;
}


