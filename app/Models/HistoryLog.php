<?php

namespace App\Models;

use App\Traits\GlobalScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistoryLog extends Model
{
    use HasFactory, GlobalScopes;

    public $timestamps = false;

    protected $guarded = [];

    public function loggable() : MorphTo
    {
        return $this->morphTo();
    }
}
