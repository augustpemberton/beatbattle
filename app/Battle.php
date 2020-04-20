<?php

namespace App;

use Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $fillable = ['name', 'description', 'user_id', 'sample_id', 'start_time', 'end_time', 'voting_time', 'status'];
    
    protected $dates = ['start_time', 'end_time', 'voting_time'];

    public function entries() {
        return $this->hasMany('App\Entry');
    }

    public function samples() {
        return $this->belongsToMany('App\Sample');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function winner() {
      return $this->belongsTo('App\User', 'winner_id');
    }

    public static function boot() {
      parent::boot();

      self::deleting(function($battle) {
        Log::info('deleting battle ' . $battle->id);
        foreach ($battle->entries as $entry) {
          $entry->delete();
        }
        foreach ($battle->samples as $sample) {
          $sample->tryDelete($battle->id, null);
        }
      });
    }

}
