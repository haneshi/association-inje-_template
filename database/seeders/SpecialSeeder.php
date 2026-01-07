<?php

namespace Database\Seeders;

use App\Models\Special;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $seq = 1;
        $specials = [
            [
                'name' => '사과',
                'content' => '달고 맛있는 사과',
                'is_active' => true,
            ],
            [
                'name' => '바나나',
                'content' => '부드럽고 달콤한 열대과일',
                'is_active' => true,
            ],
            [
                'name' => '딸기',
                'content' => '새콤달콤한 봄의 대표 과일',
                'is_active' => true,
            ],
            [
                'name' => '수박',
                'content' => '시원하고 달콤한 여름 과일',
                'is_active' => false,
            ],
            [
                'name' => '포도',
                'content' => '알알이 톡톡 터지는 포도',
                'is_active' => true,
            ],
            [
                'name' => '오렌지',
                'content' => '비타민C가 풍부한 상큼한 과일',
                'is_active' => true,
            ],
            [
                'name' => '복숭아',
                'content' => '부드럽고 향긋한 여름 과일',
                'is_active' => true,
            ],
            [
                'name' => '망고',
                'content' => '달콤하고 진한 열대 과일의 왕',
                'is_active' => false,
            ],
        ];

        foreach($specials as $special) {
            Special::create([
                'seq' => $special['is_active'] ? $seq++ : 9999,
                'name' => $special['name'],
                'content' => $special['content'],
                'is_active' => $special['is_active'],
            ]);
        }
    }
}
