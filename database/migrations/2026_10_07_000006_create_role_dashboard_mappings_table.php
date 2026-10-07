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
        Schema::create('role_dashboard_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('dashboard_name'); // 'cos', 'hr', 'supervisor', 'pmd', etc.
            $table->string('dashboard_route'); // '/cos-dashboard', '/dashboards/hr', etc.
            $table->string('controller_action')->nullable(); // For dynamic routing
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Priority if user has multiple roles
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Foreign key
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            // Unique constraint - each role can map to only one dashboard
            $table->unique(['role_id', 'dashboard_name']);

            // Indices
            $table->index('role_id');
            $table->index('dashboard_name');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_dashboard_mappings');
    }
};
