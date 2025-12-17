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
        Schema::create('admin', function (Blueprint $table) {
            $table->id()->comment('고유값'); 
            $table->string('user_id')->unique()->comment('아이디');
            $table->string('password')->comment('비밀번호');
            $table->string('name')->comment('이름');
            $table->enum('auth', ['D', 'A', 'S'])->default('A')->comment('권한 A:일반 관리자, S:최고 관리자');
            $table->boolean('is_active')->default(true)->comment('사용유무');
            $table->timestamps();
            $table->comment('관리자');

            $table->softDeletes()->comment('삭제일시'); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('admin');
    }
};
