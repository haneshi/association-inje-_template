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
        Schema::create('data_files', function (Blueprint $table) {
            $table->id()->comment('고유값');

            $table->morphs('fileable');

            $table->unsignedTinyInteger('seq')->default(255)->comment('정렬 순서');

            $table->string('file_path')->comment('파일경로');
            $table->string('filename')->comment('파일명');
            $table->string('mime_type')->comment('MIME 타입');

            $table->unsignedInteger('file_size')->comment('파일 크기(Byte)');

            $table->boolean('is_active')->default(true)->comment('사용 여부');

            $table->softDeletes(); // deleted_at
            $table->timestamps();

            $table->comment('사이트 파일');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('data_files');
    }
};
