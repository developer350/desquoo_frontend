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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('variant_name')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('features')->nullable();
            $table->longText('dimensions')->nullable();
            $table->longText('warranty_shipping')->nullable();
            $table->longText('materials_certifications')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 19, 2)->default(0);
            $table->decimal('discount_amount', 19, 2)->nullable();
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->decimal('offer_price', 19, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('product_variants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
};
