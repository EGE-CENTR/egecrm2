<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientTest extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'test' => new Collection($this->test),
        ]);
    }
}
