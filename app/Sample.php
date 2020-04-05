<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = ['user_id', 'filename', 'path', 'size'];
    public function User() {
        return $this->belongsTo('App\User');
    }
}
