<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('page', 20);           // 'homepage' | 'fanbase'
            $table->string('session_id', 64)->nullable();
            $table->string('ip', 45)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at');       // hanya created_at, tidak perlu updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
