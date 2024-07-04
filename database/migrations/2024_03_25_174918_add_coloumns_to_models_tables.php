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
            $table->string('mail_mailer');
            $table->string('mail_host');
            $table->string('mail_port');
            $table->string('mail_username');
            $table->string('mail_password');
            $table->string('twilio_accountid');
            $table->string('twilio_auth_token');
            $table->string('twilio_sms_from');
            $table->string('vonage_key');
            $table->string('vonage_secret');
            $table->string('vonage_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_models', function (Blueprint $table) {
            //
        });
    }
};
