<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRequests extends Model
{
    //

    protected $table = 'access_requests';
    protected $fillable = [
        'requestName',
        'userId',
        'assetId',
        'context',
        'rejection_remark',
        'status',
        'duration',
        'access_level'
    ];

    protected static function booted()
{
    static::creating(function ($model) {
        if (is_null($model->status)) {
            $model->status = 'Pending';
        }
    });
}

public function user(){
    return $this->belongsTo(User::class,'userId');
}

public function asset(){
    return $this->belongsTo(ServiceAssets::class,'assetId');
}

}
