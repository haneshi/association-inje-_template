<?php

namespace App\Models;

use App\Traits\GlobalScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, GlobalScopes;
    protected $table = "admin";

    protected $guarded = []; // 대량할당 수정 가능
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth' => 'date',
    ];
}
