<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Sample;
use App\Http\Requests\SampleUploadRequest;

trait SaveSamples {
  public function saveSample($file, $user) {
    $filename = $file->getClientOriginalName();

    $file_hash = md5_file($file->path());

    // If the user has already uploaded this file, use that
    $prev_sample = Sample::where('file_hash', $file_hash)->first();
    if ($prev_sample) {
      return $prev_sample;
    }

    $path = $file->store(
      'samples/'.$user->id, 'uploads'
    );

    $sample = new Sample();
    $sample->user_id = $user->id;
    $sample->filename = $filename;
    $sample->path = $path;
    $sample->file_hash = $file_hash;
    $sample->size = $file->getSize();
    $sample->save();

    return $sample;
  }
}