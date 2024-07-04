<?php

use App\Enums\GeneralStatus;
use App\Enums\LeadSource;
use App\Models\Company;
use App\Models\LeadStatus;
use App\Models\User;
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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('email_status')->nullable();

            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();

            $table->string('client_subject')->nullable();
            $table->string('client_message')->nullable();

            $table->string('qualification')->nullable();
            $table->string('work_experience')->nullable();
            $table->timestamp('last_email')->nullable();

            $table->json('data_array')->nullable();
            $table->integer('facebook_lead_id')->nullable();
            $table->enum('source', [LeadSource::getInstances()])->nullable();
            $table->string('comments')->nullable();

            $table->foreignIdFor(User::class, 'agent_id')->nullable()->constrained('users');
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(LeadStatus::class, 'status')->constrained('lead_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
