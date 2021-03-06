<?php

namespace App\Models\Factory;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Cacheable;

class BaseFactory extends Model
{
    use Cacheable;

    const DISABLE_LOGS = true;
    protected $connection = 'factory';

    public static function getTitle($id, $titleField = 'title')
    {
        return static::whereId($id)->value($titleField);
    }
}
