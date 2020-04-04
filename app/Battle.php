<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $fillable = ['name', 'description', 'user_id', 'sample_id', 'start_time', 'end_time', 'status'];

    public function entries() {
        return $this->hasMany('App\Entry');
    }

    public function sample() {
        return $this->hasOne('App\Sample');
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value)->format('Y-m-d h:m:s');
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::parse($value)->format('Y-m-d h:m:s');
    }

}
