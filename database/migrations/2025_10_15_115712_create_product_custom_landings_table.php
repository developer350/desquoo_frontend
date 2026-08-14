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
        Schema::create('product_custom_landings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->foreignId('product_id')->constrained('products');
            $table->enum('banner_type', ['image', 'video'])->default('image');
            $table->string('banner_image_alt_text')->nullable();
            $table->string('banner_super_title')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_btn_text')->nullable();
            $table->boolean('banner_btn_show')->default(true);
            $table->string('banner_bulk_order_btn_text')->nullable();
            $table->text('overview_description')->nullable();
            $table->text('overview_quote_text')->nullable();
            $table->text('overview_quote_description')->nullable();
            $table->string('productivity_super_title')->nullable();
            $table->string('productivity_title')->nullable();
            $table->string('productivity_btn_text')->nullable();
            $table->text('productivity_btn_url')->nullable();
            $table->string('mindful_engineering_title')->nullable();
            $table->string('find_the_right_product_title')->nullable();
            $table->boolean('show_height_calculator')->default(true);
            $table->string('height_calculator_title')->nullable();
            $table->text('height_calculator_description')->nullable();
            $table->boolean('show_assembly_section')->default(true);
            $table->string('assembly_super_title')->nullable();
            $table->string('assembly_title')->nullable();
            $table->string('assembly_support_text')->nullable();
            $table->string('assembly_help_text')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_custom_landings');
    }
};
