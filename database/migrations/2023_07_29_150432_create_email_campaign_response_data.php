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
        Schema::create('email_campaign_response_data', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable();
            $table->string('recipient')->nullable();
            $table->string('event_type')->nullable();
            $table->text('event_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaign_response_data');
    }
};
