<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->event_participant_id,
            'event_id'      => $this->event_id,
            'event_name'    => $this->event->title,
            'user_id'       => $this->user_id,
            'user_name'     => $this->user->name,
            'status'        => $this->status,
            'created'       => $this->created_at,
            'updated'       => $this->updated_at,
        ];
    }
}
