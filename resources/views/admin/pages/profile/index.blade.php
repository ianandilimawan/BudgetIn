@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 pb-6"
     x-data="{
        activeTab: 'profile',
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

        <!-- Mobile & Tablet Segmented Tab Switcher -->
        <div class="mt-4 pt-3.5 border-t border-zinc-100 dark:border-zinc-800/80 grid grid-cols-2 gap-1.5 p-1 bg-zinc-100/80 dark:bg-zinc-800/60 rounded-xl">
            <button type="button" @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-500 dark:text-zinc-400 font-medium hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2 px-3 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Data Profil</span>
            </button>
            <button type="button" @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white font-bold shadow-xs' : 'text-zinc-500 dark:text-zinc-400 font-medium hover:text-zinc-800 dark:hover:text-zinc-200'"
                    class="py-2 px-3 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>Ganti Password</span>
            </button>
        </div>
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

    <!-- Sesi Akun & Logout Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 p-4 sm:p-5 shadow-xs flex items-center justify-between gap-3">
        <div>
            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Sesi Akun</h3>
            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Keluar dari sesi akun Anda di perangkat ini</p>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 sm:py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-bold transition cursor-pointer shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</div>

<script>
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
