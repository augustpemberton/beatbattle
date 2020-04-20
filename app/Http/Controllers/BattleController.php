<?php

namespace App\Http\Controllers;

use Auth;
use Timezone;
use Log;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Battle;
use App\Sample;
use App\Entry;
use App\Events\NewBattle;
use App\Traits\SaveSamples;
use App\Http\Resources\BattleResource;
use App\Http\Requests\CreateBattleRequest;
use App\Http\Requests\UpdateBattleRequest;

class BattleController extends BaseController
{
    use SaveSamples;
    public function __construct() {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index() {
        return BattleResource::collection(Battle::with('entries')->with('samples')->paginate(10));
    }

    public function store(CreateBattleRequest $request) {
        $battle = new Battle();

        // convert times into UTC
        $start_time = Carbon::parse($request->input('start_time'), 'UTC');
        $end_time = Carbon::parse($request->input('end_time'), 'UTC');
        $voting_time = Carbon::parse($request->input('voting_time'), 'UTC');

        // subtract one minute from start time, to make games able to start *now*
        $start_time->subtract(1, 'minutes');

        $battle->name           = $request->input('name');
        $battle->description    = $request->input('description', null);
        $battle->start_time     = $start_time;
        $battle->end_time       = $end_time;
        $battle->voting_time    = $voting_time;
        $battle->user_id        = Auth::user()->id;
        $battle->status         = config('battle.status.upcoming');
        $battle->save();

        // save samples
        foreach ($request->file('samples') as $s) {
          $sample = $this->saveSample($s, $request->user());
          $battle->samples()->attach($sample->id);
        }

        broadcast(new NewBattle());

        return new BattleResource($battle);
    }

    public function show (Battle $battle) {
        return new BattleResource($battle);
    }

    public function update (UpdateBattleRequest $request, Battle $battle) {
        if ($request->user()->id != $battle->user_id) {
            return $this->sendError('You can only edit your own battles.', 403);
        }

        if ($battle->end_time->isPast()) {
          return $this->sendError('This battle has already finished.', 422);
        }

        if ($battle->start_time->isPast()) {
          $battle->update($request->only(['name', 'description', 'end_time']));
        } else {
          $battle->update($request->only(['name', 'description', 'start_time', 'end_time']));
        }


        return new BattleResource($battle);
    }

    public function destroy (Request $request, Battle $battle) {
        if ($request->user()->id != $battle->user_id) {
            return $this->sendError('You can only delete your own battles.', 403);
        }
        
        $battle->delete();
        return $this->sendEmptyResponse();
    }
}
