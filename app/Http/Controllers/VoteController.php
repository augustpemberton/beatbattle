<?php

namespace App\Http\Controllers;
use Log;
use App\Vote;
use App\Battle;
use App\Entry;
use App\Http\Resources\VoteResource;
use App\Http\Requests\VoteRequest;
use Illuminate\Http\Request;

class VoteController extends BaseController
{
    public function __construct() {
        $this->middleware('auth:api')->except(['show']);
    }

    public function show($battleID) {
      $battle = Battle::findOrFail($battleID);

      $votes = Vote::whereHas('entry', function ($q) use (&$battle) {
        $q->where('battle_id', $battle->id);
      })->with('user')->with('entry')->get();
      
      return VoteResource::collection($votes);
    }

    public function store(VoteRequest $request) {
      $vote = new Vote;
      $entry = Entry::findOrFail($request->input('entry_id'));
      if ($entry->user->id == $request->user()->id) {
        return $this->sendError('You cannot vote for yourself.', 422);
      }
      $vote->user_id = $request->user()->id;
      $vote->entry_id = $request->input('entry_id');
      $vote->save();

      $votes_made = $vote->votes_in_same_game();
      return $this->sendResponse(['votes' => $votes_made]);
    }

    public function destroy(Request $request, $entry_id) {
      $vote = Vote::where('user_id', $request->user()->id)
        ->where('entry_id', $entry_id);

      $vote->delete();
      return $this->sendEmptyResponse();
    }
}
