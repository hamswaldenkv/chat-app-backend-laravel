<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'id'            => $this->chat_id,
            'event_id'      => $this->event != null ? $this->event->event_id : null,
            'kind'          => $this->kind,
            'user_ids'      => $this->userIds(),
            'title'         => $this->title(),
            'description'   => $this->last_message,
            'status'        => $this->status,
            'photo_url'     =>  null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
