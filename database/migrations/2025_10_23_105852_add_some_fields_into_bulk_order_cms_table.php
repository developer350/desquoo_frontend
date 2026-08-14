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
        Schema::table('bulk_order_cms', function (Blueprint $table) {
            $table->string('banner_alt_text')->nullable();
            $table->string('banner_super_title')->nullable();
            $table->string('banner_title')->nullable();
            $table->text('want_to_chat_link')->nullable();
            $table->string('want_to_chat_text')->nullable();
            $table->boolean('show_want_to_chat')->default(true);
            $table->string('want_to_talk_number')->nullable();
            $table->boolean('show_want_to_talk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulk_order_cms', function (Blueprint $table) {
            $table->dropColumn([
                'banner_alt_text',
                'banner_super_title',
                'banner_title',
                'want_to_chat_link',
                'want_to_chat_text',
                'show_want_to_chat',
                'want_to_talk_number',
                'show_want_to_talk',
            ]);
        });
    }
};
