<?php

namespace App\Models;

use App\Traits\GlobalScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SebastianBergmann\CodeCoverage\Node\Builder;

class Special extends Model
{
    use HasFactory, GlobalScopes;

    protected $table = 'special';
    protected $guarded = [];

    public function files() : MorphMany
    {
        return $this->morphMany(DataFile::class, 'fileable')->orderBy('seq');
    }

    public function scopeActive($query) : Builder
    {
        return $query->where('is_active', 1);
    }
}
