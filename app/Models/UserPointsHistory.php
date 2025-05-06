<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPointsHistory extends Model
{
    protected $table = 'user_point_histories';
    protected $fillable = ['user_id', 'points', 'source', 'reference_type', 'reference_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}