<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'birth_date' => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'birth_time' => $this->birth_time,
            'birth_time_accuracy' => $this->birth_time_accuracy,
            'birth_place' => $this->birth_place,
            'birth_notes' => $this->birth_notes,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'marriage_date' => $this->marriage_date ? $this->marriage_date->format('Y-m-d') : null,
            'religion' => $this->religion,
            'caste' => $this->caste,
            'gotra' => $this->gotra,
            'nakshatra' => $this->nakshatra,
            'rashi' => $this->rashi,
            'about_me' => $this->about_me,
            'additional_notes' => $this->additional_notes,
            'is_profile_complete' => $this->is_profile_complete,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d') : null,
        ];
    }
}
