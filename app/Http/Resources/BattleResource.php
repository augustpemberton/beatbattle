<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BattleResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'user_id'       => $this->user_id,
            'sample_id'     => $this->sample_id,
            'start_time'    => (string) $this->start_time,
            'end_time'      => (string) $this->end_time,
            'status'        => $this->status
        ];
    }
}
