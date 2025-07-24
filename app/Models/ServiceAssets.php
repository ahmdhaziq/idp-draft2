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

    public function services(){
        return $this->belongsTo(services::class);
    }
}
