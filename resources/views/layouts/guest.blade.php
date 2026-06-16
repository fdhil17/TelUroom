<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TelUroom') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="teluroom-auth-wrap">

        {{-- Brand --}}
        <div class="teluroom-auth-brand">
            <a href="/" class="text-decoration-none d-inline-flex align-items-center justify-content-center">
                <img src="{{ asset('images/logo.png') }}" alt="TelUroom" height="36">
            </a>
            <p class="mt-2 mb-0 text-secondary" style="font-size: 0.8125rem;">{{ __('app.tagline_login') }}</p>
        </div>

        {{-- Main Content Slot --}}
        {{ $slot }}

        {{-- Footer --}}
        <p class="mt-5 mb-0 text-center text-tertiary" style="font-size: 0.75rem; color: #9CA3AF;">
            &copy; {{ date('Y') }} TelUroom &mdash; Telkom University
        </p>

    </div>

    {{-- Global Password Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.password-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Temukan input dalam grup yang sama
                    const group = this.closest('.input-group');
                    const input = group.querySelector('input');
                    const icon = this.querySelector('svg');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        // Icon eye-off
                        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                    } else {
                        input.type = 'password';
                        // Icon eye
                        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                    }
                });
            });
        });
    </script>
</body>
</html>