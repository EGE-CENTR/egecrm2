<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;

class GroupClient extends Model
{
    const DISABLE_LOGS = true;

    // не работает с удалением
    // protected $touches = ['group'];

    public $timestamps = false;
    protected $fillable = ['group_id', 'client_id'];
    public $incrementing = false;

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
