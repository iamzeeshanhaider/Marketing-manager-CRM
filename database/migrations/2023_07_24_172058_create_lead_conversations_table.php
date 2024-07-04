<?php

use App\Enums\CampaignTypes;
use App\Models\Campaign;
use App\Models\Lead;
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
        Schema::create('lead_conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [CampaignTypes::getInstances()])->nullable();

            // for all campaign types
            $table->float('rate')->nullable();
            $table->float('price')->nullable();
            $table->string('reference')->nullable(); // from vonage response
            $table->string('network')->nullable(); // from vonage response
            $table->longText('comment')->nullable();
            $table->boolean('is_oubtbound')->default(true);

            // for calls
            $table->string('duration')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('status')->nullable();
            $table->string('uuid')->nullable();

            // for sms
            $table->string('message')->nullable();

            // for emails
            $table->foreignIdFor(Campaign::class)->nullable()->constrained();

            $table->foreignIdFor(Lead::class)->constrained();
            $table->foreignIdFor(User::class, 'agent_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_conversations');
    }
};
