<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected $admins = [
        [
            "user_id" => "devel",
            "password" => "devel",
            "name" => "개발권한",
            "auth" => "D",
        ],
        [
            "user_id" => "admin",
            "password" => "admin",
            "name" => "최고관리자",
            "auth" => "S",
        ],
        [
            "user_id" => "normal",
            "password" => "normal",
            "name" => "일반관리자",
            "auth" => "A",
        ],
    ];
    public function run(): void
    {
        foreach($this->admins as $index => $admin) {
            Admin::create([
                'user_id' => $admin['user_id'],
                'password' => Hash::make($admin['password']),
                'name' => $admin['name'],
                'auth' => $admin['auth'],
                'is_active' => true,
            ]);
        }
    }
}
