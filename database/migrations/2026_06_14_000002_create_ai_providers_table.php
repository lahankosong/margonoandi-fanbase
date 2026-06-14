<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // label tampilan (mis. "Gemini Flash")
            $table->string('base_url');                  // endpoint OpenAI-compatible / anthropic
            $table->text('api_key')->nullable();         // disimpan terenkripsi
            $table->string('model');                     // mis. gemini-2.0-flash
            $table->string('format')->default('openai'); // openai | anthropic
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
