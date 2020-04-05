<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Sample;
use App\Http\Resources\SampleResource;
use App\Http\Requests\SampleUploadRequest;

class SampleController extends BaseController
{
    public function __construct() {
        $this->middleware('auth:api')->except(['show']);
    }

    public function index(Request $request) {
        return SampleResource::collection(
            Sample::where('user_id', $request->user()->id)->get()
        );
    }
    public function store(SampleUploadRequest $request) {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $hash = hash('sha256', time());

        $path = $request->file('file')->store(
            $hash.$filename, 'uploads'
        );

        $sample = new Sample();
        $sample->user_id = $request->user()->id;
        $sample->filename = $filename;
        $sample->path = $path;
        $sample->size = $file->getSize();
        $sample->save();

        return new SampleResource($sample);
    }

    public function show(Sample $sample) {
        return Storage::download($sample->path, $sample->filename);
        //return new SampleResource($sample);
    }

}
