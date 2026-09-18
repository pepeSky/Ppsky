<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

</head>

<body class="font-sans antialiased">
    @extends('adminlte::page')

    @section('title', 'pepeSky - Administration')

    @section('content_header')
        @if (isset($content_header))
            <header class="bg-white shadow">
                <div class="py-6 px-4">
                    {{ $content_header }}
                </div>
            </header>
        @endif
    @stop

    @section('content')
        {{ $slot }}
    @stop


    @section('css')
        @if (isset($css))
            {{ $css }}
        @endif
    @stop

    @section('js')

        @if (isset($js))
            {{ $js }}
        @endif

    @stop

    @stack('modals')

    @livewireScripts

</body>

</html>
