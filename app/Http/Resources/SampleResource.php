<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'filename'  => $this->filename,
            'id'        => $this->id,
            'user'      => $this->user
        ];
    }
}
