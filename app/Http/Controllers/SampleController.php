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

        $file_hash = md5_file($file->path());

        // If the user has already uploaded this file, use that
        $prev_sample = Sample::where('file_hash', $file_hash)->first();
        if ($prev_sample) {
          return new SampleResource($prev_sample);
        }

        $time_hash = hash('sha256', time());
        $path = $request->file('file')->store(
            $time_hash.$filename, 'uploads'
        );

        $sample = new Sample();
        $sample->user_id = $request->user()->id;
        $sample->filename = $filename;
        $sample->path = $path;
        $sample->file_hash = $file_hash;
        $sample->size = $file->getSize();
        $sample->save();

        return new SampleResource($sample);
    }

    public function show(Sample $sample) {
        return Storage::disk('uploads')->download($sample->path, $sample->filename);
        //return new SampleResource($sample);
    }

}
