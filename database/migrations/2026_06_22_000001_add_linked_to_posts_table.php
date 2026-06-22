<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('linked_type', 20)->nullable()->after('is_pinned');
            $table->unsignedBigInteger('linked_id')->nullable()->after('linked_type');
            $table->index(['linked_type', 'linked_id'], 'posts_linked_index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_linked_index');
            $table->dropColumn(['linked_type', 'linked_id']);
        });
    }
};
