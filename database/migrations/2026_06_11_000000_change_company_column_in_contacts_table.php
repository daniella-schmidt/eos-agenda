<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('company')->nullable()->default(null)->change();
        });

        DB::table('contacts')
            ->whereIn('company', ['0', '1'])
            ->update(['company' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('contacts')->update(['company' => '0']);

        Schema::table('contacts', function (Blueprint $table) {
            $table->boolean('company')->default(false)->change();
        });
    }
};
