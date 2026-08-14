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
        Schema::table('pincodes', function (Blueprint $table) {
            $table->dropColumn('delivery_text');
            $table->integer('delivery_days')->default(3)->after('pincodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pincodes', function (Blueprint $table) {
            $table->text('delivery_text')->nullable()->after('pincodes');
            $table->dropColumn('delivery_days');
        });
    }
};
