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
        Schema::create('event_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->cascadeOnDelete();
            $table->foreignId('smartRequesId')->constrained('smart_requests')->cascadeOnDelete();
            $table->timestamp('suggested_start_at');
            $table->timestamp('suggested_end_at');
            $table->decimal('score', 8, 2);
            $table->text('reason');
            $table->boolean('selected')->default(false);
            $table->timestamp('createdAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_suggestions');
    }
};
