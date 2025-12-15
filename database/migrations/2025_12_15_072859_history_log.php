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
        Schema::create('history_logs', function (Blueprint $table){
            $table->id();

            $table->nullableMorphs('author');
            $table->nullableMorphs('modelable');

            $table->enum('type', ['login', 'create', 'update', 'delete', 'restore', 'error', 'other'])->comment('타입');

            $table->string('description')->nullable()->comment('설명');
            $table->json('queryData')->nullable()->comment('쿼리 데이터');
            $table->json('rowData')->nullable()->comment('데이터');

            $table->ipAddress('ip')->comment('IP 주소');
            $table->text('user_agent')->nullable()->comment('접속 정보');

            $table->timestamp('created_at')->useCurrent()->comment('생성 일시');

            $table->index('type');
            $table->comment('로그 기록');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('history_logs');
    }
};
