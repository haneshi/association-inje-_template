<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travel';

    protected $guarded = [];

    public function files(): MorphMany
    {
        return $this->morphMany(DataFile::class, 'fileable')->orderBy('seq');
    }
}
