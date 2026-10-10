<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Pool;


class WaaService
{
    private const API_URL = 'https://wa-api.suthartech.com/api/messages/send';

    private string $apiKey;
    private string $session;

    public function __construct()
    {
        $this->apiKey  = Setting::get('wa_api_key', '');
        $this->session = Setting::get('wa_session_key', '');
    }
    /**
     * Send OTP
     */
    public function sendOtp(string $phone, string $customerName, string $otp, string $context = 'booking'): bool
    {
        $message = "Hello {$customerName}, Your OTP for {$context} is *{$otp}*";

        return $this->sendMessage($phone, $message, $customerName);
    }

    /**
     * Booking Notification
     */
    public function sendBookingNotification(string $phone, string $customerName, string $message): bool
    {
        $finalMessage = "Hello {$customerName},\n{$message}";

        return $this->sendMessage($phone, $finalMessage, $customerName);
    }

    public function isEnabled(): bool
    {
        return Setting::get('wa_enabled', '0') === '1'
            && !empty($this->apiKey)
            && !empty($this->session);
    }

    public function sendToPhone(string $phone, string $message, string $name = 'SutharTech'): bool
    {
        return $this->sendMessage($phone, $message, $name);
    }

    public function notifyAdmins(string $message, string $name = 'SutharTech'): int
    {
        if (! $this->isEnabled()) {
            return 0;
        }

        $numbers = preg_split('/[,;\r\n]+/', (string) Setting::get('admin_whatsapp_numbers', '')) ?: [];
        $numbers = array_values(array_unique(array_filter(array_map(fn ($number) => $this->formatPhone(trim($number)), $numbers))));
        if (! $numbers) {
            return 0;
        }

        try {
            $responses = Http::pool(fn (Pool $pool) => array_map(
                fn (string $number) => $pool->timeout(12)->withHeaders(['x-api-key' => $this->apiKey])->post(self::API_URL, [
                    'session' => $this->session,
                    'to' => $number,
                    'message' => $message,
                    'contactName' => $name,
                ]),
                $numbers
            ));

            return count(array_filter($responses, fn ($response) => $response->successful()));
        } catch (\Throwable $e) {
            Log::error('WhatsApp admin notification batch failed.', ['error' => $e->getMessage()]);
            return 0;
        }
    }



    /**
     * Booking Confirmed
     */
    public function sendBookingConfirmed(string $phone, string $customerName, string $petName, int $bookingId): bool
    {
        $message = "Your adoption booking for *{$petName}* (Booking #{$bookingId}) has been *confirmed*! 🎉";

        return $this->sendBookingNotification($phone, $customerName, $message);
    }

    /**
     * Status Update
     */
    public function sendStatusUpdate(string $phone, string $customerName, string $petName, string $status, string $bookingType = 'booking'): bool
    {
        $statusMsg = match ($status) {
            'confirmed'  => "confirmed ✅",
            'completed'  => "completed 🎉",
            'cancelled'  => "cancelled ❌",
            default      => "updated to {$status}",
        };

        $message = "Your {$bookingType} for *{$petName}* has been {$statusMsg}";

        return $this->sendBookingNotification($phone, $customerName, $message);
    }
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);
        $phone = ltrim($phone, '0');
        if (strlen($phone) === 10) {
            return '91' . $phone;
        }
        return strlen($phone) >= 10 && strlen($phone) <= 15 ? $phone : '';
    }
    /**
     * Core method (API call)
     */
    private function sendMessage(string $phone, string $message, string $name): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }
        $phone = $this->formatPhone($phone);
        if ($phone === '') {
            return false;
        }

        try {
            $response = Http::timeout(12)->acceptJson()->withHeaders([
                'x-api-key' => $this->apiKey,
            ])->post(self::API_URL, [
                "session"     => $this->session,
                "to"          => $phone,
                "message"     => $message,
                "contactName" => $name,
            ]);

            if ($response->successful()) {
                return true;
            }
            Log::warning('WhatsApp provider rejected a notification.', ['status' => $response->status()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp notification failed.', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Generate OTP
     */
    public static function generateOtp(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}
