@extends('layouts.admin')
@section('title', 'API & Integrations')
@section('styles')
    <style>
        .save-btn-fixed {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .save-btn-fixed .btn-admin {
            padding: 12px 28px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }
    </style>
@endsection
@section('content')
    <form action="{{ route('admin.integrations.update') }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-shield-halved" style="margin-right:8px;color:var(--admin-primary);"></i> Public Form Bot Protection</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" name="suthartech_captcha_enabled" value="1" {{ ($settings['suthartech_captcha_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                        Enable automatic bot checks on public forms
                    </label>
                    <div class="form-hint">Applies to the SutharTech contact and newsletter forms. Checks run silently; visitors do not need to solve a puzzle.</div>
                </div>
            </div>
        </div>

        <div class="save-btn-fixed">
            <button type="submit" class="btn-admin btn-admin-primary">
                <i class="fas fa-save"></i> Save All Settings
            </button>
        </div>
        {{-- SMTP sender configuration --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-envelope-open-text" style="margin-right:8px;color:var(--admin-primary);"></i> SMTP Email Delivery</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;">One SMTP connection sends transactional mail from the configured contact, sales, and newsletter addresses. The sender addresses must be allowed by your email provider.</p>
                <div class="form-row">
                    <div class="form-group"><label>SMTP host</label><input class="form-control" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" placeholder="smtp.example.com"></div>
                    <div class="form-group"><label>SMTP port</label><input class="form-control" type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}" min="1" max="65535"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>SMTP username</label><input class="form-control" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" autocomplete="username"></div>
                    <div class="form-group"><label>SMTP password</label><input class="form-control" type="password" name="mail_password" value="" autocomplete="new-password" placeholder="{{ !empty($settings['mail_password']) ? 'Saved — leave blank to keep current password' : 'SMTP password or app password' }}"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Encryption</label><select class="form-control" name="mail_encryption"><option value="tls" @selected(old('mail_encryption', $settings['mail_encryption'] ?? 'tls') === 'tls')>TLS</option><option value="ssl" @selected(old('mail_encryption', $settings['mail_encryption'] ?? '') === 'ssl')>SSL</option><option value="starttls" @selected(old('mail_encryption', $settings['mail_encryption'] ?? '') === 'starttls')>STARTTLS</option></select></div>
                    <div class="form-group"><label>Default sender name</label><input class="form-control" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? 'SutharTech') }}"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Contact sender</label><input type="email" class="form-control" name="mail_from_contact_address" value="{{ old('mail_from_contact_address', $settings['mail_from_contact_address'] ?? 'contact@suthartech.com') }}"></div>
                    <div class="form-group"><label>Sales inquiry sender</label><input type="email" class="form-control" name="mail_from_sales_address" value="{{ old('mail_from_sales_address', $settings['mail_from_sales_address'] ?? 'sales@suthartech.com') }}"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Newsletter sender</label><input type="email" class="form-control" name="mail_from_newsletter_address" value="{{ old('mail_from_newsletter_address', $settings['mail_from_newsletter_address'] ?? 'noreply@suthartech.com') }}"></div>
                    <div class="form-group"><label>General fallback sender</label><input type="email" class="form-control" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="hello@suthartech.com"></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-bell" style="margin-right:8px;color:var(--admin-primary);"></i> Form Admin Notifications</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;">Contact inquiries and confirmed newsletter signups notify these admin inboxes and the WhatsApp numbers above.</p>
                <div class="form-group"><label>Project inquiry admin emails</label><textarea class="form-control" name="form_admin_emails" rows="3" placeholder="owner@suthartech.com, sales@suthartech.com">{{ old('form_admin_emails', $settings['form_admin_emails'] ?? '') }}</textarea><div class="form-hint">Multiple email addresses: comma or new line separated.</div></div>
                <div class="form-group"><label>Newsletter admin emails</label><textarea class="form-control" name="newsletter_admin_emails" rows="2" placeholder="marketing@suthartech.com">{{ old('newsletter_admin_emails', $settings['newsletter_admin_emails'] ?? '') }}</textarea><div class="form-hint">Leave empty to use project inquiry admin emails.</div></div>
            </div>
        </div>

        <div class="panel">

            <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-credit-card" style="margin-right:8px;color:var(--admin-primary);"></i> Razorpay API
                    Keys
                </h2>
                <button type="button" id="testRazorpayBtn" onclick="testRazorpayConnection()"
                    class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plug"></i> Test Connection
                </button>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:16px;">
                    These keys are used by the site's configured payment integrations.
                    @if (empty($settings['razorpay_key_id']) || empty($settings['razorpay_key_secret']))
                        <br><strong class="text-danger">⚠️ Warning:</strong> Razorpay keys are not configured. Payments will
                        fail until you add your API keys.
                    @endif
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label>Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" class="form-control"
                            value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_live_...">
                    </div>
                    <div class="form-group">
                        <label>Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" class="form-control"
                            value="" autocomplete="new-password" placeholder="{{ !empty($settings['razorpay_key_secret']) ? 'Saved — leave blank to keep current key' : 'Paste secret key' }}">
                        <div class="form-hint">Keep this secret — used to verify payment signatures server-side.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- WhatsApp notifications --}}
        <div class="panel">
            <div class="panel-header">
                <h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i> WhatsApp Notifications</h2>
            </div>
            <div class="panel-body">
                <p style="color:var(--admin-gray);font-size:0.88rem;margin-bottom:20px;">
                    Messages use the SutharTech WA API. Keep the API key on the server; it is never sent to the browser.
                    Endpoint: <code>https://wa-api.suthartech.com/api/messages/send</code>
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="wa_enabled" value="1"
                                {{ ($settings['wa_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            Enable WhatsApp notifications
                        </label>
                        <div class="form-hint">Enable to send website contact and confirmed newsletter notifications.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Wa Api Key</label>
                        <input type="password" name="wa_api_key" class="form-control"
                            value="" autocomplete="new-password" placeholder="{{ !empty($settings['wa_api_key']) ? 'Saved — leave blank to keep current key' : 'Paste API key' }}">
                    </div>
                    <div class="form-group">
                        <label>Wa Session Key</label>
                        <input type="password" name="wa_session_key" class="form-control"
                            value="" autocomplete="new-password" placeholder="{{ !empty($settings['wa_session_key']) ? 'Saved — leave blank to keep current session' : 'Paste session ID' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="admin_whatsapp_numbers">Admin WhatsApp numbers</label>
                    <textarea id="admin_whatsapp_numbers" name="admin_whatsapp_numbers" class="form-control" rows="3" placeholder="+919876543210, +919812345678">{{ old('admin_whatsapp_numbers', $settings['admin_whatsapp_numbers'] ?? '') }}</textarea>
                    <div class="form-hint">Add multiple admin numbers separated by commas or new lines, including country code.</div>
                </div>
            </div>
            <div class="panel" style="margin:20px;">
                <div class="panel-header">
                        <h2><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i>Test WhatsApp API</h2>
                </div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" id="wa_phone" class="form-control" placeholder="+91XXXXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <input type="text" id="wa_message" class="form-control"
                                placeholder="Type your test message...">
                        </div>
                        <div class="form-group">
                            <button type="button"
                                class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2"
                                onclick="sendWA()">
                                <i class="fas fa-paper-plane"></i>
                                Send
                            </button>
                        </div>
                        <div id="wa_result" style="margin-top:10px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-key" style="margin-right:8px;color:var(--admin-primary);"></i> Tiny Editor</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>TinyMCE API Key</label>
                    <input type="text" name="tinymce_api_key" class="form-control"
                        value="{{ old('tinymce_api_key', $settings['tinymce_api_key'] ?? '') }}"
                        placeholder="Paste your TinyMCE API key">
                    <div class="form-hint">This key will be used automatically in blog/page editor screens.</div>
                </div>

                <div class="form-group">
                    <label>Tiny Drive API Key (Optional)</label>
                    <input type="text" name="tiny_drive_api_key" class="form-control"
                        value="{{ old('tiny_drive_api_key', $settings['tiny_drive_api_key'] ?? '') }}"
                        placeholder="Optional Tiny Drive key">
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-puzzle-piece" style="margin-right:8px;color:var(--admin-primary);"></i> Other Extra
                    API/Config</h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Extra API Settings</label>
                    <textarea name="extra_api_settings" class="form-control" rows="8"
                        placeholder="You can store any additional API keys, notes, JSON snippets, or integration config here.">{{ old('extra_api_settings', $settings['extra_api_settings'] ?? '') }}</textarea>
                    <div class="form-hint">Tip: For multiple entries, keep one key/value per line.</div>
                </div>
            </div>
        </div>

        {{-- 
        <button type="submit" class="btn-admin btn-admin-primary" style="padding:12px 36px;">
            <i class="fas fa-save"></i> Save All Settings
        </button> --}}
    </form>

    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-paper-plane" style="margin-right:8px;color:var(--admin-primary);"></i> Test SMTP Delivery</h2></div>
        <div class="panel-body">
            <form action="{{ route('admin.settings.test-mail') }}" method="POST" class="form-row">
                @csrf
                <div class="form-group"><label>Send a test message to</label><input type="email" name="test_email" class="form-control" required placeholder="you@example.com"></div>
                <div class="form-group" style="align-self:end"><button class="btn-admin btn-admin-primary" type="submit">Send test email</button></div>
            </form>
            @if(session('test_mail_success'))<p class="text-success mb-0">{{ session('test_mail_success') }}</p>@endif
            @if(session('test_mail_error'))<p class="text-danger mb-0">{{ session('test_mail_error') }}</p>@endif
        </div>
    </div>




    <script>
        async function testRazorpayConnection() {
            const btn = document.getElementById('testRazorpayBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

            try {
                const response = await fetch('{{ route('admin.settings.test-razorpay') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ ' + data.message);
                    btn.innerHTML = '<i class="fas fa-check"></i> Connected';
                    btn.className = 'btn btn-sm btn-success ml-2';
                } else {
                    alert('❌ ' + data.message);
                    btn.innerHTML = '<i class="fas fa-times"></i> Failed';
                    btn.className = 'btn btn-sm btn-danger ml-2';
                }
            } catch (error) {
                alert('❌ Connection error: ' + error.message);
                btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                btn.className = 'btn btn-sm btn-warning ml-2';
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                btn.className = 'btn btn-sm btn-outline-success ml-2';
            }, 3000);
        }
    </script>

    <script>
        async function sendWA() {
            const phone = document.getElementById('wa_phone').value.trim();
            const message = document.getElementById('wa_message').value.trim();
            const resultBox = document.getElementById('wa_result');
            if (!phone) {
                resultBox.textContent = 'Enter a phone number.';
                return;
            }
            resultBox.textContent = 'Sending…';
            try {
                const response = await fetch(@json(route('admin.integrations.test-whatsapp')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ phone, message }),
                });
                const data = await response.json();
                resultBox.textContent = data.message || (response.ok ? 'Message sent.' : 'Message could not be sent.');
            } catch (error) {
                resultBox.textContent = 'Could not reach the WhatsApp test endpoint.';
            }
        }
    </script>
@endsection
