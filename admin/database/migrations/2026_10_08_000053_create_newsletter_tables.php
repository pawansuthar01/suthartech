<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('confirmation_token', 64)->nullable()->index();
            $table->string('unsubscribe_token', 64)->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('newsletter_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->string('status', 20)->default('sending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
        Schema::create('certification_announcements', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('content_id')->unique();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->string('status', 20)->default('sending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_announcements');
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
