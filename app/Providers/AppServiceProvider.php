<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

use App\Entry;
use App\Vote;
use Log;

use Validator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningUnitTests()) {
            Schema::defaultStringLength(191);
        }
        Validator::extend('date_difference_minutes', function ($attribute, $value, $parameters, $validator) {
          $firstDate = Carbon::parse(Arr::get($validator->getData(), $parameters[0]));
          $secondDate = Carbon::parse($value)->add(1, 'seconds');
          return($firstDate->diffInMinutes($secondDate) >= $parameters[1]);
        });
        Validator::extend('max_votes', function ($attribute, $value, $parameters, $validator) {
          $entry_id = $value;
          $user_id = $parameters[0];

          $entry = Entry::findOrFail($entry_id);
          $max_votes = $entry->battle->max_votes;

          $votes = Vote::whereHas('entry', function ($q) use (&$entry){
            $q->where('battle_id', $entry->battle_id);
          })->where('user_id', $user_id)->count();

          return ($votes+1 <= $max_votes);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing') && class_exists(DuskServiceProvider::class)) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
