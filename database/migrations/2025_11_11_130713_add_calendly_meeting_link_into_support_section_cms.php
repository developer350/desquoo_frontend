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
        Schema::table('support_section_cms', function (Blueprint $table) {
            $table->text('calendly_meeting_link')->nullable()->after('get_a_virtual_demo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_section_cms', function (Blueprint $table) {
            $table->dropColumn('calendly_meeting_link');
        });
    }
};
