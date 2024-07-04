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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('website')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('lead_industry')->nullable();
            $table->string('lead_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('website');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('lead_industry');
            $table->dropColumn('lead_type');
        });
    }
};
