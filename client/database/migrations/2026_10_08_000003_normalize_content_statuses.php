<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['blogs', 'site_pages', 'services', 'projects', 'testimonials', 'certifications'] as $table) {
            DB::table($table)->where('status', 'published')->update(['status' => 'active']);
        }

        DB::table('projects')->whereIn('slug', [
            'premium-platform-llm-pipeline', 'mobile-commerce-ux', 'enterprise-telemetry-dashboard',
            'scalable-saas-workspace', 'retail-growth-dashboard', 'ai-support-workspace',
        ])->update(['status' => 'inactive']);
        DB::table('testimonials')->whereIn('client_name', [
            'Rohan Mehta', 'Maryam Sahem', 'Meryeme Hallab', 'Charlie Christie',
            'Jim Fernandez', 'Farah Y.', 'Elisa Mirzoeva',
        ])->update(['status' => 'inactive']);
    }

    public function down(): void
    {
        foreach (['blogs', 'site_pages', 'services', 'projects', 'testimonials', 'certifications'] as $table) {
            DB::table($table)->where('status', 'active')->update(['status' => 'published']);
        }
        DB::table('projects')->whereIn('slug', [
            'premium-platform-llm-pipeline', 'mobile-commerce-ux', 'enterprise-telemetry-dashboard',
            'scalable-saas-workspace', 'retail-growth-dashboard', 'ai-support-workspace',
        ])->where('status', 'inactive')->update(['status' => 'published']);
        DB::table('testimonials')->whereIn('client_name', [
            'Rohan Mehta', 'Maryam Sahem', 'Meryeme Hallab', 'Charlie Christie',
            'Jim Fernandez', 'Farah Y.', 'Elisa Mirzoeva',
        ])->where('status', 'inactive')->update(['status' => 'published']);
    }
};
