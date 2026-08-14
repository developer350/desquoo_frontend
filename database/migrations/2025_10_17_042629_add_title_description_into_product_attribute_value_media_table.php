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
        Schema::table('product_attribute_value_media', function (Blueprint $table) {
            $table->string('title')->nullable()->after('attribute_value_id');
            $table->text('description')->nullable()->after('title');
            $table->decimal('price', 19, 2)->nullable()->after('height');
            $table->string('depth')->nullable()->after('height');
            $table->integer('sort_order')->default(0)->after('price');
            $table->boolean('is_default')->default(false)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attribute_value_media', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('price');
            $table->dropColumn('depth');
            $table->dropColumn('sort_order');
            $table->dropColumn('is_default');
        });
    }
};
