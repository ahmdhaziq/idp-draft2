<?php

use App\Http\Controllers\Api\AccessApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/accessRequests',[AccessApiController::class,'listAccessRequests']);
Route::get('/accessRequests/{assetId}',[AccessApiController::class,'accessRequestbyAsset']);
Route::get('/accessRequest/{id}',[AccessApiController::class,'accessRequestDetail']);
