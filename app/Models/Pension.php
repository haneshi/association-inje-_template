<?php

namespace App\Models;

use App\Traits\GlobalScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pension extends Model
{
    use HasFactory, GlobalScopes;

    protected $table = "pension";
    protected $guarded = [];


    public function files(): MorphMany
    {
        return $this->morphMany(DataFile::class, 'fileable')->orderBy('seq');
    }

    public function scopeActive($query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(PensionRoom::class, 'pension_id', 'id');
    }
}
