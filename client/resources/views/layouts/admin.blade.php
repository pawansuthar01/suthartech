<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · SutharTech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.font-links')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-shell">
    <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin navigation">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="admin-brand-mark" aria-hidden="true">S</span><span>SutharTech<small>Admin workspace</small></span></a>
        <nav class="admin-nav">
            <p class="admin-nav-label">Main</p>
            @foreach ([['admin.dashboard', 'dashboard', 'space_dashboard', 'Dashboard'], ['admin.pages.index', 'admin.pages.*', 'article', 'Pages'], ['admin.services.index', 'admin.services.*', 'design_services', 'Services']] as [$url, $active, $icon, $label])
                <a class="admin-nav-link" href="{{ route($url) }}" @if(request()->routeIs($active)) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">{{ $icon }}</span>{{ $label }}</a>
            @endforeach
            <p class="admin-nav-label">Content</p>
            @foreach ([['admin.blogs.index', 'admin.blogs.*', 'edit_note', 'Blog'], ['admin.projects.index', 'admin.projects.*', 'work', 'Projects'], ['admin.feedback.index', 'admin.feedback.*', 'reviews', 'Feedback'], ['admin.certifications.index', 'admin.certifications.*', 'workspace_premium', 'Certifications']] as [$url, $active, $icon, $label])
                <a class="admin-nav-link" href="{{ route($url) }}" @if(request()->routeIs($active)) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">{{ $icon }}</span>{{ $label }}</a>
            @endforeach
            <p class="admin-nav-label">Website</p>
            <a class="admin-nav-link" href="{{ route('admin.inquiries.index') }}" @if(request()->routeIs('admin.inquiries.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">mail</span>Inquiries</a>
            <a class="admin-nav-link" href="{{ route('admin.settings.edit') }}" @if(request()->routeIs('admin.settings.*')) aria-current="page" @endif><span class="material-symbols-outlined" aria-hidden="true">settings</span>SEO & settings</a>
        </nav>
        <div class="admin-sidebar-footer">Signed in as {{ auth()->user()->name }}</div>
    </aside>
    <button class="admin-menu-overlay" type="button" data-admin-menu-overlay aria-label="Close navigation"></button>
    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px;min-width:0"><button class="admin-menu-toggle" type="button" data-admin-menu-toggle aria-label="Open navigation" aria-controls="admin-sidebar" aria-expanded="false"><span class="material-symbols-outlined" aria-hidden="true">menu</span></button><div><h1>@yield('title', 'Dashboard')</h1><p>SutharTech website management</p></div></div>
            <div class="admin-user"><span class="admin-user-name">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-button" type="submit">Sign out</button></form></div>
        </header>
        <main class="admin-content">
            @if (session('success')) <div class="admin-notice" role="status">{{ session('success') }}</div> @endif
            @if ($errors->any()) <div class="admin-notice admin-errors" role="alert"><ul style="margin:0;padding-left:18px">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/admin.js') }}" defer></script>
</body>
</html>
