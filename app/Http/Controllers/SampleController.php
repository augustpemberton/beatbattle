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

    public function show(Sample $sample) {
        return Storage::disk('uploads')->download($sample->path, $sample->filename);
        //return new SampleResource($sample);
    }

}
