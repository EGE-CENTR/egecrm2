<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'problems' => $this->problems,
            'has_clients' => $this->clients()->exists(),
        ]);
    }
}
