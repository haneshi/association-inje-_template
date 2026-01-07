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
        Schema::create('travel', function (Blueprint $table) {
            $table->id()->comment('관광지 고유값');
            $table->integer('seq')->default(9999)->comment('순서');

            $table->string('name', 50)->comment('관광지 이름');
            $table->string('name_eng', 100)->nullable()->comment('관광지 이름(영문)');

            $table->string('post', 10)->nullable()->comment('관광지 우편번호');
            $table->string('address_basic', 50)->nullable()->comment('도로명주소');
            $table->string('address_local', 50)->nullable()->comment('도로명 동');
            $table->string('address_detail', 50)->nullable()->comment('도로명 상세주소');
            $table->string('address_jibun', 50)->nullable()->comment('지번주소');

            $table->string('lat', 50)->nullable()->comment('위도');
            $table->string('lng', 50)->nullable()->comment('경도');

            $table->text('content')->nullable()->comment('내용');

            $table->boolean('is_active')->default(true)->comment('사용유무');
            $table->timestamps();

            $table->comment('관광지');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel');
    }
};
