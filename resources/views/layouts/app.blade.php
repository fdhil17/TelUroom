<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TelUroom') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-surface-base">
        
        {{-- App Shell: Sidebar --}}
        @include('layouts.sidebar')

        {{-- App Shell: Main Wrapper --}}
        <div class="teluroom-wrapper">
            
            {{-- App Shell: Topbar --}}
            @include('layouts.topbar')

            {{-- App Shell: Main Canvas --}}
            <main class="teluroom-main">
                
                {{-- Global Page Title & Toolbar (Optional slots) --}}
                @if (isset($header) || isset($toolbar))
                    <div class="mb-4">
                        @isset($header)
                            <h1 class="teluroom-page-title mb-2">{{ $header }}</h1>
                        @endisset
                        
                        @isset($toolbar)
                            <div class="teluroom-toolbar mt-3">
                                {{ $toolbar }}
                            </div>
                        @endisset
                    </div>
                @endif

                {{-- Actual Content --}}
                {{ $slot }}
                
            </main>
        </div>

        <!-- Global Toast Notifications -->
        <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1100;">
            @if (session('success'))
                <div class="toast teluroom-toast show border-0 rounded-3 shadow-sm bg-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="text-success bg-success bg-opacity-10 rounded-circle p-2 d-flex">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="toast-body p-0 flex-grow-1 font-sans text-dark fw-medium" style="font-size: 0.875rem;">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close ms-auto flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="toast teluroom-toast show border-0 rounded-3 shadow-sm bg-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4500">
                    <div class="d-flex align-items-center gap-3 p-3">
                        <div class="text-danger bg-danger bg-opacity-10 rounded-circle p-2 d-flex">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </div>
                        <div class="toast-body p-0 flex-grow-1 font-sans text-dark fw-medium" style="font-size: 0.875rem;">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close ms-auto flex-shrink-0" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastElList = [].slice.call(document.querySelectorAll('.toast.show'));
                toastElList.map(function (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 4500 });
                    toast.show();
                });
            });
        </script>
    </body>
</html>