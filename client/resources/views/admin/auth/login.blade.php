<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Admin sign in · SutharTech</title>@include('partials.font-links')@vite('resources/js/app.js')<link rel="stylesheet" href="{{ asset('css/theme.css') }}"><link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
<body class="admin-shell admin-auth-page">
    <main class="admin-auth-card">
        <a class="admin-brand" href="{{ route('home') }}" style="padding:0;border:0"><span class="admin-brand-mark" aria-hidden="true">S</span><span>SutharTech<small>Admin workspace</small></span></a>
        <h1>Sign in</h1><p>Use your admin account to manage the public website.</p>
        @if ($errors->any()) <div class="admin-notice admin-errors" role="alert">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('admin.login.store') }}">@csrf
            <div class="admin-field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus></div>
            <div class="admin-field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
            <label style="display:flex;align-items:center;gap:8px;margin:0 0 18px;font-size:13px;color:var(--text-secondary)"><input name="remember" type="checkbox" value="1"> Keep me signed in</label>
            <button class="admin-button admin-button-primary" style="width:100%" type="submit">Sign in</button>
        </form>
    </main>
<script src="{{ asset('js/admin.js') }}" defer></script></body></html>
