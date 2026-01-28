<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'نظام ERP') }}</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Tajawal', sans-serif; }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 45px rgba(0,0,0,.35);
        }

        .input-field:focus {
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167,139,250,.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Logo sizing */
        .login-logo {
            height: 40px;
            width: auto;
        }

        @media (max-width: 768px) {
            .login-logo {
                height: 32px;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col gradient-bg">

<!-- ================= Header ================= -->
<header class="glass-effect border-b border-white/20">
    <div class="max-w-7xl mx-auto h-16 px-6 flex items-center justify-between">

        <!-- Left: Logo -->
        <a
            href="https://clicksolutions-ar.com"
            target="_blank"
            class="flex items-center gap-3"
        >
            <img
                src="{{ asset('assets/img/click.png') }}"
                alt="Click Solutions Logo"
                class="login-logo"
            >

            <div class="leading-tight">
                <h1 class="text-base font-extrabold text-white">
                    Zoom for Kitchens
                </h1>
                <p class="text-[11px] text-white/70">
                    ERP System for Kitchen Factories
                </p>
            </div>
        </a>

        <!-- Right: Click Solutions -->
        <a
            href="https://clicksolutions-ar.com"
            target="_blank"
            class="text-base font-extrabold text-white
                   tracking-wide hover:opacity-90 transition"
        >
            Click Solutions
        </a>

    </div>
</header>

<!-- ================= Main ================= -->
<main class="flex-1 flex items-center justify-center px-4">

    <!-- Login Card -->
    <div
        class="glass-effect card-hover rounded-2xl shadow-2xl"
        style="width:100%; max-width:380px; padding:28px;"
    >
        {{ $slot }}
    </div>

</main>

<!-- ================= Footer ================= -->
<footer class="glass-effect border-t border-white/20 text-center py-4 mt-auto">
    <p class="text-white/70 text-sm">
        © Made with ❤️ by
        <a
            href="https://clicksolutions-ar.com"
            target="_blank"
            class="font-semibold underline hover:text-white"
        >
            Click Solutions
        </a>
    </p>
</footer>

</body>
</html>
