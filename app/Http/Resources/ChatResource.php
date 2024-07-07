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
        $current_user_id  = $request->user()->id;
        $user_ids = [];
        foreach (explode(',', $this->user_ids) as $key) {
            if ($key != $current_user_id) $user_ids[]  = $key;
        }

        return [
            'id'            => $this->chat_id,
            'event_id'      => $this->event != null ? $this->event->event_id : null,
            'kind'          => $this->kind,
            'user_ids'      => $this->userIds(),
            'title'         => $this->title($user_ids),
            'description'   => $this->last_message,
            'status'        => $this->status,
            'photo_url'     =>  null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
