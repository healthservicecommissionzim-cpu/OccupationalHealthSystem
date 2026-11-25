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
        // Drop tables in reverse order of foreign key dependencies
        Schema::dropIfExists('roster_audit_logs');
        Schema::dropIfExists('roster_assignments');
        Schema::dropIfExists('cost_centers');
        Schema::dropIfExists('staff_rosters');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables can be recreated from backup if needed
        // This is intentionally left empty as the original migrations were deleted
    }
};
