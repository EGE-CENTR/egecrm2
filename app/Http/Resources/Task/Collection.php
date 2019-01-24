<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\Resource as CommentResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'comments' => CommentResource::collection($this->comments),
        ]);
    }
}
