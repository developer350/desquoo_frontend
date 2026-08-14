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
        Schema::create('office_cms', function (Blueprint $table) {
            $table->id();
            $table->string('section_one_title')->nullable();
            $table->text('section_one_description')->nullable();
            $table->string('section_two_title')->nullable();
            $table->text('section_two_description')->nullable();
            $table->string('section_three_title')->nullable();
            $table->string('section_four_title')->nullable();
            $table->string('section_five_title')->nullable();
            $table->text('section_five_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_cms');
    }
};
