<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
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
          'id'      => $this->id,
          'entry_id' => $this->entry_id,
          'entry'   => $this->entry,
          'user'    => $this->user
        ];
    }
}
