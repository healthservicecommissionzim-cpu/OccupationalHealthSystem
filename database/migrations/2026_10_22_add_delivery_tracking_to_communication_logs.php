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
        Schema::table('communication_logs', function (Blueprint $table) {
            // Delivery status tracking
            $table->enum('delivery_status', ['PENDING', 'SENT', 'DELIVERED', 'FAILED', 'BOUNCED', 'UNDELIVERABLE'])->default('PENDING')->after('status');
            
            // Delivery attempt tracking
            $table->integer('delivery_attempts')->default(0)->after('delivery_status');
            $table->timestamp('first_attempt_at')->nullable()->after('delivery_attempts');
            $table->timestamp('delivered_at')->nullable()->after('first_attempt_at');
            
            // Detailed failure information
            $table->string('failure_reason')->nullable()->after('delivered_at');
            $table->string('failure_code')->nullable()->after('failure_reason');
            $table->text('delivery_response')->nullable()->after('failure_code');
            
            // Email provider / SMS gateway specific tracking
            $table->string('provider')->nullable()->after('delivery_response');
            $table->string('provider_message_id')->nullable()->after('provider');
            $table->json('provider_metadata')->nullable()->after('provider_message_id');
            
            // Webhook/callback tracking
            $table->boolean('webhook_received')->default(false)->after('provider_metadata');
            $table->timestamp('webhook_received_at')->nullable()->after('webhook_received');
            $table->json('webhook_data')->nullable()->after('webhook_received_at');
            
            // Retry information
            $table->timestamp('next_retry_at')->nullable()->after('webhook_data');
            $table->text('retry_history')->nullable()->after('next_retry_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communication_logs', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_status',
                'delivery_attempts',
                'first_attempt_at',
                'delivered_at',
                'failure_reason',
                'failure_code',
                'delivery_response',
                'provider',
                'provider_message_id',
                'provider_metadata',
                'webhook_received',
                'webhook_received_at',
                'webhook_data',
                'next_retry_at',
                'retry_history',
            ]);
        });
    }
};
