<?php

namespace App;

use App\Entry;
use App\Battle;

use Log;
use Storage;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = ['user_id', 'filename', 'path', 'size'];
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function battles() {
      return $this->belongsToMany('App\Battle');
    }

    public function otherBattles($battleid) {
      return $this->belongsToMany('App\Battle')->where('battles.id', '<>', $battleid);
    }

    /* Delete sample if not used anywhere. */
    public function tryDelete($ignoreBattle = null, $ignoreEntry = null) {
      $entries = Entry::where('sample_id', $this->id)->where('id', '<>', $ignoreEntry)->exists();
      $battles = $this->otherBattles($ignoreBattle)->exists();
      if (!$entries && !$battles) {
        Log::info('deleting sample ' . $this->id);
        Storage::disk('uploads')->delete($this->path);
        $this->delete();
      } else {
        Log::info('not deleting sample ' . $this->id);
      }
    }
}
