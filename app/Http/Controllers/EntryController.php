<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Entry;
use App\Battle;
use App\Http\Resources\EntryResource;

class EntryController extends BaseController
{
    public function __construct() {
        $this->middleware('auth:api')->except(['show']);
    }

    public function show(Battle $battle) {
        return EntryResource::collection(
            Entry::where('battle_id', $battle->id)->with('sample')->get()
        );
    }

    public function create(CreateEntryRequest $request) {
        $entry = new Entry();
        $entry->sample_id = $request->sample_id;
        $entry->battle_id = $request->battle_id;
        $entry->user_id = Auth::user()->id;
        $entry->listenable_early = $request->listenable_early;
        $entry->save();

        return new EntryResource($entry);
    }

    public function update (Request $request, Entry $entry) {
        if ($request->user()->id != $entry->user_id) {
            return $this->sendError('You can only edit your own entries', 403);
        }

        $entry->update($request->only(['sample_id', 'listenable_early']));

        return new EntryResource($entry);
    }

    public function delete(Entry $entry) {
        if ($request->user()->id != $entry->user_id) {
            return $this->sendError('You can only edit your own entries', 403);
        }
        $entry->delete();
        return $this->sendEmptyResponse();
    }
}
