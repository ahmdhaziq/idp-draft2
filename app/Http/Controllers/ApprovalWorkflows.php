<?php

namespace App\Http\Controllers;

use App\Models\AccessRequests;
use Illuminate\Http\Request;

class ApprovalWorkflows extends Controller
{
    //
    public static function approveRequests($record){
        $record->update(
            [
            'status' => 'Approved',
            ]
        );
    }

    public static function rejectRequests ($record){
        $record->update(
            [
                'status' => 'Approved',
            ]
            );
    }
}
