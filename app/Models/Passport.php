<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'series', 'number', 'birthday', 'issued_date', 'issued_by', 'address', 'code'
    ];
}