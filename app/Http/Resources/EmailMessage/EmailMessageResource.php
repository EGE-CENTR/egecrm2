<?php

namespace App\Http\Resources\EmailMessage;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;

class EmailMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(extractFields([
            'id', 'files', 'created_at', 'subject', 'message', 'email'
        ], $this), [
            'createdAdmin' => new AdminResource($this->createdAdmin),
        ]);
    }
}