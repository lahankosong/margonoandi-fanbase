<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('band_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('roles_needed')->nullable(); // koma: "gitaris,drummer"
            $table->string('genres')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('open');   // open | closed
            $table->boolean('urgent')->default(false);
            $table->timestamps();

            $table->index('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('band_posts');
    }
};
