<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'directory_id',
        'filename',
        'path',
        'file_size',
        'mime_type',
    ];

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public function getFilePathHash(): string
    {
        return Crypt::encryptString($this->path);
    }

    public function getFileNameHash(): string
    {
        return Crypt::encryptString($this->filename);
    }
}
