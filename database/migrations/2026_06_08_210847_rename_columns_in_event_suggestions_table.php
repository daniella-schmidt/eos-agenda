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
        Schema::table('event_suggestions', function (Blueprint $table) {
            $table->renameColumn('smartRequesId', 'smartRequestId');
            $table->renameColumn('suggested_start_at', 'suggestedStartAt');
            $table->renameColumn('suggested_end_at', 'suggestedEndAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_suggestions', function (Blueprint $table) {
            $table->renameColumn('smartRequestId', 'smartRequesId');
            $table->renameColumn('suggestedStartAt', 'suggested_start_at');
            $table->renameColumn('suggestedEndAt', 'suggested_end_at');
        });
    }
};
