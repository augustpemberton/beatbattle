<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Timezone;
use Carbon\Carbon;

use App\Http\Resources\SampleResource;

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
          'id'            => $this->id,
          'name'          => $this->name,
          'description'   => $this->description,
          'user'          => $this->user,
          'samples'       => SampleResource::collection($this->samples),
          'start_time'    => $this->start_time,
          'end_time'      => $this->end_time,
          'voting_time'   => $this->voting_time,
          'status'        => $this->status
      ];
    }
}
