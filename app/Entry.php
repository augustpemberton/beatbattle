<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['user_id', 'listenable_early', 'duration', 'battle_id'];

    public function battle() {
        return $this->belongsTo('App\Battle');
    }
}
