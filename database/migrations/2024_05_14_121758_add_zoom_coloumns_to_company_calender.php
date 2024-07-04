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
        Schema::table('company_calender', function (Blueprint $table) {
            $table->string('zoom_client_id')->nullable();
            $table->string('zoom_client_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_calender', function (Blueprint $table) {
            $table->dropColumn('zoom_client_id');
            $table->dropColumn('zoom_client_secret');
        });
    }
};
