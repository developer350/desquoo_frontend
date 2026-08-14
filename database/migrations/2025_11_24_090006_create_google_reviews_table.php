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
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('review_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->mediumText('review')->nullable();
            $table->integer('rating')->nullable();
            $table->date('published_on')->nullable();
            $table->string('avatar_alt_text')->nullable();
            $table->boolean('show_in_bulk_order')->default(false);
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
        Schema::dropIfExists('google_reviews');
    }
};
