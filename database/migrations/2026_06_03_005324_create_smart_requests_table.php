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
        Schema::create('smart_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->cascadeOnDelete();
            $table->text('rawText');
            $table->string('intent')->nullable();
            $table->string('extractedTitle')->nullable();
            $table->text('extractedDescription')->nullable();
            $table->timestamp('extractedStartAt')->nullable();
            $table->timestamp('extractedEndAt')->nullable();
            $table->json('extractedParticipants')->nullable();
            $table->json('extractedData')->nullable();
            $table->string('status')->default('pending');
            $table->text('errorMessage')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_requests');
    }
};
