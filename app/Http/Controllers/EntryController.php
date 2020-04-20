<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Entry;
use App\Battle;
use App\Events\NewEntry;
use App\Events\DeleteEntry;
use App\Traits\SaveSamples;
use App\Http\Resources\EntryResource;
use App\Http\Requests\CreateEntryRequest;
use App\Http\Requests\UpdateEntryRequest;

class EntryController extends BaseController
{
    use SaveSamples;
    public function __construct() {
        $this->middleware('auth:api')->except(['show']);
    }

    public function show($battleID) {
        return EntryResource::collection(
            Entry::where('battle_id', $battleID)->with('sample')->with('user')->get()
        );
    }

    public function store(CreateEntryRequest $request) {
        // validate entry date
        $battle = Battle::findOrFail($request->battle_id);
        if ($battle->end_time < Carbon::now('UTC')) {
          return $this->sendError('This game has already ended', 422);
        }

        // save sample
        $sample = $this->savesample($request->file('samples')[0], $request->user());

        $entry = new Entry();
        $entry->sample_id = $sample->id;
        $entry->battle_id = $request->battle_id;
        $entry->user_id = Auth::user()->id;
        $entry->listenable_early = $request->listenable_early;
        $entry->save();

        broadcast(new NewEntry($request->battle_id));

        return new EntryResource($entry);
    }

    public function update (UpdateEntryRequest $request, Entry $entry) {
        if ($request->user()->id != $entry->user_id) {
            return $this->sendError('You can only edit your own entries', 403);
        }

        // Upload new sample
        $sample = $this->savesample($request->file('samples')[0], $request->user());

        // Delete old sample
        if ($request->input('sample_id') != $entry->sample_id) {
          $entry->sample->tryDelete(null, $entry->id);
        }

        $entry->sample_id = $sample->id;
        $entry->notes = $request->notes;
        $entry->listenable_early = $request->listenable_early;
        $entry->save();

        return new EntryResource($entry);
    }

    public function destroy(Request $request, Entry $entry) {
        if ($request->user()->id != $entry->user_id) {
            return $this->sendError('You can only edit your own entries', 403);
        }
        $entry->sample->tryDelete();
        $entry->delete();

        broadcast(new DeleteEntry($entry->user_id, $entry->battle_id));
        return $this->sendEmptyResponse();
    }
}
