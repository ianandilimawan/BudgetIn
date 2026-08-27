<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @php
        $settings = \App\Models\Setting::getSettings();
        $appName = 'InForge';
        $appLogo = $settings->app_logo ?? null;
    @endphp

    <title>{{ $appName }} — High-Velocity Laravel 13 Application Platform by Intech Studio</title>

    @if ($settings && $settings->app_favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $settings->app_favicon) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .bg-glow {
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(99, 102, 241, 0.18), transparent);
        }

        .grid-mesh {
            background-size: 32px 32px;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
        }
    </style>
</head>

<body
    class="bg-[#080c16] text-slate-200 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white antialiased relative overflow-x-hidden">
    <!-- Ambient Backdrop -->
    <div class="fixed inset-0 bg-glow pointer-events-none z-0"></div>
    <div class="fixed inset-0 grid-mesh pointer-events-none z-0"></div>

    <!-- Header Navigation -->
    <div class="w-full sticky top-3 sm:top-5 z-50 px-4 sm:px-6">
        <header
            class="max-w-5xl mx-auto rounded-2xl bg-slate-900/80 border border-slate-800/90 backdrop-blur-xl shadow-xl shadow-black/40">
            <div class="px-4 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-4">
                <!-- Brand & Intech Studio Link -->
                <div class="flex items-center gap-3 min-w-0">
                    <a href="/" class="flex items-center gap-2.5 shrink-0 group">
                        @if ($appLogo)
                            <img src="{{ asset('storage/' . $appLogo) }}" alt="{{ $appName }}"
                                class="h-7 sm:h-8 w-auto">
                        @else
                            <div
                                class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        @endif
                        <span
                            class="font-bold text-base sm:text-lg tracking-tight text-white group-hover:text-indigo-300 transition-colors">
                            {{ $appName }}
                        </span>
                    </a>

                    <span class="text-slate-700 select-none">/</span>

                    <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800/80 hover:bg-slate-800 border border-slate-700/60 text-xs font-semibold text-slate-300 hover:text-white transition-all">
                        <span>Intech Studio</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <!-- Right Action -->
                <div>
                    <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-semibold shadow-md shadow-indigo-600/25 transition-all">
                        <span>Visit Website</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </header>
    </div>

    <!-- Hero Section -->
    <main class="relative z-10 flex-1 max-w-5xl mx-auto px-4 sm:px-6 pt-12 sm:pt-20 pb-20 text-center">
        <!-- Badge -->
        <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20 text-indigo-300 text-xs font-semibold mx-auto mb-6 sm:mb-8 transition-all group">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
            <span>Crafted by Intech Studio</span>
            <svg class="w-3 h-3 text-indigo-400 group-hover:translate-x-0.5 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <!-- Main Headline -->
        <h1
            class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.12] mb-5 max-w-4xl mx-auto">
            The High-Velocity Laravel 13 <br class="hidden sm:block">
            <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-indigo-300 bg-clip-text text-transparent">
                Application Platform
            </span>
        </h1>

        <!-- Subtitle -->
        <p
            class="text-sm sm:text-base md:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed mb-8 sm:mb-10 font-normal">
            A battle-tested application framework by <a href="https://intechstudio.id" target="_blank"
                rel="noopener noreferrer"
                class="text-slate-200 hover:text-indigo-300 font-medium underline underline-offset-4 decoration-slate-700">Intech
                Studio</a>. Pre-configured with reactive Livewire PowerGrid tables, Spatie RBAC security, zero-overhead
            background logging, and automated full-stack synthesis.
        </p>

        <!-- CTA Action Buttons -->
        <div
            class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3.5 max-w-md mx-auto mb-14 sm:mb-16">
            <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-0.5 transition-all">
                <span>Explore Intech Studio</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>

            <a href="#features"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white font-semibold text-sm hover:-translate-y-0.5 transition-all">
                <span>Explore Architecture</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </a>
        </div>

        <!-- Terminal Widget -->
        <div x-data="{
            tab: 'scaffold',
            copied: false,
            commands: {
                scaffold: 'php artisan generate:scaffold Product',
                fromTable: 'php artisan generate:scaffold Blog --fromTable --tableName=blog',
                revert: 'php artisan revert:scaffold Product'
            }
        }" class="max-w-3xl mx-auto mb-20 text-left">
            <div
                class="rounded-2xl border border-slate-800 bg-slate-900/90 shadow-2xl backdrop-blur-xl overflow-hidden">
                <!-- Bar -->
                <div
                    class="px-4 py-3 bg-slate-950 border-b border-slate-800/80 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                        </div>
                        <span class="ml-2 text-xs font-mono text-slate-400">inforge-cli</span>
                    </div>

                    <!-- Tabs -->
                    <div class="flex items-center gap-1 bg-slate-900 p-0.5 rounded-lg border border-slate-800">
                        <button type="button" @click="tab = 'scaffold'"
                            :class="tab === 'scaffold' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-slate-200'"
                            class="px-2.5 py-1 rounded-md text-[11px] font-mono font-medium transition-all">
                            Scaffold
                        </button>
                        <button type="button" @click="tab = 'fromTable'"
                            :class="tab === 'fromTable' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-slate-200'"
                            class="px-2.5 py-1 rounded-md text-[11px] font-mono font-medium transition-all">
                            --fromTable
                        </button>
                        <button type="button" @click="tab = 'revert'"
                            :class="tab === 'revert' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-slate-200'"
                            class="px-2.5 py-1 rounded-md text-[11px] font-mono font-medium transition-all">
                            Revert
                        </button>
                    </div>

                    <!-- Copy -->
                    <button type="button"
                        @click="navigator.clipboard.writeText(commands[tab]); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-mono flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied" x-cloak class="text-emerald-400 font-semibold">Copied</span>
                    </button>
                </div>

                <!-- Terminal Body -->
                <div class="p-5 font-mono text-xs sm:text-sm space-y-2 overflow-x-auto">
                    <template x-if="tab === 'scaffold'">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-slate-300">
                                <span class="text-emerald-400 select-none">$</span>
                                <span class="text-indigo-300">php artisan generate:scaffold Product</span>
                            </div>
                            <p class="text-slate-400 text-xs pl-4">[1/6] Created Model, Requests, and Database
                                Migration</p>
                            <p class="text-slate-400 text-xs pl-4">[2/6] Generated Livewire PowerGrid Table Component
                            </p>
                            <p class="text-slate-400 text-xs pl-4">[3/6] Rendered Statically-compiled Blade Views
                                (index, create, edit, show)</p>
                            <p class="text-slate-400 text-xs pl-4">[4/6] Injected Routes into web.php and Menu into
                                config/menu.php</p>
                            <p class="text-slate-400 text-xs pl-4">[5/6] Generated Unit and Feature Test Suite</p>
                            <p class="text-emerald-400 text-xs font-semibold pt-1.5">Synthesis complete in 0.38s (Clean
                                rollback on error enabled)</p>
                        </div>
                    </template>

                    <template x-if="tab === 'fromTable'">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-slate-300">
                                <span class="text-emerald-400 select-none">$</span>
                                <span class="text-indigo-300">php artisan generate:scaffold Blog --fromTable
                                    --tableName=blog</span>
                            </div>
                            <p class="text-slate-400 text-xs pl-4">[Schema] Introspected columns, ENUM types &
                                BelongsTo foreign relations</p>
                            <p class="text-slate-400 text-xs pl-4">[Sync] Synchronized Spatie permissions & Menu routes
                            </p>
                            <p class="text-emerald-400 text-xs font-semibold pt-1.5">Introspection completed without
                                schema rewriting</p>
                        </div>
                    </template>

                    <template x-if="tab === 'revert'">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-slate-300">
                                <span class="text-emerald-400 select-none">$</span>
                                <span class="text-indigo-300">php artisan revert:scaffold Product</span>
                            </div>
                            <p class="text-slate-400 text-xs pl-4">[Cleanup] Removed generated Model, Controllers,
                                PowerGrid, and Views</p>
                            <p class="text-slate-400 text-xs pl-4">[Cleanup] Cleaned menu entries and database
                                permissions</p>
                            <p class="text-emerald-400 text-xs font-semibold pt-1.5">Revert finished cleanly with 0
                                leftover files</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bento Grid Architecture -->
        <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-5 text-left mb-16 pt-4">
            <!-- Bento Card 1 -->
            <div
                class="md:col-span-2 p-6 sm:p-8 rounded-2xl bg-slate-900/50 border border-slate-800/80 hover:border-slate-700 transition-all">
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Automated Full-Stack Code Synthesis</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    A single unified command engine that synthesizes clean, PSR-compliant architecture: Eloquent Models,
                    Form Requests, Statically compiled Blade views, Spatie permissions, and Feature Tests.
                </p>
                <div class="flex flex-wrap gap-2 text-[11px] font-mono text-slate-300">
                    <span class="px-2.5 py-1 rounded-md bg-slate-800/90 border border-slate-700/60">--fromTable</span>
                    <span
                        class="px-2.5 py-1 rounded-md bg-slate-800/90 border border-slate-700/60">--schema=JSON</span>
                    <span class="px-2.5 py-1 rounded-md bg-slate-800/90 border border-slate-700/60">--api</span>
                    <span
                        class="px-2.5 py-1 rounded-md bg-slate-800/90 border border-slate-700/60">--soft-deletes</span>
                    <span class="px-2.5 py-1 rounded-md bg-slate-800/90 border border-slate-700/60">Auto
                        Rollback</span>
                </div>
            </div>

            <!-- Bento Card 2 -->
            <div
                class="p-6 sm:p-8 rounded-2xl bg-slate-900/50 border border-slate-800/80 hover:border-slate-700 transition-all flex flex-col justify-between">
                <div>
                    <div
                        class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Reactive Data & Table Engine</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-4">
                        High-performance reactive datatables powered by Livewire PowerGrid with instant search,
                        multi-column filters, and memory-safe streaming exports.
                    </p>
                </div>
                <div class="text-xs font-mono text-purple-300">
                    Livewire 4 + PowerGrid 6
                </div>
            </div>

            <!-- Bento Card 3 -->
            <div
                class="p-6 sm:p-8 rounded-2xl bg-slate-900/50 border border-slate-800/80 hover:border-slate-700 transition-all flex flex-col justify-between">
                <div>
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Zero-Trust RBAC & Security</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-4">
                        Granular user roles & permissions with automatic constructor middleware injection, brute-force
                        rate limiters, and OWASP-hardened validation.
                    </p>
                </div>
                <div class="text-xs font-mono text-emerald-300">
                    Spatie RBAC Architecture
                </div>
            </div>

            <!-- Bento Card 4 -->
            <div
                class="md:col-span-2 p-6 sm:p-8 rounded-2xl bg-slate-900/50 border border-slate-800/80 hover:border-slate-700 transition-all">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Production-Hardened Core</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Zero-latency async activity logs, streaming server log viewer, automatic WebP media optimization,
                    two-factor OTP authentication, and dynamic SMTP settings.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                        <div class="text-sm font-bold text-indigo-400 font-mono">0ms</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Async Logs</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                        <div class="text-sm font-bold text-emerald-400 font-mono">18 / 18</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Tests Passed</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                        <div class="text-sm font-bold text-purple-400 font-mono">WebP</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Auto Media</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                        <div class="text-sm font-bold text-pink-400 font-mono">2FA OTP</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Secured Auth</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tech Stack Pill Ribbon -->
        <div
            class="pt-8 border-t border-slate-900 flex flex-wrap items-center justify-center gap-2.5 text-xs text-slate-400">
            <span class="text-slate-500 uppercase tracking-widest text-[10px] font-bold mr-2">Built With</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">PHP
                8.3+</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">Laravel
                13</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">Tailwind
                CSS v4</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">Livewire
                4</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">PowerGrid
                v6</span>
            <span class="px-3 py-1 rounded-lg bg-slate-900/80 border border-slate-800 font-mono text-[11px]">Spatie
                Permissions</span>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-[#060910] py-8 px-4 text-center text-xs text-slate-500">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} <strong>{{ $appName }}</strong>. Developed by <a
                    href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                    class="text-slate-300 hover:text-indigo-300 underline underline-offset-2 transition-colors font-medium">Intech
                    Studio</a>.</p>
            <div class="flex items-center gap-6 text-xs text-slate-400">
                <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                    class="hover:text-white transition-colors">Intech Studio ↗</a>
                <a href="https://intechstudio.id" target="_blank" rel="noopener noreferrer"
                    class="hover:text-white transition-colors">Portfolio</a>
            </div>
        </div>
    </footer>
</body>

</html>
