<?php

namespace App\Http\Controllers;

use Auth;

use App\Battle;
use App\Sample;
use App\Entry;

use Illuminate\Http\Request;

use App\Http\Resources\BattleResource;
use App\Http\Requests\CreateBattle;

class BattleController extends BaseController
{
    public function __construct() {
        //$this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index() {
        return BattleResource::collection(Battle::with('entries')->paginate(10));
    }

    public function store(CreateBattle $request) {
        $battle = new Battle();

        $battle->name           = $request->input('name');
        $battle->description    = $request->input('description', null);
        $battle->sample_id      = $request->input('sample_id');
        $battle->start_time     = $request->input('start_time');
        $battle->end_time       = $request->input('end_time');
        $battle->user_id        = Auth::user()->id;

        $battle->save();

        return $this->sendResponse($battle);
    }

    public function show (Battle $battle) {
        return new BattleResource($battle);
    }

    public function update (Request $request, Battle $battle) {
        if ($request->user()->id != $battle->user_id) {
            return $this->sendError('You can only edit your own battles.', 403);
        }

        $battle->update($request->only(['name', 'description']));

        return new BattleResource($battle);
    }

    public function delete (Battle $battle) {
        $battle->delete();
        return $this->sendEmptyResponse();
    }
}
