<?php

namespace App\Services;

use Illuminate\Http\Request;

class FormBotProtection
{
    public static function passes(Request $request): bool
    {
        if (trim((string) $request->input('website', '')) !== '') {
            return false;
        }

        $parts = explode('.', (string) $request->input('bot_token', ''), 2);
        if (count($parts) !== 2 || ! ctype_digit($parts[0])) {
            return false;
        }

        $age = now()->timestamp - (int) $parts[0];

        return $age >= 2
            && $age <= 7200
            && hash_equals(hash_hmac('sha256', $parts[0], (string) config('services.suthartech_content_token')), $parts[1]);
    }
}
