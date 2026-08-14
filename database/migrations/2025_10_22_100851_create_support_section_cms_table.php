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
        Schema::create('support_section_cms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->string('visit_store_btn_text')->nullable();
            $table->boolean('visit_store_btn_show')->default(true);
            $table->string('get_a_virtual_demo_btn_text')->nullable();
            $table->boolean('get_a_virtual_demo')->default(true);
            $table->string('form_title')->nullable();
            $table->text('form_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_section_cms');
    }
};
