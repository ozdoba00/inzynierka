<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return[
            "id"=> $this->id,
            "name"=> $this->name,
            "email"=> $this->email,
            "email_verified_at"=> $this->email_verified_at,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            "last_name"=> $this->last_name,
            "profile_image"=> $this->profile_image ? Storage::url($this->profile_image) : $this->profile_image,
            "date_of_birth"=> $this->date_of_birth,
            "gender"=> $this->gender
        ];

    }
}
