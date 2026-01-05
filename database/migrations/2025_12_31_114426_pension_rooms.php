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
        Schema::create('pension_rooms', function (Blueprint $table) {
            $table->id()->comment('객실 고유값');
            $table->smallInteger('seq')->default(9999)->comment('순서');
            $table->unsignedBigInteger('pension_id')->comment('펜션고유값');
            $table->string('name')->comment('객실 이름');

            $table->integer('area1')->comment('객실 면적(㎡)');
            $table->integer('area2')->comment('객실 평');

            $table->string('shape')->comment('객실 유형');

            $table->json('amenities')->comment('구비시설(jsonData 형식)'); // json 데이터 형식

            $table->string('etc')->nullable()->comment('특이사항');
            $table->integer('person_min')->comment('기준인원');
            $table->integer('person_max')->comment('최대인원');
            $table->boolean('is_active')->default(true)->comment('사용유무');
            $table->timestamps();

            $table->comment('펜션 객실');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('pension_rooms');
    }
};
