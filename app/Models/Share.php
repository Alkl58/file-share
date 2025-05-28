<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'directory_id',
        'file_id',
        'user_id',
        'valid_until',
        'code',
    ];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class, 'directory_id');
    }
}
