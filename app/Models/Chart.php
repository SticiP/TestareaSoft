<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $fillable = [
        'user_id', 'lib', 'type', 'data', 'title'
    ];

    protected $casts = [
        'devices' => 'array',
        'sensors' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
