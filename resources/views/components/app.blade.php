<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EOS') }} - Organize o amanhã</title>

    <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

    @include('layouts.navigation')

    <main class="main-content">
        <div class="container">
            @if(isset($header))
                <div class="page-header" data-reveal>
                    <h1>{{ $header }}</h1>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <!-- FullCalendar CDN -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
</body>
</html>