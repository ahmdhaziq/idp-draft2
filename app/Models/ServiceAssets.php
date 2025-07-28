<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAssets extends Model
{
    //

    protected $table = 'service_assets';
    protected $fillable = [
        'serviceId',
        'resource_name',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function services(){
        return $this->belongsTo(services::class,'serviceId');
    }
}
