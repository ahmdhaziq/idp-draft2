<?php

use App\Http\Controllers\ApiController\AccessRequestsAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/accessRequests',[AccessRequestsAPI::class,'listAccessRequests']);
Route::get('/accessRequests/{assetId}',[AccessRequestsAPI::class,'accessRequestbyAsset']);
Route::get('/accessRequest/{id}',[AccessRequestsAPI::class,'accessRequestDetail']);