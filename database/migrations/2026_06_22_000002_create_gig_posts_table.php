<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gig_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 120);
            $table->string('type', 50)->default('lainnya');
            $table->text('description')->nullable();
            $table->string('location', 120)->nullable();
            $table->date('date_event')->nullable();
            $table->text('requirements')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamps();

            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_posts');
    }
};
