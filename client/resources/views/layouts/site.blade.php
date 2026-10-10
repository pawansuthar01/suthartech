<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FAFAF9">

    <title>{{ $seo['title'] }}</title>
    @include('partials.seo', ['seo' => $seo])

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stack('head')
</head>
<body class="bg-background text-primary antialiased overflow-x-hidden relative">
    <x-site-background />
    <x-site-navbar />

    <main class="site-page-main @yield('main-pt', 'pt-20')">
        @yield('content')
    </main>

    <x-site-footer />

    @stack('scripts')
</body>
</html>
