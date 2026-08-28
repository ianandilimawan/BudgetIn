@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-4 sm:space-y-6 pb-6"
     x-data="{
        activeTab: 'health',
        showCurrentPw: false,
        showNewPw: false,
        showConfirmPw: false,
        avatarPreview: '{{ $user->avatar ? Storage::url($user->avatar) : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&color=18181b&background=f4f4f5" }}',
        previewImage(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.avatarPreview = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
     }">

    <!-- Header & User Identity Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 shadow-xs">
        <div class="flex items-center gap-3.5 sm:gap-4">
            <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden bg-zinc-100 dark:bg-zinc-800 border-2 border-emerald-500/30 dark:border-emerald-500/40 flex-shrink-0 shadow-xs">
                <img :src="avatarPreview" alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate mt-0.5">{{ $user->email }}</p>
            </div>
        </div>

        <!-- Mobile & Desktop Segmented 3-Tab Switcher -->
        <div class="mt-4 pt-3.5 border-t border-zinc-100 dark:border-zinc-800/80 grid grid-cols-3 gap-1 sm:gap-1.5 p-1 bg-zinc-100/80 dark:bg-zinc-800/60 rounded-xl">
            <button type="button" @click="activeTab = 'health'"
                    :class="activeTab === 'health' ? 'bg-white dark:bg-zinc-700 text-emerald-700 dark:text-emerald-300 font-bold shadow-xs' : 'text-zinc-500 dark:text-zinc-400 font-medium hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2 px-1 sm:px-3 rounded-lg text-xs transition-all flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-1.5 cursor-pointer">
                <span class="text-sm">✨</span>
                <span class="text-[11px] sm:text-xs font-semibold sm:font-bold whitespace-nowrap">
                    <span class="sm:hidden">Kesehatan</span>
                    <span class="hidden sm:inline">Kesehatan & AI</span>
                </span>
            </button>
            <button type="button" @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-500 dark:text-zinc-400 font-medium hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2 px-1 sm:px-3 rounded-lg text-xs transition-all flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[11px] sm:text-xs font-semibold sm:font-bold whitespace-nowrap">
                    <span class="sm:hidden">Profil</span>
                    <span class="hidden sm:inline">Data Diri</span>
                </span>
            </button>
            <button type="button" @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-500 dark:text-zinc-400 font-medium hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2 px-1 sm:px-3 rounded-lg text-xs transition-all flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span class="text-[11px] sm:text-xs font-semibold sm:font-bold whitespace-nowrap">Password</span>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 1. TAB: Skor Kesehatan Keuangan & Gemini AI Advisor -->
    <!-- ============================================================== -->
    <div x-show="activeTab === 'health'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        @if(isset($financialHealth) && isset($aiInsights))
        <div x-data="financialAiAdvisor({
                score: {{ $financialHealth['overall_score'] }},
                status: '{{ $financialHealth['status_label'] }}',
                statusColor: '{{ $financialHealth['status_color'] }}',
                month: {{ $financialHealth['month'] }},
                year: {{ $financialHealth['year'] }},
                summary: {{ json_encode($aiInsights['summary']) }},
                cashflowInsight: {{ json_encode($aiInsights['cashflow_insight']) }},
                budgetWarning: {{ json_encode($aiInsights['budget_warning']) }},
                actionableTip: {{ json_encode($aiInsights['actionable_tip']) }},
                engine: '{{ $aiInsights['engine'] }}',
                generatedAt: '{{ $aiInsights['generated_at'] }}'
             })"
             class="space-y-4 sm:space-y-6">

            <!-- Health Score Gauge Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-3.5 sm:p-5 shadow-xs relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/10 dark:bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white truncate">Skor Kesehatan Keuangan</h2>
                            <p class="text-[10px] text-zinc-400 truncate">Periode {{ $financialHealth['month_name'] }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full flex-shrink-0"
                          :class="{
                              'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300': statusColor === 'emerald',
                              'bg-teal-100 text-teal-800 dark:bg-teal-900/60 dark:text-teal-300': statusColor === 'teal',
                              'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300': statusColor === 'amber',
                              'bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-300': statusColor === 'rose',
                          }" x-text="status">
                        {{ $financialHealth['status_label'] }}
                    </span>
                </div>

                <!-- Big Score Presentation (Horizontal Compact on Mobile) -->
                <div class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl bg-zinc-50/80 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800 relative z-10">
                    <div class="relative w-15 h-15 sm:w-18 sm:h-18 flex items-center justify-center flex-shrink-0">
                        <svg class="w-full h-full -rotate-90 transform" viewBox="0 0 36 36">
                            <path class="text-zinc-200 dark:text-zinc-700" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path stroke-dasharray="100, 100" :stroke-dasharray="`${score}, 100`" 
                                  :class="{
                                      'text-emerald-500': statusColor === 'emerald',
                                      'text-teal-500': statusColor === 'teal',
                                      'text-amber-500': statusColor === 'amber',
                                      'text-rose-500': statusColor === 'rose',
                                  }"
                                  class="transition-all duration-1000 ease-out" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-base sm:text-xl font-extrabold text-zinc-900 dark:text-white" x-text="score">{{ $financialHealth['overall_score'] }}</span>
                            <span class="text-[8px] sm:text-[9px] text-zinc-400 -mt-1">/100</span>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-zinc-700 dark:text-zinc-300 leading-relaxed font-medium">
                            {{ $financialHealth['status_description'] }}
                        </p>
                    </div>
                </div>

                <!-- 4 Pillars Grid -->
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2 relative z-10">
                    @foreach($financialHealth['pillars'] as $key => $pillar)
                    <div class="p-2 sm:p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center justify-between text-[10px] text-zinc-400 mb-0.5">
                            <span class="truncate">{{ $pillar['name'] }}</span>
                            <span class="font-bold text-[9px] {{ $pillar['is_healthy'] ? 'text-emerald-500' : 'text-amber-500' }}">{{ $pillar['score'] }}/100</span>
                        </div>
                        <p class="text-xs sm:text-sm font-extrabold text-zinc-800 dark:text-zinc-200 truncate">{{ $pillar['value_formatted'] }}</p>
                        <p class="text-[9px] text-zinc-400 mt-0.5 truncate">Target: {{ $pillar['target'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Gemini AI Advisor Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-3.5 sm:p-5 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white truncate">AI Financial Insights</h2>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[8px] sm:text-[9px] font-bold"
                                      :class="engine === 'gemini' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300'">
                                    <span class="w-1 h-1 rounded-full" :class="engine === 'gemini' ? 'bg-indigo-500 animate-pulse' : 'bg-emerald-500'"></span>
                                    <span x-text="engine === 'gemini' ? 'Gemini' : 'Smart AI'"></span>
                                </span>
                            </div>
                            <p class="text-[10px] text-zinc-400 truncate">Rekomendasi strategi kas</p>
                        </div>
                    </div>

                    <button type="button" @click="refreshAi()" :disabled="loading"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-[10px] sm:text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:text-emerald-600 dark:hover:text-emerald-400 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition cursor-pointer disabled:opacity-50 flex-shrink-0 shadow-2xs">
                        <svg class="w-3 h-3" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span x-text="loading ? 'Analisis...' : 'Analisis Ulang'">Analisis Ulang</span>
                    </button>
                </div>

                <!-- AI Summary Quote -->
                <div class="p-3 rounded-xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-indigo-500/10 border border-emerald-500/20 mb-3">
                    <p class="text-xs font-medium text-zinc-800 dark:text-zinc-200 leading-relaxed italic" x-text="summary">
                        "{{ $aiInsights['summary'] }}"
                    </p>
                </div>

                <!-- Structured 3 Cards -->
                <div class="space-y-2">
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300 flex items-center justify-center flex-shrink-0 text-xs mt-0.5">
                            💰
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white">Arus Kas & Tabungan</p>
                            <p class="text-zinc-600 dark:text-zinc-300 text-xs leading-relaxed mt-0.5" x-text="cashflowInsight">
                                {{ $aiInsights['cashflow_insight'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300 flex items-center justify-center flex-shrink-0 text-xs mt-0.5">
                            ⚠️
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white">Catatan Pos Anggaran</p>
                            <p class="text-zinc-600 dark:text-zinc-300 text-xs leading-relaxed mt-0.5" x-text="budgetWarning">
                                {{ $aiInsights['budget_warning'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/40">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300 flex items-center justify-center flex-shrink-0 text-xs mt-0.5">
                            💡
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-zinc-900 dark:text-white">Rekomendasi Aksi Cerdas</p>
                            <p class="text-zinc-600 dark:text-zinc-300 text-xs leading-relaxed mt-0.5" x-text="actionableTip">
                                {{ $aiInsights['actionable_tip'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-[10px] text-zinc-400">
                    <span>Dihitung: <span x-text="generatedAt">{{ $aiInsights['generated_at'] }}</span></span>
                    <span class="hidden sm:inline">Diperbarui otomatis oleh AI Advisor</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- 1. TAB: Data Profil -->
    <div x-show="activeTab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2.5 bg-zinc-50/50 dark:bg-zinc-800/30">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Informasi Data Diri</h2>
                    <p class="text-[11px] text-zinc-400">Ubah nama, email, dan foto profil Anda</p>
                </div>
            </div>

            <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Avatar Upload Section -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-2">Foto Profil</label>
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200/80 dark:border-zinc-700/80">
                        <div class="w-14 h-14 rounded-full overflow-hidden bg-zinc-200 dark:bg-zinc-700 flex-shrink-0 border border-zinc-300 dark:border-zinc-600">
                            <img :src="avatarPreview" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <label for="avatarInput" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg text-xs font-semibold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700 cursor-pointer transition shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Pilih Foto Baru</span>
                            </label>
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden" @change="previewImage($event)">
                            <p class="text-[10px] text-zinc-400 mt-1">Mendukung JPG, PNG, atau WebP (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs placeholder:font-normal placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition"
                               placeholder="Masukkan nama lengkap Anda">
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs placeholder:font-normal placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition"
                               placeholder="nama@email.com">
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/20 transition-all cursor-pointer"
                            x-bind:disabled="loading">
                        <span x-show="!loading">Simpan Perubahan</span>
                        <span x-show="loading" style="display: none;" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. TAB: Ganti Password -->
    <div x-show="activeTab === 'security'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2.5 bg-zinc-50/50 dark:bg-zinc-800/30">
                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">Keamanan & Kata Sandi</h2>
                    <p class="text-[11px] text-zinc-400">Perbarui kata sandi akun Anda secara berkala</p>
                </div>
            </div>

            <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.password') }}" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password Field -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input :type="showCurrentPw ? 'text' : 'password'" name="current_password" id="current_password" required
                               class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs placeholder:font-normal placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                               placeholder="Masukkan kata sandi lama">
                        <button type="button" @click="showCurrentPw = !showCurrentPw"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!showCurrentPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showCurrentPw" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    <p id="current_password_hint" class="text-xs mt-1 font-medium hidden"></p>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <input :type="showNewPw ? 'text' : 'password'" name="password" id="password" required
                               class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs placeholder:font-normal placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="showNewPw = !showNewPw"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!showNewPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showNewPw" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="mt-2 space-y-1">
                        <div class="h-1.5 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div id="password_strength_bar" class="h-full bg-zinc-400 w-0 transition-all duration-300"></div>
                        </div>
                        <p id="password_strength_text" class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider"></p>
                    </div>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input :type="showConfirmPw ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                               class="w-full pl-9 pr-10 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 text-zinc-900 dark:text-white text-xs sm:text-sm font-medium placeholder:text-xs placeholder:font-normal placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                               placeholder="Ulangi kata sandi baru">
                        <button type="button" @click="showConfirmPw = !showConfirmPw"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                            <svg x-show="!showConfirmPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showConfirmPw" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    <p id="password_match_hint" class="text-xs mt-1 font-medium hidden"></p>
                </div>

                <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all cursor-pointer"
                            x-bind:disabled="loading">
                        <span x-show="!loading">Perbarui Kata Sandi</span>
                        <span x-show="loading" style="display: none;" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memproses...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Logout Action Card -->
    <!-- ============================================================== -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-rose-200/60 dark:border-rose-900/40 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div class="flex items-center gap-3 text-left">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Keluar dari Akun</h3>
                <p class="text-[11px] text-zinc-400">Akhiri sesi login Anda di perangkat ini secara aman</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}" class="w-full sm:w-auto flex-shrink-0">
            @csrf
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 font-bold text-xs border border-rose-200/80 dark:border-rose-800/80 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</div>

<script>
    // Financial AI Advisor Alpine Component
    document.addEventListener('alpine:init', () => {
        Alpine.data('financialAiAdvisor', (initialData) => ({
            score: initialData.score,
            status: initialData.status,
            statusColor: initialData.statusColor,
            month: initialData.month,
            year: initialData.year,
            summary: initialData.summary,
            cashflowInsight: initialData.cashflowInsight,
            budgetWarning: initialData.budgetWarning,
            actionableTip: initialData.actionableTip,
            engine: initialData.engine,
            generatedAt: initialData.generatedAt,
            loading: false,
            async refreshAi() {
                this.loading = true;
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const response = await fetch('{{ route('admin.financial_health.refresh') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            month: this.month,
                            year: this.year,
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.score = data.financial_health.overall_score;
                        this.status = data.financial_health.status_label;
                        this.statusColor = data.financial_health.status_color;
                        this.summary = data.ai_insights.summary;
                        this.cashflowInsight = data.ai_insights.cashflow_insight;
                        this.budgetWarning = data.ai_insights.budget_warning;
                        this.actionableTip = data.ai_insights.actionable_tip;
                        this.engine = data.ai_insights.engine;
                        this.generatedAt = data.ai_insights.generated_at;
                        if (typeof showToast === 'function') {
                            showToast('Analisis AI berhasil diperbarui!', 'success');
                        }
                    } else {
                        throw new Error(data.message || 'Gagal memperbarui analisis');
                    }
                } catch (err) {
                    console.error(err);
                    if (typeof showToast === 'function') {
                        showToast('Gagal memperbarui analisis AI.', 'error');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
    // Current Password AJAX Check
    let currentPasswordTimeout;
    const currentPasswordInput = document.getElementById('current_password');
    const currentPasswordHint = document.getElementById('current_password_hint');

    if (currentPasswordInput) {
        currentPasswordInput.addEventListener('input', function() {
            clearTimeout(currentPasswordTimeout);
            const val = this.value;
            if (!val) {
                currentPasswordHint.classList.add('hidden');
                return;
            }

            currentPasswordTimeout = setTimeout(() => {
                fetch('{{ route('admin.profile.check-password') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ current_password: val })
                })
                .then(res => res.json())
                .then(data => {
                    currentPasswordHint.classList.remove('hidden');
                    if (data.match) {
                        currentPasswordHint.textContent = 'Kata sandi saat ini sesuai.';
                        currentPasswordHint.className = 'text-xs mt-1 font-medium text-emerald-600 dark:text-emerald-400';
                    } else {
                        currentPasswordHint.textContent = 'Kata sandi saat ini tidak cocok.';
                        currentPasswordHint.className = 'text-xs mt-1 font-medium text-rose-600 dark:text-rose-400';
                    }
                })
                .catch(() => {});
            }, 400);
        });
    }

    // Password Strength & Match Check
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password_strength_bar');
    const strengthText = document.getElementById('password_strength_text');
    const matchHint = document.getElementById('password_match_hint');

    function checkStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!%*?_.-]+/)) strength++;
        return strength;
    }

    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            if (!val) {
                strengthBar.style.width = '0%';
                strengthText.textContent = '';
                checkMatch();
                return;
            }

            const strength = checkStrength(val);
            let width = '0%';
            let color = 'bg-rose-500';
            let text = 'Lemah';

            if (strength <= 2) {
                width = '33%';
                color = 'bg-rose-500';
                text = 'Lemah';
            } else if (strength === 3 || strength === 4) {
                width = '66%';
                color = 'bg-amber-500';
                text = 'Sedang';
            } else if (strength >= 5) {
                width = '100%';
                color = 'bg-emerald-500';
                text = 'Kuat';
            }

            strengthBar.style.width = width;
            strengthBar.className = `h-full ${color} transition-all duration-300`;
            strengthText.textContent = 'Kekuatan: ' + text;

            checkMatch();
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', checkMatch);
    }

    function checkMatch() {
        if (!passwordInput || !confirmInput || !matchHint) return;
        const val1 = passwordInput.value;
        const val2 = confirmInput.value;

        if (!val2) {
            matchHint.classList.add('hidden');
            return;
        }

        matchHint.classList.remove('hidden');
        if (val1 === val2) {
            matchHint.textContent = 'Kata sandi konfirmasi cocok.';
            matchHint.className = 'text-xs mt-1 font-medium text-emerald-600 dark:text-emerald-400';
        } else {
            matchHint.textContent = 'Konfirmasi kata sandi belum sama.';
            matchHint.className = 'text-xs mt-1 font-medium text-rose-600 dark:text-rose-400';
        }
    }
</script>
@endsection
