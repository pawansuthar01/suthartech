<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->whereIn('key', [
            'recaptcha_enabled',
            'recaptcha_site_key',
            'recaptcha_secret_key',
        ])->delete();
    }

    public function down(): void
    {
        // Removed CAPTCHA credentials cannot be restored safely.
    }
};
