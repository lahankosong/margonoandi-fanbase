<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category'); // teori | produksi | kolaborasi | rilis
            $table->unsignedTinyInteger('batch')->default(1);
            $table->text('excerpt');
            $table->longText('content_markdown');
            $table->unsignedSmallInteger('reading_time')->default(8); // menit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
