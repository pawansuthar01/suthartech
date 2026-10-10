<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FormBotProtection
{
    public static function enabled(): bool
    {
        return Cache::remember('form-bot-protection-enabled', now()->addSeconds(30), function (): bool {
            try {
                return (bool) Http::acceptJson()
                    ->withToken((string) config('services.internal_content_token'))
                    ->timeout(2)
                    ->get(config('services.form_protection_settings_url'))
                    ->throw()
                    ->json('bot_protection_enabled', true);
            } catch (\Throwable) {
                return true;
            }
        });
    }

    public static function issueToken(): string
    {
        $issuedAt = (string) now()->timestamp;

        return $issuedAt.'.'.hash_hmac('sha256', $issuedAt, (string) config('services.internal_content_token'));
    }

    public static function passes(Request $request, bool $allowFreshToken = false): bool
    {
        if (! self::enabled()) {
            return true;
        }

        if (trim((string) $request->input('website', '')) !== '') {
            return false;
        }

        $parts = explode('.', (string) $request->input('bot_token', ''), 2);
        if (count($parts) !== 2 || ! ctype_digit($parts[0])) {
            return false;
        }

        $age = now()->timestamp - (int) $parts[0];

        return $age >= ($allowFreshToken ? 0 : 2)
            && $age <= 7200
            && hash_equals(hash_hmac('sha256', $parts[0], (string) config('services.internal_content_token')), $parts[1]);
    }
}
