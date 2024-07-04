<?php

use App\Enums\Gender;
use App\Enums\Ethnicity;
use App\Enums\GeneralStatus;
use App\Enums\UKStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->enum('gender', Gender::getInstances())->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('country_id')->unsigned()->nullable();
            $table->string('city')->nullable();
            $table->longText('address')->nullable();

            $table->string('designation')->nullable();
            $table->enum('ethnicity', Ethnicity::getInstances())->nullable();
            $table->enum('uk_status', UKStatus::getInstances())->nullable();
            $table->string('image')->nullable();
            $table->boolean('has_completed_profile')->default(false);

            $table->timestamp('last_login')->nullable();
            $table->string('last_login_ip_address')->nullable();
            $table->string('status')->default(GeneralStatus::Active);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
