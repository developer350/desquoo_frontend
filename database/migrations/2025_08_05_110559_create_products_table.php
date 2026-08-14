<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->text('short_description')->nullable();
            $table->longText('features')->nullable();
            $table->longText('dimensions')->nullable();
            $table->longText('warranty_shipping')->nullable();
            $table->longText('materials_certifications')->nullable();
            $table->enum('type', ['single', 'variable'])->default('single');
            $table->longText('related_products')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_favourite')->default(false);
            $table->boolean('status')->default(true);
            $table->text('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('other_meta_tags')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
};
