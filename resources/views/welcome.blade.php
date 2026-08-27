<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @php
        $settings = \App\Models\Setting::getSettings();
        $appName = $settings->app_name ?? 'BudgetIn';
        $appLogo = $settings->app_logo ?? null;
        $pageTitle = $appName . ' — Kelola Saldo Dompet, Catat Arus Kas & Atur Target Anggaran';
        $pageDescription = 'Aplikasi pengelola keuangan pribadi & bisnis mandiri. Lacak saldo multi-dompet (Bank, Tunai, E-Wallet), kontrol limit pengeluaran bulanan, jadwalkan transaksi rutin, dan evaluasi arus kas visual secara real-time.';
        $pageUrl = url('/');
        $ogImage = asset('images/logo-icon.svg');
    @endphp

    <!-- Primary SEO Meta Tags -->
    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="budgetin, aplikasi keuangan, aplikasi budgeting, kelola dompet, catat pengeluaran harian, software manajemen kas, multi wallet, finansial pribadi, atur target anggaran, recurring transactions, ekspor laporan excel, pembukuan mandiri">
    <meta name="author" content="Intech Studio">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="language" content="Indonesian">
    <meta name="geo.region" content="ID">
    <meta name="geo.country" content="ID">
    <meta name="theme-color" content="#10B981">
    <meta name="format-detection" content="telephone=no">
    <link rel="canonical" href="{{ $pageUrl }}">

    <!-- Open Graph / Facebook / WhatsApp Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="BudgetIn — Aplikasi Pengelola Arus Kas & Multi-Dompet Modern">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $pageUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="BudgetIn — Aplikasi Pengelola Arus Kas & Multi-Dompet Modern">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.svg') }}">
    @if ($settings && $settings->app_favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $settings->app_favicon) }}">
    @endif

    <!-- Structured Data (JSON-LD) for Search Engines (SoftwareApplication + FAQPage + Breadcrumbs) -->
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'WebApplication',
          '@id' => $pageUrl . '#webapp',
          'name' => $appName,
          'url' => $pageUrl,
          'description' => $pageDescription,
          'applicationCategory' => 'FinanceApplication',
          'operatingSystem' => 'All (Web-based)',
          'browserRequirements' => 'Requires modern web browser',
          'softwareVersion' => '1.0.0',
          'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.9',
            'reviewCount' => '150',
            'bestRating' => '5',
            'worstRating' => '1'
          ],
          'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'IDR',
            'availability' => 'https://schema.org/InStock'
          ],
          'featureList' => [
            'Manajemen Multi-Dompet & Rekening Bank',
            'Target Limit Anggaran per Kategori',
            'Transaksi Rutin Terjadwal Otomatis',
            'Grafik Visual Tren 6 Bulan & Komposisi Pengeluaran',
            'Ekspor Laporan Transaksi ke Format Excel',
            'Keamanan Isolasi Data Pribadi per Pengguna (Tenant Isolation)'
          ],
          'creator' => [
            '@type' => 'Organization',
            'name' => 'Intech Studio',
            'url' => 'https://intechstudio.id'
          ]
        ],
        [
          '@type' => 'FAQPage',
          '@id' => $pageUrl . '#faq',
          'mainEntity' => [
            [
              '@type' => 'Question',
              'name' => 'Apakah aplikasi BudgetIn benar-benar gratis?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Ya! Anda bisa mendaftar secara langsung dan menggunakan semua fitur pencatatan multi-dompet, transaksi rutin, target anggaran, dan ekspor excel tanpa biaya langganan.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Apakah orang lain bisa melihat data keuangan saya?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Tidak sama sekali. Setiap akun memiliki ruang data terisolasi (Tenant Isolation). Data mutasi, dompet, dan anggaran Anda hanya dapat diakses oleh Anda sendiri.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Berapa banyak dompet dan rekening yang bisa saya buat?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Tidak ada batasan! Anda bebas membuat rekening Bank (BCA, Mandiri, BRI), kas tunai harian, maupun e-wallet (GoPay, OVO, DANA) sebanyak yang Anda butuhkan.'
              ]
            ]
          ]
        ],
        [
          '@type' => 'BreadcrumbList',
          '@id' => $pageUrl . '#breadcrumb',
          'itemListElement' => [
            [
              '@type' => 'ListItem',
              'position' => 1,
              'name' => 'Home',
              'item' => $pageUrl
            ],
            [
              '@type' => 'ListItem',
              'position' => 2,
              'name' => 'Fitur',
              'item' => $pageUrl . '#fitur'
            ],
            [
              '@type' => 'ListItem',
              'position' => 3,
              'name' => 'FAQ',
              'item' => $pageUrl . '#faq'
            ]
          ]
        ]
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Alpine.js & Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .ambient-mesh {
            background-image: 
                radial-gradient(at 10% 15%, rgba(16, 185, 129, 0.18) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(245, 158, 11, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 60%, rgba(99, 102, 241, 0.18) 0px, transparent 50%),
                radial-gradient(at 20% 80%, rgba(6, 182, 212, 0.18) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.10) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05), 0 0 15px rgba(16, 185, 129, 0.05);
        }

        .dark .glass-card {
            background: rgba(18, 24, 38, 0.88);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(8px) rotate(-1deg); }
        }

        .animate-float {
            animation: float-slow 5s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: float-reverse 6s ease-in-out infinite;
        }

        .gradient-text-cheerful {
            background: linear-gradient(135deg, #059669 0%, #0284c7 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] dark:bg-[#0B0F19] text-zinc-800 dark:text-zinc-100 min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white antialiased relative overflow-x-hidden transition-colors duration-300">
    
    <!-- Ambient Glow Backdrop -->
    <div class="fixed inset-0 ambient-mesh pointer-events-none z-0"></div>
    <div class="fixed inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:24px_24px] opacity-40 pointer-events-none z-0"></div>

    <!-- 1. Header Navigation Bar -->
    <div class="w-full sticky top-2 sm:top-4 z-50 px-3 sm:px-6">
        <header class="max-w-6xl mx-auto rounded-2xl sm:rounded-3xl glass-card px-3 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-3 shadow-lg">
            <!-- Brand with Icon as "B" -->
            <a href="/" class="flex items-center shrink-0 group">
                @if ($appLogo)
                    <img src="{{ asset('storage/' . $appLogo) }}" alt="{{ $appName }}" class="h-7 sm:h-8 w-auto mr-1.5">
                    <span class="font-heading font-black text-lg sm:text-xl tracking-tight text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        {{ $appName }}<span class="text-emerald-500">.</span>
                    </span>
                @else
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-icon.svg') }}" alt="B" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl shadow-md shadow-emerald-500/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300">
                        <span class="font-heading font-black text-lg sm:text-xl tracking-tight text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors ml-1">
                            udget<span class="text-emerald-500">In</span><span class="text-emerald-500">.</span>
                        </span>
                    </div>
                @endif
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-zinc-600 dark:text-zinc-300">
                <a href="#fitur" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Fitur</a>
                <a href="#multi-wallet" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Multi-Dompet</a>
                <a href="#anggaran" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Limit Anggaran</a>
                <a href="#rutin" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Transaksi Rutin</a>
                <a href="#faq" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">FAQ</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-1.5 sm:gap-2.5">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-emerald-500/25 transition-all">
                        <span>Dashboard</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                @else
                    <a href="{{ route('admin.login') }}"
                        class="inline-flex items-center px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-bold text-zinc-700 dark:text-zinc-200 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('admin.register') }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-600 hover:to-teal-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-emerald-500/25 transition-all">
                        <span>Daftar</span>
                        <svg class="w-3.5 h-3.5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @endauth
            </div>
        </header>
    </div>

    <!-- 2. Main Hero Section -->
    <main class="relative z-10 flex-1 max-w-6xl mx-auto px-4 sm:px-6 pt-8 sm:pt-16 pb-16 sm:pb-20 text-center">
        
        <!-- Bubbly Pill Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-4 sm:py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-700/60 text-emerald-800 dark:text-emerald-300 text-[11px] sm:text-xs font-extrabold mx-auto mb-4 sm:mb-6 shadow-xs">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span>Aplikasi Manajemen Keuangan & Multi-Dompet</span>
        </div>

        <!-- Big Cheerful Headline -->
        <h1 class="font-heading text-3xl xs:text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-zinc-900 dark:text-white tracking-tight leading-[1.18] sm:leading-[1.12] mb-4 sm:mb-6 max-w-4xl mx-auto">
            Atur Duit Jadi Gampang, <br class="hidden sm:block">
            Nabung Jadi <span class="gradient-text-cheerful">Lebih Terstruktur!</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-xs sm:text-base md:text-lg text-zinc-600 dark:text-zinc-300 max-w-2xl mx-auto leading-relaxed mb-6 sm:mb-8 font-medium px-2">
            Kelola saldo multi-dompet (Bank, Tunai, E-Wallet), pantau limit target anggaran bulanan, jadwalkan transaksi rutin, dan ekspor laporan kas dalam 1 klik.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4 max-w-md mx-auto mb-10 sm:mb-14">
            <a href="{{ route('admin.register') }}"
                class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-black text-sm sm:text-base shadow-xl shadow-emerald-500/30 hover:scale-105 transition-all duration-300">
                <span>Mulai Sekarang — Gratis</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>

            <a href="#fitur"
                class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200 font-bold text-xs sm:text-sm shadow-xs transition-all">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Lihat Fitur Lengkap</span>
            </a>
        </div>

        <!-- Trust Highlights Grid (Mobile 2x2, Desktop Inline) -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center gap-2.5 sm:gap-6 text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400 mb-12 sm:mb-20 text-left sm:text-center">
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Multi-Rekening Bank</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Limit Anggaran Bulanan</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Data Terisolasi Aman</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Ekspor Excel Cepat</span>
            </div>
        </div>

        <!-- 3. Floating Quick Info Chips & Real Workspace Mockup -->
        <div class="relative max-w-5xl mx-auto mb-20 sm:mb-28">
            
            <!-- Floating Chip 1 (Desktop only) -->
            <div class="absolute -top-6 -left-4 sm:-left-10 z-20 glass-card px-4 py-3 rounded-2xl shadow-xl animate-float hidden md:flex items-center gap-3 border-emerald-300/50">
                <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase">Pemasukan Kas</p>
                    <p class="text-xs font-black text-emerald-600 dark:text-emerald-400">+Rp 15.000.000 (Bank BCA)</p>
                </div>
            </div>

            <!-- Floating Chip 2 (Desktop only) -->
            <div class="absolute -top-8 -right-4 sm:-right-8 z-20 glass-card px-4 py-3 rounded-2xl shadow-xl animate-float-reverse hidden md:flex items-center gap-3 border-amber-300/50">
                <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase">Target Anggaran</p>
                    <p class="text-xs font-black text-amber-600 dark:text-amber-400">Limit Terjaga Aman</p>
                </div>
            </div>

            <!-- Real Workspace Frame -->
            <div class="rounded-2xl sm:rounded-3xl glass-card p-3.5 sm:p-7 text-left shadow-2xl border-2 border-emerald-500/20 relative overflow-hidden">
                
                <!-- Browser Bar -->
                <div class="flex items-center justify-between pb-3 sm:pb-4 mb-4 sm:mb-6 border-b border-zinc-200 dark:border-zinc-800">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-rose-400"></div>
                        <div class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-amber-400"></div>
                        <div class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-emerald-400"></div>
                        <span class="ml-2 sm:ml-3 text-[11px] sm:text-xs font-extrabold text-zinc-500 dark:text-zinc-400 truncate max-w-[140px] sm:max-w-none">{{ $appName }} Dashboard Workspace</span>
                    </div>
                    <span class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] sm:text-xs font-extrabold border border-emerald-500/30 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Data
                    </span>
                </div>

                <!-- 4 Colorful Wallet Badges -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-4 sm:mb-6">
                    <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/30 border border-blue-200 dark:border-blue-800/60">
                        <div class="flex items-center justify-between text-[10px] sm:text-xs mb-1 font-bold text-blue-600 dark:text-blue-400">
                            <span class="truncate">Bank BCA</span>
                            <span class="text-[9px] uppercase font-extrabold bg-blue-100 dark:bg-blue-900/60 px-1 py-0.2 rounded">Bank</span>
                        </div>
                        <h4 class="text-xs sm:text-base font-black text-zinc-900 dark:text-white truncate">Rp 18.500.000</h4>
                    </div>

                    <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/40 dark:to-teal-950/30 border border-emerald-200 dark:border-emerald-800/60">
                        <div class="flex items-center justify-between text-[10px] sm:text-xs mb-1 font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="truncate">Dompet Kas</span>
                            <span class="text-[9px] uppercase font-extrabold bg-emerald-100 dark:bg-emerald-900/60 px-1 py-0.2 rounded">Tunai</span>
                        </div>
                        <h4 class="text-xs sm:text-base font-black text-zinc-900 dark:text-white truncate">Rp 2.450.000</h4>
                    </div>

                    <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-cyan-50 to-sky-50 dark:from-cyan-950/40 dark:to-sky-950/30 border border-cyan-200 dark:border-cyan-800/60">
                        <div class="flex items-center justify-between text-[10px] sm:text-xs mb-1 font-bold text-cyan-600 dark:text-cyan-400">
                            <span class="truncate">GoPay</span>
                            <span class="text-[9px] uppercase font-extrabold bg-cyan-100 dark:bg-cyan-900/60 px-1 py-0.2 rounded">E-Wallet</span>
                        </div>
                        <h4 class="text-xs sm:text-base font-black text-zinc-900 dark:text-white truncate">Rp 950.000</h4>
                    </div>

                    <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-950/40 dark:to-pink-950/30 border border-purple-200 dark:border-purple-800/60">
                        <div class="flex items-center justify-between text-[10px] sm:text-xs mb-1 font-bold text-purple-600 dark:text-purple-400">
                            <span class="truncate">Total Kas</span>
                            <span class="text-[9px] uppercase font-extrabold bg-purple-100 dark:bg-purple-900/60 px-1 py-0.2 rounded">Total</span>
                        </div>
                        <h4 class="text-xs sm:text-base font-black text-purple-600 dark:text-purple-300 truncate">Rp 21.900.000</h4>
                    </div>
                </div>

                <!-- Mid Split: Target Anggaran & Mutasi Kas Nyata -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 sm:gap-5">
                    <!-- Left (7 Cols): Budget Progress -->
                    <div class="md:col-span-7 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <h4 class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white truncate">Target Limit Anggaran</h4>
                            </div>
                            <span class="text-[10px] sm:text-xs font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg flex-shrink-0">Terkendali</span>
                        </div>

                        <!-- Progress 1: Food -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] sm:text-xs font-bold text-zinc-700 dark:text-zinc-300">
                                <span>Makanan & Minuman</span>
                                <span>Rp 1.450k <span class="text-zinc-400">/ 2.500k (58%)</span></span>
                            </div>
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-full rounded-full" style="width: 58%"></div>
                            </div>
                        </div>

                        <!-- Progress 2: Transport -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] sm:text-xs font-bold text-zinc-700 dark:text-zinc-300">
                                <span>Bensin & Transport</span>
                                <span>Rp 600k <span class="text-zinc-400">/ 800k (75%)</span></span>
                            </div>
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-full rounded-full" style="width: 75%"></div>
                            </div>
                        </div>

                        <!-- Progress 3: Shopping -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] sm:text-xs font-bold text-zinc-700 dark:text-zinc-300">
                                <span>Belanja & Hiburan</span>
                                <span>Rp 400k <span class="text-zinc-400">/ 1.500k (27%)</span></span>
                            </div>
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-teal-400 to-cyan-500 h-full rounded-full" style="width: 27%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right (5 Cols): Live Mutasi Kas -->
                    <div class="md:col-span-5 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between space-y-2.5 sm:space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white">Mutasi Kas Terakhir</h4>
                            <span class="text-[9px] sm:text-[10px] text-zinc-400 font-bold">Hari ini</span>
                        </div>

                        <div class="space-y-2">
                            <!-- Income -->
                            <div class="flex items-center justify-between p-2 sm:p-2.5 rounded-lg sm:rounded-xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/40">
                                <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-emerald-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-black text-zinc-900 dark:text-white truncate">Project Freelance</p>
                                        <p class="text-[9px] sm:text-[10px] text-zinc-400 truncate">Bank BCA</p>
                                    </div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-black text-emerald-600 dark:text-emerald-400 flex-shrink-0">+Rp 4.500.000</span>
                            </div>

                            <!-- Expense -->
                            <div class="flex items-center justify-between p-2 sm:p-2.5 rounded-lg sm:rounded-xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/40">
                                <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-rose-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-black text-zinc-900 dark:text-white truncate">Supermarket</p>
                                        <p class="text-[9px] sm:text-[10px] text-zinc-400 truncate">Dompet Kas</p>
                                    </div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-black text-rose-600 dark:text-rose-400 flex-shrink-0">-Rp 320.000</span>
                            </div>

                            <!-- Transfer -->
                            <div class="flex items-center justify-between p-2 sm:p-2.5 rounded-lg sm:rounded-xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40">
                                <div class="flex items-center gap-1.5 sm:gap-2 min-w-0">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-indigo-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] sm:text-xs font-black text-zinc-900 dark:text-white truncate">Pindah Dana</p>
                                        <p class="text-[9px] sm:text-[10px] text-zinc-400 truncate">BCA ➔ GoPay</p>
                                    </div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-black text-indigo-600 dark:text-indigo-400 flex-shrink-0">Rp 500.000</span>
                            </div>
                        </div>

                        <div class="pt-1 text-center">
                            <span class="text-[10px] sm:text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Tercatat Real-Time</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Bento Grid Real Features Section -->
        <div id="fitur" class="text-left mb-20 sm:mb-28">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="px-3.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    <span>Fitur Unggulan {{ $appName }}</span>
                </span>
                <h2 class="font-heading text-2xl sm:text-4xl md:text-5xl font-black text-zinc-900 dark:text-white tracking-tight mt-2 mb-2 sm:mb-3">
                    Solusi Lengkap Arus Kas & Anggaran
                </h2>
                <p class="text-xs sm:text-base text-zinc-600 dark:text-zinc-400">
                    Dirancang dengan arsitektur modern untuk memudahkan pencatatan harian, kontrol limit pengeluaran, dan transparansi saldo.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <!-- Bento 1: Multi-Wallet (Cash Accounts) -->
                <div id="multi-wallet" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-emerald-400 transition-all duration-300 hover:shadow-xl group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Manajemen Multi-Dompet</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Kelola rekening Bank (BCA, Mandiri), Dompet Kas Tunai, hingga E-Wallet dalam satu layar dengan fitur Pindah Dana / Transfer antar rekening.
                    </p>
                </div>

                <!-- Bento 2: Category Budget Planner -->
                <div id="anggaran" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-amber-400 transition-all duration-300 hover:shadow-xl group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-amber-500/25 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Target Limit Anggaran</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Tentukan batas pengeluaran bulanan per kategori. Indikator progress bar memberikan sinyal visual otomatis jika pengeluaran hampir atau sudah melebihi limit.
                    </p>
                </div>

                <!-- Bento 3: Recurring Transactions -->
                <div id="rutin" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-cyan-400 transition-all duration-300 hover:shadow-xl group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-cyan-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Transaksi Rutin Terjadwal</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Otomatisasi pengeluaran rutin seperti sewa, tagihan WiFi, listrik, atau gaji berkala dengan penjadwalan otomatis atau eksekusi manual kapan saja.
                    </p>
                </div>

                <!-- Bento 4: 6-Month Visual Analytics & Donut (Wide) -->
                <div class="md:col-span-2 p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-indigo-400 transition-all duration-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-indigo-500/25">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Grafik Tren 6 Bulan & Donut Komposisi Biaya</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4">
                        Evaluasi pola keuangan dengan grafik area perbandingan pemasukan vs pengeluaran 6 bulan, diagram donut proporsi biaya, dan tabel rekap tabungan tahunan.
                    </p>
                    <div class="flex flex-wrap gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-black text-zinc-700 dark:text-zinc-300">
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">ApexCharts Area Trend</span>
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-pink-50 dark:bg-pink-950/60 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800">Donut Breakdown</span>
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Rekap 12 Bulan</span>
                    </div>
                </div>

                <!-- Bento 5: Security & Excel Export (1 Col) -->
                <div class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-emerald-400 transition-all duration-300 hover:shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-emerald-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Privasi Data & Ekspor Excel</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4">
                            Setiap pengguna memiliki ruang data terisolasi (*Tenant Isolation*). Unduh rekapitulasi data mutasi kas ke format Excel dalam 1 klik.
                        </p>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>1-Click Excel Export</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- 5. Cara Kerja 3 Langkah Mudah -->
        <div class="max-w-4xl mx-auto mb-20 sm:mb-28 text-left">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                <span class="px-3.5 py-1 rounded-full bg-cyan-100 dark:bg-cyan-950/80 text-cyan-700 dark:text-cyan-300 text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span>Langkah Penggunaan</span>
                </span>
                <h2 class="font-heading text-2xl sm:text-4xl font-black text-zinc-900 dark:text-white tracking-tight mt-2 mb-2">
                    Mulai dalam 3 Langkah Sederhana
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <!-- Step 1 -->
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-emerald-500/20">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-emerald-500/30">
                        1
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Daftar Akun & Buat Dompet</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Buat akun gratis. Dompet kas utama Anda langsung siap digunakan dan Anda bisa menambahkan rekening bank lainnya.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-teal-500/20">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-teal-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-teal-500/30">
                        2
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Catat Mutasi & Atur Limit</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Catat pemasukan, pengeluaran, atau pindah dana antar dompet serta tentukan target limit anggaran bulanan.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-cyan-500/20">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-cyan-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-cyan-500/30">
                        3
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Pantau Grafik & Evaluasi</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Pantau tren arus kas bulanan secara real-time dan unduh laporan transaksi kapan saja dalam format Excel.
                    </p>
                </div>
            </div>
        </div>

        <!-- 6. Comparison: Cara Lama vs Pakai BudgetIn -->
        <div class="max-w-4xl mx-auto mb-20 sm:mb-28 text-left">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                <span class="px-3.5 py-1 rounded-full bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300 text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    <span>Perbandingan Solusi</span>
                </span>
                <h2 class="font-heading text-2xl sm:text-4xl font-black text-zinc-900 dark:text-white tracking-tight mt-2 mb-2">
                    Kenapa Harus Beralih ke {{ $appName }}?
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Old Way -->
                <div class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400 font-heading font-black text-base sm:text-lg">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span>Catat di Buku / Spreadsheet Biasa</span>
                    </div>
                    <ul class="space-y-2 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">
                        <li class="flex items-start gap-2">
                            <span class="text-rose-500 font-bold">•</span>
                            <span>Ribet buka laptop atau cari buku setiap habis melakukan transaksi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-rose-500 font-bold">•</span>
                            <span>Tidak ada grafik visual otomatis & sering tidak sinkron antar dompet.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-rose-500 font-bold">•</span>
                            <span>Tidak ada alarm batas limit anggaran, pengeluaran tiba-tiba membengkak.</span>
                        </li>
                    </ul>
                </div>

                <!-- BudgetIn Way -->
                <div class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl bg-emerald-50/70 dark:bg-emerald-950/30 border-2 border-emerald-400 dark:border-emerald-600 space-y-3 sm:space-y-4 shadow-xl">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-heading font-black text-base sm:text-lg">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Pakai {{ $appName }}</span>
                    </div>
                    <ul class="space-y-2 text-xs sm:text-sm text-zinc-700 dark:text-zinc-200 font-medium">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Catat mutasi cepat dalam 3 detik dari smartphone maupun desktop.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Grafik visual tren 6 bulan & kalkulasi multi-wallet otomatis.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Target limit anggaran real-time menjaga keuangan tetap sehat & disiplin.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 7. Interactive FAQ Accordion -->
        <div id="faq" x-data="{ active: null }" class="max-w-3xl mx-auto mb-20 sm:mb-28 text-left">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                <span class="px-3.5 py-1 rounded-full bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tanya Jawab</span>
                </span>
                <h2 class="font-heading text-2xl sm:text-4xl font-black text-zinc-900 dark:text-white tracking-tight mt-2 mb-2">
                    Pertanyaan yang Sering Muncul
                </h2>
            </div>

            <div class="space-y-3">
                <!-- Q1 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 1 ? null : 1)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Apakah aplikasi ini benar-benar gratis?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 1 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Ya! Anda bisa mendaftar secara langsung dan menggunakan semua fitur pencatatan multi-dompet, transaksi rutin, target anggaran, dan ekspor excel tanpa biaya langganan.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 2 ? null : 2)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Apakah orang lain bisa melihat data keuangan saya?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 2 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Tidak sama sekali. Setiap akun memiliki ruang data terisolasi (*Tenant Isolation*). Data mutasi, dompet, dan anggaran Anda hanya dapat diakses oleh Anda sendiri.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 3 ? null : 3)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Berapa banyak dompet dan rekening yang bisa saya buat?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 3 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Tidak ada batasan! Anda bebas membuat rekening Bank (BCA, Mandiri, BRI), kas tunai harian, maupun e-wallet (GoPay, OVO, DANA) sebanyak yang Anda butuhkan.
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. Grand Bottom CTA Banner -->
        <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 p-6 sm:p-14 text-center relative overflow-hidden shadow-2xl text-white">
            <div class="max-w-2xl mx-auto relative z-10">
                <div class="w-12 h-12 sm:w-14 sm:h-14 mx-auto mb-3 sm:mb-4 rounded-xl sm:rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h2 class="font-heading text-2xl sm:text-4xl md:text-5xl font-black tracking-tight mb-3 sm:mb-4 text-white">
                    Siap Mengatur Finansialmu Lebih Rapi?
                </h2>
                <p class="text-xs sm:text-base text-emerald-100 mb-6 sm:mb-8 leading-relaxed font-medium">
                    Daftar gratis sekarang dalam 30 detik. Mulai atur dompet kas, pantau limit anggaran, dan capai tujuan keuanganmu!
                </p>
                <a href="{{ route('admin.register') }}"
                    class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-white text-emerald-700 hover:bg-emerald-50 font-black text-sm sm:text-base shadow-2xl transition-all hover:scale-105">
                    <span>Buat Akun Gratis Sekarang</span>
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </main>

    <!-- 9. Footer -->
    <footer class="border-t border-zinc-200 dark:border-zinc-800/80 bg-white/70 dark:bg-[#060910]/90 backdrop-blur-md py-6 sm:py-8 px-4 text-xs text-zinc-500">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 sm:gap-2">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-icon.svg') }}" alt="B" class="w-4 h-4 rounded-md inline-block mr-0.5">
                    <span class="font-heading font-black text-zinc-800 dark:text-white text-xs sm:text-sm">udget<span class="text-emerald-500">In</span>.</span>
                </div>
                <span>•</span>
                <p>&copy; {{ date('Y') }} {{ $appName }}. Crafted with care by <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer" class="text-zinc-700 dark:text-zinc-300 hover:text-emerald-500 underline font-bold transition-colors">Intech Studio</a>.</p>
            </div>
            <div class="flex items-center justify-center gap-5 text-xs text-zinc-500 dark:text-zinc-400 font-semibold">
                <a href="{{ route('admin.login') }}" class="hover:text-emerald-500 transition-colors">Masuk</a>
                <a href="{{ route('admin.register') }}" class="hover:text-emerald-500 transition-colors">Daftar</a>
                <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-500 transition-colors">Intech Studio ↗</a>
            </div>
        </div>
    </footer>
</body>

</html>
