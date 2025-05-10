<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'directory_id',
        'file_id',
        'valid_until',
        'code',
    ];
}
