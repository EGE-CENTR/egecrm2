<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class ClientReviewCollection extends JsonResource
{
    public function toArray($request)
    {
       return extractFields($this, [
           'subject_id', 'grade_id', 'lesson_count', 'teacher_id', 'year', 'client_id', 'review'
        ]);
    }
}
