<?php

namespace App;

use Log;
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

    public function votes() {
      return $this->hasMany('App\Vote');
    }

    public static function boot() {
      parent::boot();

      self::deleting(function($entry) {
        Log::info('Deleting entry: ' . $entry->id);
        $entry->sample->tryDelete(null, $entry->id);
      });
    }
}
