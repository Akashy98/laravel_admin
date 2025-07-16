<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'role_id' => $this->role_id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'profile_image' => $this->profile_image,
            'status' => $this->status,
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'profile' => new UserProfileResource($this->whenLoaded('profile', function () { return $this->profile; })),
            'addresses' => UserAddressResource::collection($this->whenLoaded('addresses', function () { return $this->addresses; })),
            'contacts' => UserContactResource::collection($this->whenLoaded('contacts', function () { return $this->contacts; })),
            'device_tokens' => DeviceTokenResource::collection($this->whenLoaded('deviceTokens', function () { return $this->deviceTokens; })),

            // Add more relationships as needed
        ];
    }
}
