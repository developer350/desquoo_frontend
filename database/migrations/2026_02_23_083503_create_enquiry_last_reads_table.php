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
        Schema::create('enquiry_last_reads', function (Blueprint $table) {
            $table->id();
            $table->timestamp('got_a_question_at')->nullable();
            $table->timestamp('visit_at')->nullable();
            $table->timestamp('office_at')->nullable();
            $table->timestamp('bulk_order_at')->nullable();
            $table->timestamp('newsletter_at')->nullable();
            $table->timestamp('blog_comment_at')->nullable();
            $table->foreignId('admin_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_last_reads');
    }
};
