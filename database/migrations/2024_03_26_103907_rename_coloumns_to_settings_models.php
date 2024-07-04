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
            $table->renameColumn('mailgun_domian', 'MAILGUN_DOMAIN');
            $table->renameColumn('mailgun_secret', 'MAILGUN_SECRET');
            $table->renameColumn('mail_mailer', 'MAIL_MAILER');
            $table->renameColumn('mail_host', 'MAIL_HOST');
            $table->renameColumn('mail_port', 'MAIL_PORT');
            $table->renameColumn('mail_username', 'MAIL_USERNAME');
            $table->renameColumn('mail_password', 'MAIL_PASSWORD');
            $table->renameColumn('twilio_accountid', 'TWILIO_ACCOUNT_SID');
            $table->renameColumn('twilio_auth_token', 'TWILIO_AUTH_TOKEN');
            $table->renameColumn('twilio_sms_from', 'TWILIO_SMS_FROM');
            $table->renameColumn('vonage_key', 'VONAGE_KEY');
            $table->renameColumn('vonage_secret', 'VONAGE_SECRET');
            $table->renameColumn('vonage_number', 'VONAGE_NUMBER');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_models', function (Blueprint $table) {
            $table->renameColumn('mailgun_domian', 'MAILGUN_DOMAIN');
            $table->renameColumn('mailgun_secret', 'MAILGUN_SECRET');
            $table->renameColumn('mail_mailer', 'MAIL_MAILER');
            $table->renameColumn('mail_host', 'MAIL_HOST');
            $table->renameColumn('mail_port', 'MAIL_PORT');
            $table->renameColumn('mail_username', 'MAIL_USERNAME');
            $table->renameColumn('mail_password', 'MAIL_PASSWORD');
            $table->renameColumn('twilio_accountid', 'TWILIO_ACCOUNT_SID');
            $table->renameColumn('twilio_auth_token', 'TWILIO_AUTH_TOKEN');
            $table->renameColumn('twilio_sms_from', 'TWILIO_SMS_FROM');
            $table->renameColumn('vonage_key', 'VONAGE_KEY');
            $table->renameColumn('vonage_secret', 'VONAGE_SECRET');
            $table->renameColumn('vonage_number', 'VONAGE_NUMBER');
        });
    }
};
