<?php

namespace App;

use App\Entry;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'entry_id'];

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function entry() {
      return $this->belongsTo('App\Entry', 'entry_id', 'id');
    }

    public function votes_in_same_game() {
      return Vote::with(['entry' => function ($q) {
        $q->where('battle_id', $this->entry->battle_id);
      }])->where('user_id', $this->user_id)->count();
    }
}
