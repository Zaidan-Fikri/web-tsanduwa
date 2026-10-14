<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') | {{ setting('school_name', 'MTs. Darul Ulum Waru') }}@else{{ setting('school_name', 'MTs. Darul Ulum Waru') }} | Beriman – Berilmu – Berprestasi @endif</title>
    <meta name="description" content="@yield('meta_description', setting('footer_about', 'Website resmi MTs. Darul Ulum Waru, Sidoarjo.'))">

    @include('partials.head-assets')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=1">
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    @include('partials.topbar')
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.modals')
    @include('partials.footer')

    <script src="{{ asset('js/site.js') }}?v=1"></script>
    @stack('scripts')
</body>
</html>
