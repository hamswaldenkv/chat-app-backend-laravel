<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $participants = [];
        foreach ($this->participants as $item) {
            $user = $item->user;

            $row['id'] = $user->user_id;
            $row['participation_id'] = $item->event_participant_id;
            $row['name'] = $user->name;
            $row['first_name'] = $user->first_name;
            $row['last_name'] = $user->last_name;
            $row['photo_url'] = $user->photo_url;

            $participants[] = $row;
        }

        return [
            'id'                    => $this->event_id,
            'title'                 => $this->title,
            'description'           => $this->description,
            'organisator_name'      => $this->organisator_name,
            'venue_place'           => $this->venue_place,
            'venue_address'         => $this->venue_address,
            'is_live'               => $this->is_live,
            'is_free'               => $this->is_free,
            'count_participants'    => count($participants),
            'status'                => $this->status,
            'start_at'              => date('c', $this->start_at),
            'finish_at'             => date('c', $this->finish_at),
            'created'               => $this->created_at,
            'updated'               => $this->updated_at,
            'participants'          => $participants,
        ];
    }
}
