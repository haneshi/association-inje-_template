<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getPreviewAttribute()
    {
        return $this->file_path ? asset('data/'.$this->file_path): null;
    }
}
