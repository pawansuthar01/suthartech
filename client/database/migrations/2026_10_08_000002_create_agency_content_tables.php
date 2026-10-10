<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body');
            $table->string('image_path')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_description', 700)->nullable();
            $table->longText('body')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('description', 700);
            $table->longText('body')->nullable();
            $table->json('deliverables')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('summary', 500);
            $table->longText('body')->nullable();
            $table->string('image_path')->nullable();
            $table->json('technologies')->nullable();
            $table->string('project_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('draft')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_role')->nullable();
            $table->string('company')->nullable();
            $table->text('quote');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('issuer');
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('SutharTech');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('location')->nullable();
            $table->json('social_links')->nullable();
            $table->string('default_meta_title')->nullable();
            $table->string('default_meta_description', 320)->nullable();
            $table->string('default_og_image')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 190);
            $table->string('phone', 40)->nullable();
            $table->string('company', 160)->nullable();
            $table->string('project_type', 100);
            $table->string('budget', 100)->nullable();
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('services');
        Schema::dropIfExists('site_pages');
    }
};
