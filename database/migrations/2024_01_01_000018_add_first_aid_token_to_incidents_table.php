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
        Schema::table('incidents', function (Blueprint $table) {
            $table->string('first_aid_token', 4)->nullable()->unique()->after('status')->comment('4-digit token for first aid linking');
            $table->timestamp('first_aid_token_expires_at')->nullable()->after('first_aid_token')->comment('Token expiration time (7 days from incident creation)');
            $table->boolean('first_aid_recorded')->default(false)->after('first_aid_token_expires_at')->comment('Whether first aid has been recorded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn('first_aid_token');
            $table->dropColumn('first_aid_token_expires_at');
            $table->dropColumn('first_aid_recorded');
        });
    }
};
