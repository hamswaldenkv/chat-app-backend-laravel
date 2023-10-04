<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'id'                    => $this->chat_message_id,
            'kind'                  => $this->kind,
            'user_id'               => $this->user_id,
            'content'               => $this->content,
            'metafield'             => $this->metafield,
            'status'                => $this->status,
            'delivered_user_ids'    =>  $this->delivered_user_ids,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
