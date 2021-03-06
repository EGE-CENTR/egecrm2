<?php

namespace App\Http\Resources\Photo;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'url', 'url_version', 'url_original', 'url_cropped', 'has_cropped',
            'entity_type', 'entity_id'
        ]);
    }
}
