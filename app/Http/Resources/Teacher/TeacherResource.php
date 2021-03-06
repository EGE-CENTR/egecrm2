<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phone\PhoneResource;
use App\Http\Resources\Photo\PhotoResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'names', 'subjects_ec', 'default_name', 'in_egecentr', 'disable_bonuses'
        ], [
            'email' => $this->email_string,
            'phones' => $this->phones,
            'photo' => $this->photo,
            'is_banned' => $this->isBanned(),
        ]);
    }
}
