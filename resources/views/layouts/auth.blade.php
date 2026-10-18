<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kinetic Apex') — Member & Staff Login</title>
    <link rel="stylesheet" href="{{ asset('css/gym.css') }}">
    @stack('head')
</head>
<body style="background: var(--color-bg);">

    {{-- Toast from session --}}
    @if(session('success'))
        <div class="toast-container-dark">
            <div class="toast-dark success" id="session-toast">
                <span class="material-symbols-outlined" style="color:var(--color-primary-container);flex-shrink:0;">check_circle</span>
                <span style="font-size:13px;">{{ session('success') }}</span>
                <button onclick="document.getElementById('session-toast').remove()" style="margin-left:auto;background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:18px;">×</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast-container-dark">
            <div class="toast-dark error" id="session-toast-err">
                <span class="material-symbols-outlined" style="color:var(--color-error);flex-shrink:0;">error</span>
                <span style="font-size:13px;">{{ session('error') }}</span>
                <button onclick="document.getElementById('session-toast-err').remove()" style="margin-left:auto;background:none;border:none;color:var(--color-outline);cursor:pointer;font-size:18px;">×</button>
            </div>
        </div>
    @endif

    @yield('content')

    <script>
        // Auto-dismiss toasts after 4 seconds
        setTimeout(() => {
            document.querySelectorAll('.toast-dark').forEach(t => t.remove());
        }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
