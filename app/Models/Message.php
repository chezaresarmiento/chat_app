<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Message extends Model
{
    use HasApiTokens;
    //
    protected $fillable = ['user_id', 'content','roomid'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
