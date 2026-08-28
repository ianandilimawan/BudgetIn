@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-base sm:text-lg md:text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Profil Akun Saya</h1>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    {{ Auth::user()->roles->first()->name ?? 'Pengguna' }}
                </span>
            </div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola informasi identitas, foto profil, dan kata sandi keamanan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        <!-- 1. Profile Data Card -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
                <div class="p-3.5 sm:p-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2 bg-zinc-50/50 dark:bg-zinc-800/30">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Informasi Identitas</h2>
                        <p class="text-[10px] text-zinc-400">Data akun dan foto profil</p>
                    </div>
                </div>

                <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider mb-2">Foto Profil (Avatar)</label>
                        <div class="flex items-center gap-3.5 sm:gap-5">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 border-2 border-dashed border-zinc-200 dark:border-zinc-700 flex-shrink-0 shadow-2xs">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" id="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=18181b&background=f4f4f5" id="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <input type="file" name="avatar" id="avatar" accept="image/*"
                                       class="block w-full text-xs text-zinc-500 dark:text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-950/60 dark:file:text-emerald-300 dark:hover:file:bg-emerald-900/60 transition-all cursor-pointer"
                                       onchange="previewImage(event)">
                                <p class="text-[10px] text-zinc-400 mt-1.5">Format JPG, PNG, atau WebP. Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <x-input-floating type="text" name="name" label="Nama Lengkap" value="{{ old('name', $user->name) }}" required="true" />
                        </div>

                        <div>
                            <x-input-floating type="email" name="email" label="Alamat Email" value="{{ old('email', $user->email) }}" required="true" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/20 active:scale-95 transition-all cursor-pointer"
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

        <!-- 2. Security / Change Password Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-xs overflow-hidden">
                <div class="p-3.5 sm:p-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2 bg-zinc-50/50 dark:bg-zinc-800/30">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-white">Keamanan Akun</h2>
                        <p class="text-[10px] text-zinc-400">Ganti kata sandi</p>
                    </div>
                </div>

                <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.password') }}" method="POST" class="p-4 sm:p-5 space-y-3.5 sm:space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-floating type="password" name="current_password" label="Kata Sandi Saat Ini" required="true" />
                        <p id="current_password_hint" class="text-xs mt-1 font-medium hidden"></p>
                    </div>

                    <div>
                        <x-input-floating type="password" name="password" label="Kata Sandi Baru" required="true" />
                        <div class="mt-2 h-1.5 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div id="password_strength_bar" class="h-full bg-zinc-400 w-0 transition-all duration-300"></div>
                        </div>
                        <p id="password_strength_text" class="text-[10px] text-zinc-400 mt-1 font-semibold uppercase tracking-wider"></p>
                    </div>

                    <div>
                        <x-input-floating type="password" name="password_confirmation" label="Konfirmasi Kata Sandi Baru" required="true" />
                        <p id="password_match_hint" class="text-xs mt-1 font-medium hidden"></p>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 dark:border-zinc-800">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 active:scale-95 transition-all cursor-pointer"
                                x-bind:disabled="loading">
                            <span x-show="!loading">Perbarui Kata Sandi</span>
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
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

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
                });
            }, 500);
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
