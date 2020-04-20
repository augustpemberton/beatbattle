<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
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
          'battle_id'         => $this->battle_id,
          'id'                => $this->id,
          'listenable_early'  => $this->listenable_early,
          'notes'             => $this->notes,
          'sample'            => $this->sample,
          'user'              => $this->user,
          'date'              => $this->date_created
        ];
    }
}
