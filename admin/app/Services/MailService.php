<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    public static function deliver(Mailable $mailable, array|string $recipients, string $sender = 'default'): bool
    {
        $recipients = is_array($recipients) ? $recipients : [$recipients];
        $recipients = array_values(array_filter($recipients, fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)));
        if (! $recipients) {
            return false;
        }
        try {
            self::configureMailer();
            $fromAddress = Setting::get("mail_from_{$sender}_address") ?: Setting::get('mail_from_address');
            $fromName = Setting::get("mail_from_{$sender}_name") ?: Setting::get('mail_from_name', Setting::get('site_name', 'SutharTech'));
            if ($fromAddress) {
                $mailable->from($fromAddress, $fromName);
            }
            Mail::to($recipients)->send($mailable);
            return true;
        } catch (\Throwable $e) {
            Log::error('MailService: delivery failed.', ['recipient_count' => count($recipients), 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send a mailable to the configured notification email address(es).
     * Reads from Admin → My Account → Form Notification Emails (notification_email setting).
     * Falls back to mail_from_address or site_email if not set.
     */
    public static function sendToAdmin(Mailable $mailable): bool
    {
        $recipients = self::configuredRecipients('form_admin_emails', 'notification_email', 'site_email');
        if (!$recipients) {
            Log::warning('MailService: No notification email configured. Set it in Admin → My Account → Form Notification Emails.');
            return false;
        }
        return self::deliver($mailable, $recipients, 'sales');
    }

    public static function configuredRecipients(string ...$settingKeys): array
    {
        foreach ($settingKeys as $key) {
            $raw = (string) Setting::get($key, '');
            $recipients = array_values(array_filter(
                preg_split('/[,;\s]+/', $raw) ?: [],
                fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL)
            ));
            if ($recipients) {
                return array_unique($recipients);
            }
        }

        return [];
    }

    /**
     * Dynamically configure the mailer from DB settings so admin SMTP
     * settings (saved via Admin > My Account) are used at runtime.
     */
    private static function configureMailer(): void
    {
        $host       = Setting::get('mail_host');
        $port       = Setting::get('mail_port');
        $username   = Setting::get('mail_username');
        $password   = Setting::get('mail_password');
        $encryption = Setting::get('mail_encryption');
        $fromAddr   = Setting::get('mail_from_address');
        $fromName   = Setting::get('mail_from_name', Setting::get('site_name', 'PawZone'));

        if (!$host) {
            // Fall back to .env / log driver — no DB config yet
            return;
        }

        config([
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => (int) ($port ?: 587),
            'mail.mailers.smtp.username'   => $username,
            'mail.mailers.smtp.password'   => $password,
            'mail.mailers.smtp.encryption' => $encryption ?: 'tls',
            'mail.mailers.smtp.timeout'    => 30,
            'mail.default'                 => 'smtp',
            'mail.from.address'            => $fromAddr,
            'mail.from.name'               => $fromName,
        ]);
    }
}
