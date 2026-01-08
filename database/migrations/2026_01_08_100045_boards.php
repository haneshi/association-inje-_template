<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('boards', function (Blueprint $table) {
            $table->id()->comment('게시판 고유값');
            $table->unsignedTinyInteger('seq')->default(255)->comment('게시판 순서');
            // unsignedTinyInteger : 0 ~ 255 까지의 범위

            $table->string('board_name')->unique()->comment('게시판 이름');
            $table->string('title')->nullable()->comment('게시판 제목');
            $table->string('type')->comment('게시판 타입');

            $table->boolean('is_fixed')->default(false)->comment('글 고정 유무');
            $table->boolean('is_secret')->default(false)->comment('비밀글 유무');
            $table->boolean('is_comment')->default(false)->comment('댓글 유무');
            $table->boolean('is_period')->default(false)->comment('기간 유무');

            $table->unsignedTinyInteger('page_show_num')->default(value: 12)->comment('한페이지 출력 게수');

            $table->boolean('is_active')->default(true)->comment('사용유무');
            $table->timestamps();
            $table->comment('게시판');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
