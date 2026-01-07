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
        Schema::create('special', function (Blueprint $table) {
            $table->id()->comment('특산품 고유값');
            $table->integer('seq')->default(9999)->comment('순서');

            $table->string('name', 100)->comment('특산품 이름');
            $table->text('content')->comment('내용');

            $table->boolean('is_active')->default(true)->comment('사용유무');
            $table->timestamps();

            $table->comment('특산품');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('special');
    }
};
