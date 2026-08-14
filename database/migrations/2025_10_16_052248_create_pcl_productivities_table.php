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
        Schema::create('pcl_productivities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_custom_landing_id')->constrained('product_custom_landings');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->text('url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pcl_productivities');
    }
};
