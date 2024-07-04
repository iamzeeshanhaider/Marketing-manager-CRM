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
        Schema::table('settings_models', function (Blueprint $table) {
            $table->string('MAIL_FROM_ADDRESS')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_models', function (Blueprint $table) {
            $table->dropColumn('MAIL_FROM_ADDRESS');
        });
    }
};