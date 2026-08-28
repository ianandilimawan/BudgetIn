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
          'operatingSystem' => 'All (Web-based, PWA Android & iOS)',
          'browserRequirements' => 'Requires modern web browser',
          'softwareVersion' => '1.2.0',
          'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.9',
            'reviewCount' => '180',
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
            'Perencanaan Anggaran Proyek Khusus (Pernikahan, Liburan, Renovasi, Bisnis)',
            'AI Financial Health Advisor & Skor Finansial 4 Pilar (Gemini AI)',
            'Target Limit Anggaran Bulanan per Kategori',
            'Transaksi Rutin Terjadwal Otomatis',
            'Grafik Visual Tren 6 Bulan & Komposisi Pengeluaran',
            'Ekspor Laporan Transaksi ke Format Excel',
            'PWA Mobile-First (Bisa diinstall di Layar Utama HP)'
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
              'name' => 'Berapa biaya untuk menggunakan aplikasi BudgetIn?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Anda dapat mendaftar dan memulai secara gratis untuk menikmati fitur esensial seperti pencatatan mutasi kas multi-dompet, target anggaran dasar, dan ekspor laporan. Untuk kebutuhan pengelolaan proyek tanpa batas dan analisa AI mendalam, tersedia opsi upgrade yang sangat terjangkau.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Apa itu fitur Anggaran Proyek di BudgetIn?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Anggaran Proyek memungkinkan Anda membuat rencana pendanaan khusus untuk momen besar seperti Pernikahan, Liburan, Renovasi Rumah, atau Modal Bisnis, lengkap dengan breakdown pos belanja bertahap dan kalkulasi realisasi pengeluaran.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Bagaimana cara kerja AI Financial Health Advisor?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Didukung oleh Gemini AI, sistem menganalisa 4 pilar kondisi finansial Anda (Savings Rate, Emergency Runway, Kepatuhan Anggaran, dan Stabilitas Kas) untuk menghasilkan skor kesehatan keuangan (0-100) serta rekomendasi langkah perbaikan konkret.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Apakah aplikasi ini bisa di-install di smartphone?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Ya! BudgetIn mendukung teknologi Progressive Web App (PWA). Anda dapat memasang aplikasi langsung ke layar utama Android (Chrome) maupun iOS (Safari Add to Home Screen) layaknya aplikasi native.'
              ]
            ],
            [
              '@type' => 'Question',
              'name' => 'Apakah orang lain bisa melihat data keuangan saya?',
              'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Tidak sama sekali. Setiap akun memiliki ruang data terisolasi (Tenant Isolation). Data mutasi, dompet, dan anggaran Anda hanya dapat diakses oleh Anda sendiri.'
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
            animation: ambient-drift 18s ease-in-out infinite alternate;
        }

        @keyframes ambient-drift {
            0% { transform: scale(1) translate(0px, 0px); }
            50% { transform: scale(1.04) translate(10px, -15px); }
            100% { transform: scale(0.98) translate(-10px, 10px); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05), 0 0 15px rgba(16, 185, 129, 0.05);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 24px 48px -12px rgba(16, 185, 129, 0.12), 0 0 20px rgba(16, 185, 129, 0.08);
        }

        .dark .glass-card {
            background: rgba(18, 24, 38, 0.88);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .dark .glass-card:hover {
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.7), 0 0 20px rgba(16, 185, 129, 0.15);
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(0.6deg); }
        }

        @keyframes float-reverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(6px) rotate(-0.6deg); }
        }

        .animate-float {
            animation: float-slow 6s ease-in-out infinite;
        }

        .animate-float-reverse {
            animation: float-reverse 7s ease-in-out infinite;
        }

        .gradient-text-cheerful {
            background: linear-gradient(135deg, #059669 0%, #0284c7 50%, #6366f1 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-shine 8s ease-in-out infinite alternate;
        }

        @keyframes text-shine {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Subtle button light sweep */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }

        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(
                60deg,
                rgba(255, 255, 255, 0) 20%,
                rgba(255, 255, 255, 0.25) 50%,
                rgba(255, 255, 255, 0) 80%
            );
            transform: rotate(25deg);
            transition: all 0.75s ease-in-out;
        }

        .btn-shine:hover::after {
            left: 140%;
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
            <nav class="hidden lg:flex items-center gap-6 text-xs font-bold text-zinc-600 dark:text-zinc-300">
                <a href="#fitur" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Fitur Utama</a>
                <a href="#proyek" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Anggaran Proyek</a>
                <a href="#ai-advisor" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">AI Advisor</a>
                <a href="#multi-wallet" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Multi-Dompet</a>
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
        <div class="inline-flex items-center gap-2 px-3.5 py-1 sm:px-4 sm:py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-700/60 text-emerald-800 dark:text-emerald-300 text-[11px] sm:text-xs font-extrabold mx-auto mb-4 sm:mb-6 shadow-xs">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span>Aplikasi Manajemen Kas, Anggaran Proyek & AI Advisor</span>
        </div>

        <!-- Big Cheerful Headline -->
        <h1 class="font-heading text-3xl xs:text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-zinc-900 dark:text-white tracking-tight leading-[1.18] sm:leading-[1.12] mb-4 sm:mb-6 max-w-4xl mx-auto">
            Atur Duit Jadi Gampang, <br class="hidden sm:block">
            Nabung Jadi <span class="gradient-text-cheerful">Lebih Terstruktur!</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-xs sm:text-base md:text-lg text-zinc-600 dark:text-zinc-300 max-w-2xl mx-auto leading-relaxed mb-6 sm:mb-8 font-medium px-2">
            Kelola saldo multi-dompet (Bank, Tunai, E-Wallet), capai target dana momen besar dengan <strong>Anggaran Proyek</strong>, serta dapatkan skor analisa kesehatan finansial cerdas berbasis AI.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4 max-w-md mx-auto mb-10 sm:mb-14">
            <a href="{{ route('admin.register') }}"
                class="btn-shine inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-black text-sm sm:text-base shadow-xl shadow-emerald-500/30 hover:scale-105 transition-all duration-300">
                <span>Mulai Sekarang — Gratis</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>

            <a href="#fitur"
                class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200 font-bold text-xs sm:text-sm shadow-xs hover:-translate-y-0.5 transition-all">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Lihat Fitur Lengkap</span>
            </a>
        </div>

        <!-- Trust Highlights Grid -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center gap-2.5 sm:gap-6 text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400 mb-12 sm:mb-20 text-left sm:text-center">
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800 hover:-translate-y-0.5 transition-transform">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Multi-Rekening Bank</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800 hover:-translate-y-0.5 transition-transform">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Anggaran Proyek Khusus</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800 hover:-translate-y-0.5 transition-transform">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">AI Financial Advisor</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/60 dark:bg-zinc-900/60 sm:bg-transparent p-2 sm:p-0 rounded-lg border sm:border-0 border-zinc-200/60 dark:border-zinc-800 hover:-translate-y-0.5 transition-transform">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">PWA Siap Pasang di HP</span>
            </div>
        </div>

        <!-- 3. Floating Quick Info Chips & Real Workspace Mockup -->
        <div class="relative max-w-5xl mx-auto mb-20 sm:mb-28">

            <!-- Floating Chip 1 (Desktop only - AI Score) -->
            <div class="absolute -top-6 -left-4 sm:-left-10 z-20 glass-card px-4 py-3 rounded-2xl shadow-xl animate-float hidden md:flex items-center gap-3 border-emerald-300/50 hover:scale-105 transition-transform">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase">AI Skor Finansial</p>
                    <p class="text-xs font-black text-emerald-600 dark:text-emerald-400">97/100 (Sangat Sehat)</p>
                </div>
            </div>

            <!-- Floating Chip 2 (Desktop only - Project Budget) -->
            <div class="absolute -top-8 -right-4 sm:-right-8 z-20 glass-card px-4 py-3 rounded-2xl shadow-xl animate-float-reverse hidden md:flex items-center gap-3 border-purple-300/50 hover:scale-105 transition-transform">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-500 to-pink-500 text-white flex items-center justify-center shadow-md shadow-purple-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-bold uppercase">Proyek Pernikahan</p>
                    <p class="text-xs font-black text-purple-600 dark:text-purple-400">Pagu Rp 75jt (Target 4 Bln)</p>
                </div>
            </div>

            <!-- Real Workspace Frame (1:1 Authentic App UI Key Visual with Auto-Demo Slideshow) -->
            <div x-data="{ 
                kvView: 'overview',
                views: ['overview', 'projects', 'ai_advisor'],
                isPaused: false,
                autoTimer: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    if (this.autoTimer) clearInterval(this.autoTimer);
                    this.autoTimer = setInterval(() => {
                        if (!this.isPaused) {
                            const curIdx = this.views.indexOf(this.kvView);
                            this.kvView = this.views[(curIdx + 1) % this.views.length];
                        }
                    }, 4500);
                },
                setView(view) {
                    this.kvView = view;
                    this.startAutoplay();
                },
                pauseAutoplay() {
                    this.isPaused = true;
                },
                resumeAutoplay() {
                    this.isPaused = false;
                }
            }" 
            @mouseenter="pauseAutoplay()" 
            @mouseleave="resumeAutoplay()"
            class="rounded-2xl sm:rounded-3xl glass-card p-2 sm:p-4 text-left shadow-2xl border-2 border-emerald-500/20 relative overflow-hidden bg-zinc-100/90 dark:bg-[#0c1017]/95">

                <!-- Browser Window Top Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between px-3 py-2.5 mb-3 border-b border-zinc-200/80 dark:border-zinc-800/80 bg-white/70 dark:bg-zinc-900/70 rounded-xl gap-2">
                    <div class="flex items-center justify-between sm:justify-start gap-1.5 w-full sm:w-auto">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            <div class="hidden md:flex items-center gap-1.5 ml-3 px-3 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-[11px] text-zinc-500 font-mono border border-zinc-200/60 dark:border-zinc-700/60">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>https://budgetin.intechstudio.id/dashboard</span>
                            </div>
                        </div>

                        <!-- Auto-Demo Live Status Indicator -->
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold border transition-colors"
                            :class="isPaused ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/60' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/60'">
                            <span class="w-1.5 h-1.5 rounded-full" :class="isPaused ? 'bg-amber-500' : 'bg-emerald-500 animate-ping'"></span>
                            <span x-text="isPaused ? 'Dijeda (Interaksi)' : 'Auto Demo (4.5s)'"></span>
                        </div>
                    </div>

                    <!-- Interactive KV View Switcher (Live tabs) -->
                    <div class="inline-flex p-1 rounded-xl bg-zinc-200/70 dark:bg-zinc-800 border border-zinc-300/50 dark:border-zinc-700/70 text-[11px] font-extrabold self-start sm:self-auto overflow-x-auto max-w-full">
                        <button type="button" @click="setView('overview')"
                            :class="kvView === 'overview' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                            class="px-2.5 sm:px-3 py-1 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span>Dashboard</span>
                        </button>
                        <button type="button" @click="setView('projects')"
                            :class="kvView === 'projects' ? 'bg-white dark:bg-zinc-900 text-purple-600 dark:text-purple-400 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                            class="px-2.5 sm:px-3 py-1 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span>Proyek Acara</span>
                            <span class="px-1 py-0.2 rounded text-[9px] bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300">Baru</span>
                        </button>
                        <button type="button" @click="setView('ai_advisor')"
                            :class="kvView === 'ai_advisor' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white'"
                            class="px-2.5 sm:px-3 py-1 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>AI Health</span>
                            <span class="px-1 py-0.2 rounded text-[9px] bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300">97</span>
                        </button>
                    </div>
                </div>

                <!-- Main Application Grid: Authentic Left Sidebar + Right Content -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 items-start">

                    <!-- Real Left Sidebar Mockup (Desktop Only) -->
                    <div class="hidden lg:block lg:col-span-3 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 p-3.5 shadow-xs space-y-4">
                        <!-- Sidebar Logo -->
                        <div class="flex items-center pb-3 border-b border-zinc-100 dark:border-zinc-800">
                            <img src="{{ asset('images/logo-icon.svg') }}" alt="B" class="w-6 h-6 rounded-lg mr-1.5">
                            <span class="font-extrabold text-sm text-zinc-900 dark:text-white">Budget<span class="text-emerald-500">In</span>.</span>
                        </div>

                        <!-- Sidebar Nav Items -->
                        <div class="space-y-1 text-xs font-semibold">
                            <div class="px-2 py-1 text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Utama</div>

                            <button type="button" @click="setView('overview')"
                                :class="kvView === 'overview' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800'"
                                class="w-full flex items-center gap-2 px-2.5 py-2 rounded-xl transition-all text-left cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span>Dashboard</span>
                            </button>

                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl text-zinc-500 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>Transaksi Kas</span>
                            </div>

                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl text-zinc-500 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <span>Dompet Kas</span>
                            </div>

                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-xl text-zinc-500 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span>Target Anggaran</span>
                            </div>

                            <button type="button" @click="setView('projects')"
                                :class="kvView === 'projects' ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800'"
                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl transition-all text-left cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span>Anggaran Proyek</span>
                                </div>
                                <span class="px-1.5 py-0.2 text-[9px] font-extrabold rounded-md bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">Baru</span>
                            </button>

                            <button type="button" @click="setView('ai_advisor')"
                                :class="kvView === 'ai_advisor' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 font-bold' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800'"
                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl transition-all text-left cursor-pointer">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span>Kesehatan & AI</span>
                                </div>
                                <span class="px-1.5 py-0.2 text-[9px] font-extrabold rounded-md bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300">97</span>
                            </button>
                        </div>

                        <!-- Sidebar Profile -->
                        <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-black text-xs flex items-center justify-center">
                                I
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-zinc-900 dark:text-white truncate">Ian Budgeter</p>
                                <p class="text-[10px] text-zinc-400 truncate">Finance Pro Plan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Main Content Area (9 Cols on Desktop, Full Width on Mobile) -->
                    <div class="lg:col-span-9 space-y-3 sm:space-y-4">

                        <!-- TAB 1: REAL DASHBOARD KEUANGAN -->
                        <div x-show="kvView === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="space-y-3 sm:space-y-4">

                            <!-- Dashboard Header & +Catat Cepat Button -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white dark:bg-zinc-900 p-3 sm:p-4 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                <div>
                                    <h2 class="text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white">Dashboard Keuangan</h2>
                                    <p class="text-[11px] text-zinc-500">Ringkasan kondisi finansial, saldo dompet, tren arus kas, dan kontrol anggaran.</p>
                                </div>
                                <div class="inline-flex items-center gap-2 self-start sm:self-auto">
                                    <span class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-bold shadow-sm shadow-emerald-600/20 flex items-center gap-1.5">
                                        <span>+ Catat Cepat</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Saldo Dompet & Rekening Section (1:1 with Real App) -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-zinc-900 dark:text-white">
                                        <div class="w-5 h-5 rounded-md bg-indigo-50 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        </div>
                                        <span>Saldo Dompet & Rekening</span>
                                    </div>
                                    <span class="text-[11px] font-extrabold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/80 px-2 py-0.5 rounded-lg border border-indigo-100 dark:border-indigo-900/50">
                                        Total Kas: Rp 244.700.000
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <!-- BCA Prioritas -->
                                    <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs hover:border-blue-400 transition-all">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="w-5 h-5 rounded-md bg-blue-50 dark:bg-blue-950/80 text-blue-600 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <span class="text-[8px] uppercase font-extrabold px-1 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Bank</span>
                                        </div>
                                        <p class="text-[10px] text-zinc-400 truncate">BCA Prioritas</p>
                                        <h4 class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white truncate">Rp 85.000.000</h4>
                                    </div>

                                    <!-- Mandiri Cadangan -->
                                    <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs hover:border-amber-400 transition-all">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="w-5 h-5 rounded-md bg-amber-50 dark:bg-amber-950/80 text-amber-600 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <span class="text-[8px] uppercase font-extrabold px-1 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Bank</span>
                                        </div>
                                        <p class="text-[10px] text-zinc-400 truncate">Mandiri Cadangan</p>
                                        <h4 class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white truncate">Rp 60.000.000</h4>
                                    </div>

                                    <!-- Bibit SBN -->
                                    <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs hover:border-indigo-400 transition-all">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="w-5 h-5 rounded-md bg-purple-50 dark:bg-purple-950/80 text-purple-600 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                            </div>
                                            <span class="text-[8px] uppercase font-extrabold px-1 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Investasi</span>
                                        </div>
                                        <p class="text-[10px] text-zinc-400 truncate">Bibit SBN</p>
                                        <h4 class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white truncate">Rp 95.000.000</h4>
                                    </div>

                                    <!-- Kas Tunai -->
                                    <div class="p-2.5 sm:p-3 rounded-xl sm:rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs hover:border-emerald-400 transition-all">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="w-5 h-5 rounded-md bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <span class="text-[8px] uppercase font-extrabold px-1 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Tunai</span>
                                        </div>
                                        <p class="text-[10px] text-zinc-400 truncate">Kas Dompet Tunai</p>
                                        <h4 class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400 truncate">Rp 4.700.000</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 Core Metric Summary Cards (1:1 with Real App lines 565-643) -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <div class="p-2.5 sm:p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                    <div class="text-[9px] uppercase font-bold text-zinc-400">Total Arus Kas</div>
                                    <div class="text-xs sm:text-sm font-black text-zinc-900 dark:text-white mt-0.5 truncate">Rp 244.700.000</div>
                                    <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1 rounded">Surplus • 48 Trx</span>
                                </div>

                                <div class="p-2.5 sm:p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                    <div class="text-[9px] uppercase font-bold text-zinc-400">Net Tabungan (Bln Ini)</div>
                                    <div class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400 mt-0.5 truncate">+Rp 19.650.000</div>
                                    <span class="text-[9px] font-semibold text-zinc-400">Agustus 2026</span>
                                </div>

                                <div class="p-2.5 sm:p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                    <div class="text-[9px] uppercase font-bold text-zinc-400">Pemasukan</div>
                                    <div class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400 mt-0.5 truncate">+Rp 26.500.000</div>
                                    <span class="text-[9px] font-semibold text-zinc-400">Total: 145.0jt</span>
                                </div>

                                <div class="p-2.5 sm:p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                    <div class="text-[9px] uppercase font-bold text-zinc-400">Pengeluaran</div>
                                    <div class="text-xs sm:text-sm font-black text-rose-600 dark:text-rose-400 mt-0.5 truncate">-Rp 6.850.000</div>
                                    <span class="text-[9px] font-semibold text-zinc-400">Total: 60.3jt</span>
                                </div>
                            </div>

                            <!-- Mid Split: Target Anggaran Bulanan (7 Cols) & Mutasi Kas (5 Cols) -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                <!-- Target Anggaran per Kategori -->
                                <div class="md:col-span-7 p-3 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs space-y-2.5">
                                    <div class="flex items-center justify-between pb-2 border-b border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-5 h-5 rounded-md bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xs font-bold text-zinc-900 dark:text-white">Target Anggaran Bulan Ini</h3>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg">
                                            Rp 3.000k / 6.500k (46%)
                                        </span>
                                    </div>

                                    <!-- Overall Bar -->
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-full rounded-full" style="width: 46%"></div>
                                    </div>

                                    <!-- Pos Category Items -->
                                    <div class="space-y-1.5 pt-1">
                                        <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                                            <span class="font-bold text-zinc-800 dark:text-zinc-200">Makanan & Kuliner</span>
                                            <span class="text-[11px] font-extrabold text-emerald-600">Rp 1.150k / 3.500k (32%)</span>
                                        </div>
                                        <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                                            <span class="font-bold text-zinc-800 dark:text-zinc-200">Belanja Supermarket</span>
                                            <span class="text-[11px] font-extrabold text-teal-600">Rp 1.850k / 3.000k (61%)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mutasi Kas Terakhir -->
                                <div class="md:col-span-5 p-3 sm:p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs space-y-2">
                                    <div class="flex items-center justify-between pb-1.5 border-b border-zinc-100 dark:border-zinc-800">
                                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white">Mutasi Terakhir</h3>
                                        <span class="text-[9px] text-zinc-400">Live</span>
                                    </div>

                                    <div class="space-y-1.5 text-xs">
                                        <div class="p-1.5 rounded-lg bg-emerald-50/70 dark:bg-emerald-950/30 flex items-center justify-between">
                                            <div class="truncate pr-1">
                                                <p class="font-bold text-zinc-900 dark:text-white truncate">Gaji Pokok Bulanan</p>
                                                <p class="text-[9px] text-zinc-400">BCA Prioritas</p>
                                            </div>
                                            <span class="font-black text-emerald-600 text-[11px] flex-shrink-0">+Rp 26.500k</span>
                                        </div>

                                        <div class="p-1.5 rounded-lg bg-rose-50/70 dark:bg-rose-950/30 flex items-center justify-between">
                                            <div class="truncate pr-1">
                                                <p class="font-bold text-zinc-900 dark:text-white truncate">Belanja Supermarket</p>
                                                <p class="text-[9px] text-zinc-400">BCA Prioritas</p>
                                            </div>
                                            <span class="font-black text-rose-600 text-[11px] flex-shrink-0">-Rp 1.850k</span>
                                        </div>

                                        <div class="p-1.5 rounded-lg bg-indigo-50/70 dark:bg-indigo-950/30 flex items-center justify-between">
                                            <div class="truncate pr-1">
                                                <p class="font-bold text-zinc-900 dark:text-white truncate">Top Up Reksadana SBN</p>
                                                <p class="text-[9px] text-zinc-400">Mandiri ➔ Bibit</p>
                                            </div>
                                            <span class="font-black text-indigo-600 text-[11px] flex-shrink-0">Rp 5.000k</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: REAL ANGGARAN PROYEK & RENCANA ACARA (1:1 with Real App lines 821-881) -->
                        <div x-show="kvView === 'projects'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="space-y-3 sm:space-y-4">

                            <!-- Projects Section Header (1:1) -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white dark:bg-zinc-900 p-3 sm:p-4 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center flex-shrink-0 text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Anggaran Proyek & Rencana Acara</h2>
                                            <span class="px-1.5 py-0.2 text-[9px] font-extrabold uppercase rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200/60">Fitur Baru</span>
                                        </div>
                                        <p class="text-[10px] text-zinc-500">Pagu anggaran terpisah untuk pernikahan, liburan, renovasi, atau target besar</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-xl self-start sm:self-auto">
                                    + Buat Anggaran Acara
                                </span>
                            </div>

                            <!-- 4 Real Project Cards (2x2 Grid) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Project 1: Pernikahan Impian -->
                                <div class="p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 hover:border-emerald-400 transition-all shadow-xs space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </div>
                                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">Pernikahan Impian</h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">40%</span>
                                    </div>
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-emerald-500 h-full rounded-full" style="width: 40%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-zinc-500 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                        <span>Sisa: <strong class="text-emerald-600 dark:text-emerald-400">Rp 45.000.000</strong></span>
                                        <span class="font-bold text-zinc-700 dark:text-zinc-300">112h lagi</span>
                                    </div>
                                </div>

                                <!-- Project 2: Liburan Musim Gugur Jepang -->
                                <div class="p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 hover:border-sky-400 transition-all shadow-xs space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">Liburan Musim Gugur Jepang</h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300">41%</span>
                                    </div>
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-sky-500 h-full rounded-full" style="width: 41%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-zinc-500 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                        <span>Sisa: <strong class="text-sky-600 dark:text-sky-400">Rp 20.500.000</strong></span>
                                        <span class="font-bold text-zinc-700 dark:text-zinc-300">68h lagi</span>
                                    </div>
                                </div>

                                <!-- Project 3: Renovasi Rumah & Kamar -->
                                <div class="p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 hover:border-amber-400 transition-all shadow-xs space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            </div>
                                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">Renovasi Rumah & Interior</h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300">25%</span>
                                    </div>
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-amber-500 h-full rounded-full" style="width: 25%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-zinc-500 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                        <span>Sisa: <strong class="text-amber-600 dark:text-amber-400">Rp 37.500.000</strong></span>
                                        <span class="font-bold text-zinc-700 dark:text-zinc-300">140h lagi</span>
                                    </div>
                                </div>

                                <!-- Project 4: Modal Usaha Kafe Kopi -->
                                <div class="p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 hover:border-indigo-400 transition-all shadow-xs space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate">Modal Bisnis Kafe Kopi</h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">70%</span>
                                    </div>
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-indigo-500 h-full rounded-full" style="width: 70%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-zinc-500 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                                        <span>Sisa: <strong class="text-indigo-600 dark:text-indigo-400">Rp 6.000.000</strong></span>
                                        <span class="font-bold text-zinc-700 dark:text-zinc-300">25h lagi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: REAL SKOR KESEHATAN KEUANGAN & GEMINI AI (1:1 with profile/index.blade.php) -->
                        <div x-show="kvView === 'ai_advisor'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="space-y-3 sm:space-y-4">

                            <!-- Health Score Card with Radial Score & 4 Pillars (1:1) -->
                            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-3.5 sm:p-4 shadow-xs relative overflow-hidden">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Skor Kesehatan Keuangan</h3>
                                            <p class="text-[10px] text-zinc-400">Periode Agustus 2026</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                                        Sangat Sehat (Grade A+)
                                    </span>
                                </div>

                                <!-- Radial presentation -->
                                <div class="flex items-center gap-3.5 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800 mb-3">
                                    <div class="relative w-14 h-14 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-zinc-200 dark:text-zinc-700" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                            <path stroke-dasharray="97, 100" class="text-emerald-500" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-base font-extrabold text-zinc-900 dark:text-white">97</span>
                                            <span class="text-[8px] text-zinc-400 -mt-1">/100</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed font-medium">
                                        Kondisi finansial sangat kuat dan sehat. Aliran kas surplus konsisten dengan rasio tabungan di atas target minimum.
                                    </p>
                                </div>

                                <!-- 4 Pillars Grid (1:1) -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center justify-between text-[9px] text-zinc-400">
                                            <span>Savings Rate</span>
                                            <span class="font-bold text-emerald-500">100/100</span>
                                        </div>
                                        <p class="text-xs font-black text-zinc-900 dark:text-white">82.2%</p>
                                        <p class="text-[8px] text-zinc-400">Target: > 20%</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center justify-between text-[9px] text-zinc-400">
                                            <span>Emergency Runway</span>
                                            <span class="font-bold text-emerald-500">100/100</span>
                                        </div>
                                        <p class="text-xs font-black text-zinc-900 dark:text-white">4.3 Bulan</p>
                                        <p class="text-[8px] text-zinc-400">Target: 3-6 Bulan</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center justify-between text-[9px] text-zinc-400">
                                            <span>Disiplin Anggaran</span>
                                            <span class="font-bold text-emerald-500">100/100</span>
                                        </div>
                                        <p class="text-xs font-black text-zinc-900 dark:text-white">5/5 Pos Aman</p>
                                        <p class="text-[8px] text-zinc-400">Target: 100%</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center justify-between text-[9px] text-zinc-400">
                                            <span>Net Arus Kas</span>
                                            <span class="font-bold text-emerald-500">100/100</span>
                                        </div>
                                        <p class="text-xs font-black text-zinc-900 dark:text-white">+Rp 28.765k</p>
                                        <p class="text-[8px] text-zinc-400">Target: Surplus</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Gemini AI Insights Card (1:1) -->
                            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-3.5 sm:p-4 shadow-xs space-y-2.5">
                                <div class="flex items-center justify-between pb-1.5 border-b border-zinc-100 dark:border-zinc-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">AI Financial Insights</h3>
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                                Gemini AI
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-semibold text-zinc-400">Real-Time Sync</span>
                                </div>

                                <!-- AI Quote -->
                                <div class="p-2.5 rounded-xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-indigo-500/10 border border-emerald-500/20 text-xs italic text-zinc-800 dark:text-zinc-200">
                                    "Kondisi finansial Anda berada pada kuadran keuangan terbaik. Rasio tabungan 82.2% memberi ruang akselerasi investasi dan pendanaan proyek Pernikahan tanpa mengganggu likuiditas harian."
                                </div>

                                <!-- 3 Structured AI Advice Cards -->
                                <div class="space-y-1.5 text-xs">
                                    <div class="p-2 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40">
                                        <p class="font-bold text-zinc-900 dark:text-white">Arus Kas & Tabungan</p>
                                        <p class="text-zinc-600 dark:text-zinc-400 text-[11px]">Tabungan bulanan surplus Rp 19.65jt konsisten di atas benchmark.</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40">
                                        <p class="font-bold text-zinc-900 dark:text-white">Catatan Pos Anggaran</p>
                                        <p class="text-zinc-600 dark:text-zinc-400 text-[11px]">Semua 5 pos anggaran berada di bawah batas pagu bulanan (Aman 100%).</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/40">
                                        <p class="font-bold text-zinc-900 dark:text-white">Rekomendasi Aksi Cerdas</p>
                                        <p class="text-zinc-600 dark:text-zinc-400 text-[11px]">Alokasikan 50% surplus ke instrumen pasar uang untuk pendanaan proyek acara.</p>
                                    </div>
                                </div>
                            </div>
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
                    Solusi Lengkap Finansial, Proyek & AI
                </h2>
                <p class="text-xs sm:text-base text-zinc-600 dark:text-zinc-400">
                    Dari pencatatan harian, alokasi tabungan momen besar, hingga evaluasi kesehatan finansial otomatis oleh AI.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <!-- Bento 1: Anggaran Proyek Khusus (NEW) -->
                <div id="proyek" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-purple-400 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-xl group relative overflow-hidden">
                    <span class="absolute top-4 right-4 px-2 py-0.5 rounded-md bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[10px] font-black uppercase">Baru</span>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-purple-500 to-pink-500 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-purple-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Anggaran Proyek Khusus</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Rencanakan target dana untuk Pernikahan, Liburan, Renovasi, atau Bisnis. Lengkap dengan rincian pos belanja bertahap, countdown target hari H, dan AI Project Advisor.
                    </p>
                </div>

                <!-- Bento 2: AI Financial Health Advisor (NEW) -->
                <div id="ai-advisor" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-emerald-400 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-xl group relative overflow-hidden">
                    <span class="absolute top-4 right-4 px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 text-[10px] font-black uppercase">Gemini AI</span>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-emerald-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">AI Financial Health Advisor</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Dapatkan skor kesehatan finansial otomatis (0–100) berdasarkan 4 pilar utama (*Savings Rate, Emergency Runway, Kepatuhan Anggaran, Arus Kas*) serta rekomendasi actionable dari AI.
                    </p>
                </div>

                <!-- Bento 3: Multi-Wallet (Cash Accounts) -->
                <div id="multi-wallet" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-blue-400 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-xl group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-blue-500/25 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Manajemen Multi-Dompet</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        Pantau rekening Bank (BCA, Mandiri), Kas Tunai, E-Wallet (GoPay, OVO), hingga Akun Investasi secara real-time dengan fitur Pindah Dana antar rekening.
                    </p>
                </div>

                <!-- Bento 4: 6-Month Visual Analytics & Donut (Wide) -->
                <div class="md:col-span-2 p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-indigo-400 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-xl">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-indigo-500/25">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Grafik Tren 6 Bulan & Donut Komposisi Biaya</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4">
                        Evaluasi pola keuangan dengan grafik area perbandingan pemasukan vs pengeluaran 6 bulan, diagram donut proporsi biaya, dan tabel rekap tabungan tahunan.
                    </p>
                    <div class="flex flex-wrap gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-black text-zinc-700 dark:text-zinc-300">
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">ApexCharts Trend</span>
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-pink-50 dark:bg-pink-950/60 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800">Donut Breakdown</span>
                        <span class="px-2.5 py-1 rounded-lg sm:rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Rekap 12 Bulan</span>
                    </div>
                </div>

                <!-- Bento 5: Target Anggaran & Rutin (1 Col) -->
                <div id="anggaran" class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl glass-card hover:border-amber-400 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center mb-4 sm:mb-5 shadow-lg shadow-amber-500/25">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="font-heading text-lg sm:text-xl font-extrabold text-zinc-900 dark:text-white mb-1.5 sm:mb-2">Target Limit Anggaran & Rutin</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4">
                            Tentukan batas pengeluaran bulanan per kategori dan jadwalkan mutasi rutin tagihan berkala secara otomatis.
                        </p>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Alarm Pengeluaran & Jadwal Rutin</span>
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
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-emerald-500/20 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-lg">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-emerald-500/30">
                        1
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Daftar Akun & Buat Dompet</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Buat akun gratis. Dompet kas utama Anda langsung siap digunakan dan Anda bisa menambahkan rekening bank lainnya.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-teal-500/20 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-lg">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-teal-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-teal-500/30">
                        2
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Catat Mutasi & Buat Proyek</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Catat pemasukan, pengeluaran, pindah dana, serta buat target Anggaran Proyek untuk momen spesial Anda.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl glass-card relative border-2 border-cyan-500/20 hover:-translate-y-1.5 transition-all duration-300 hover:shadow-lg">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-cyan-500 text-white font-black text-sm sm:text-base flex items-center justify-center mb-3 sm:mb-4 shadow-md shadow-cyan-500/30">
                        3
                    </div>
                    <h3 class="font-heading text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mb-1">Cek Skor AI & Evaluasi</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Dapatkan diagnosa kesehatan keuangan otomatis dari AI dan unduh laporan transaksi kapan saja dalam format Excel.
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
                <div class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 space-y-3 sm:space-y-4 hover:-translate-y-1 transition-transform duration-300">
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
                            <span>Sulit melacak alokasi dana khusus seperti tabungan pernikahan atau liburan.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-rose-500 font-bold">•</span>
                            <span>Tidak ada AI yang memberi tahu apakah rasio tabungan & dana darurat aman.</span>
                        </li>
                    </ul>
                </div>

                <!-- BudgetIn Way -->
                <div class="p-5 sm:p-7 rounded-2xl sm:rounded-3xl bg-emerald-50/70 dark:bg-emerald-950/30 border-2 border-emerald-400 dark:border-emerald-600 space-y-3 sm:space-y-4 shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-heading font-black text-base sm:text-lg">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Pakai {{ $appName }}</span>
                    </div>
                    <ul class="space-y-2 text-xs sm:text-sm text-zinc-700 dark:text-zinc-200 font-medium">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Catat mutasi cepat dalam 3 detik dari smartphone maupun desktop (PWA).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Fitur Anggaran Proyek khusus untuk kontrol pos belanja momen penting.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>AI Financial Advisor mendiagnosa 4 pilar kesehatan keuangan secara otomatis.</span>
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
                        <span>Berapa biaya untuk menggunakan aplikasi ini?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 1 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Anda dapat mendaftar dan memulai secara gratis untuk menikmati fitur esensial seperti pencatatan mutasi kas multi-dompet, target anggaran dasar, dan ekspor laporan. Untuk kebutuhan pengelolaan proyek tanpa batas dan analisa AI mendalam, kami juga menyediakan opsi upgrade dengan harga yang sangat terjangkau.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 2 ? null : 2)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Apa itu fitur Anggaran Proyek?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 2 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Anggaran Proyek adalah fitur khusus untuk merencanakan pendanaan momen besar (seperti Pernikahan, Liburan, Renovasi Rumah, atau Modal Bisnis) terpisah dari pos rutin bulanan, lengkap dengan checklist rincian pos belanja dan pelacakan target hari H.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 3 ? null : 3)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Bagaimana cara kerja AI Financial Health Advisor?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 3 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Sistem menganalisa 4 pilar utama finansial Anda: Rasio Tabungan (Savings Rate), Cadangan Dana Darurat (Runway), Kepatuhan Batas Anggaran, dan Stabilitas Arus Kas untuk memberikan skor 0–100 serta masukan cerdas dari Gemini AI.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 4 ? null : 4)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Apakah BudgetIn bisa dipasang di smartphone (PWA)?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 4 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Bisa banget! BudgetIn dirancang dengan standar Progressive Web App (PWA). Cukup buka di browser HP Anda dan klik tombol "Install Aplikasi" atau "Add to Home Screen" di Safari/Chrome.
                    </div>
                </div>

                <!-- Q5 -->
                <div class="rounded-2xl glass-card overflow-hidden">
                    <button type="button" @click="active = (active === 5 ? null : 5)"
                        class="w-full p-4 sm:p-5 text-left font-bold text-xs sm:text-sm text-zinc-900 dark:text-white flex justify-between items-center cursor-pointer">
                        <span>Apakah orang lain bisa melihat data keuangan saya?</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400 flex-shrink-0" :class="active === 5 ? 'rotate-180 text-emerald-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 5" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Tidak sama sekali. Setiap akun memiliki ruang data terisolasi (*Tenant Isolation*). Data mutasi, dompet, dan anggaran Anda hanya dapat diakses oleh Anda sendiri.
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
                    class="btn-shine inline-flex items-center justify-center gap-2.5 w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-white text-emerald-700 hover:bg-emerald-50 font-black text-sm sm:text-base shadow-2xl transition-all hover:scale-105">
                    <span>Mulai Coba Sekarang</span>
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
