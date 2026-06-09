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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->cascadeOnDelete();
            $table->foreignId('calendarId')->constrained('calendars')->cascadeOnDelete();
            $table->foreignId('smartRequestId')->nullable()->constrained('smart_requests')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('startAt')->nullable();
            $table->timestamp('endAt')->nullable();
            $table->string('timezone');
            $table->text('location')->nullable();
            $table->string('meetingURL')->nullable();
            $table->string('status')->default('draft');
            $table->string('priority')->default('medium');
            $table->string('source')->default('manual');
            $table->boolean('isAllDay')->default(false);
            $table->boolean('isRecurring')->default(false);
            $table->boolean('createByAI')->default(false);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
