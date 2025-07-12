<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inkwell</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon-16x16.png') }}" type="image/png" sizes="16x16">
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Left: Logo -->
                <div class="flex items-center">
                    <a href="/">
                        <img src="{{ asset('inkwell_logo_transparent.png') }}" alt="Inkwell Logo" class="h-10 w-10" />
                    </a>
                </div>
                <!-- Right: Navigation Links -->
                <div class="flex items-center space-x-8">
                    <x-nav-link href="/">Home</x-nav-link>
                    <x-nav-link href="about">About</x-nav-link>
                </div>
            </div>
        </div>
    </nav>
    
    {{ $slot }}
</body>
<script>
    window.generateLetterUrl = "{{ route('generate.letter') }}";
</script>
</html>