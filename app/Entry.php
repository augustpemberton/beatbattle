<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['sample_id', 'user_id', 'listenable_early', 'battle_id'];

    public function battle() {
        return $this->belongsTo('App\Battle');
    }

    public function sample() {
        return $this->belongsTo('App\Sample');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }
}
