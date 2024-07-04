<?php

use App\Models\Company;
use App\Models\LeadStatus;
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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Company::class, 'company_id')->constrained('companies');
            $table->foreignIdFor(LeadStatus::class, 'lead_status_id')->constrained('lead_statuses');
            $table->enum('email_status', ['Sent', 'Opened', 'Clicked', 'Bounced', 'Unsubscribed', 'Spam', 'Invalid', 'Deferred', 'Blocked', 'Error', 'Scheduled', 'Queued', 'Expired', 'Deleted', 'Unconfirmed', 'Active', 'InActive']);
            $table->enum('type', ['SMS', 'Email']);
            $table->text('email_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
