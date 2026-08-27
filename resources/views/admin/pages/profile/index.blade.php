@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="space-y-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-lg border border-zinc-100 dark:border-zinc-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <!-- Profile Form Content -->
                <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2 uppercase tracking-wider">Profile Avatar</label>
                            <div class="flex items-center gap-6">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 border-2 border-dashed border-zinc-200 dark:border-zinc-700 shrink-0">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" id="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=18181b&background=f4f4f5" id="avatarPreview" alt="Preview" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-xs text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 dark:file:bg-zinc-800 dark:file:text-zinc-300 dark:hover:file:bg-zinc-700 transition-all cursor-pointer" onchange="previewImage(event)">
                                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 mt-2">JPG, GIF or PNG. Max size of 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-input-floating type="text" name="name" label="Full Name" value="{{ old('name', $user->name) }}" required="true" />
                        </div>

                        <div>
                            <x-input-floating type="email" name="email" label="Email Address" value="{{ old('email', $user->email) }}" required="true" />
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-zinc-100 dark:border-zinc-800/80 flex justify-end">
                        <button type="submit" class="btn btn-primary px-6 py-2" x-bind:disabled="loading">
<span x-show="!loading">Save Changes</span>
                        <span x-show="loading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Form -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-lg border border-zinc-100 dark:border-zinc-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <!-- Security Form Content -->
                <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.profile.password') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-floating type="password" name="current_password" label="Current Password" required="true" />
                        <p id="current_password_hint" class="text-xs mt-1.5 font-medium hidden"></p>
                    </div>

                    <div>
                        <x-input-floating type="password" name="password" label="New Password" required="true" />
                        <div class="mt-2 h-1.5 w-full bg-zinc-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div id="password_strength_bar" class="h-full bg-zinc-400 w-0 transition-all duration-300"></div>
                        </div>
                        <p id="password_strength_text" class="text-[10px] text-zinc-500 mt-1.5 font-medium uppercase tracking-wider"></p>
                    </div>

                    <div>
                        <x-input-floating type="password" name="password_confirmation" label="Confirm New Password" required="true" />
                        <p id="password_match_hint" class="text-xs mt-1.5 font-medium hidden"></p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-zinc-100 dark:border-zinc-800/80">
                        <button type="submit" class="btn btn-primary w-full px-6 py-2" x-bind:disabled="loading">
<span x-show="!loading">Update Password</span>
                        <span x-show="loading" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
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
                    currentPasswordHint.textContent = 'Current password is correct.';
                    currentPasswordHint.className = 'text-xs mt-1.5 font-medium text-green-600 dark:text-green-400';
                } else {
                    currentPasswordHint.textContent = 'Current password does not match.';
                    currentPasswordHint.className = 'text-xs mt-1.5 font-medium text-red-600 dark:text-red-400';
                }
            });
        }, 500);
    });

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
        let color = 'bg-red-500';
        let text = 'Weak';
        
        if (strength <= 2) {
            width = '33%';
            color = 'bg-red-500';
            text = 'Weak';
        } else if (strength === 3 || strength === 4) {
            width = '66%';
            color = 'bg-yellow-500';
            text = 'Medium';
        } else if (strength >= 5) {
            width = '100%';
            color = 'bg-green-500';
            text = 'Strong';
        }
        
        strengthBar.style.width = width;
        strengthBar.className = `h-full ${color} transition-all duration-300`;
        strengthText.textContent = text;
        
        checkMatch();
    });

    confirmInput.addEventListener('input', checkMatch);

    function checkMatch() {
        const val1 = passwordInput.value;
        const val2 = confirmInput.value;
        
        if (!val2) {
            matchHint.classList.add('hidden');
            return;
        }
        
        matchHint.classList.remove('hidden');
        if (val1 === val2) {
            matchHint.textContent = 'Passwords match.';
            matchHint.className = 'text-xs mt-1.5 font-medium text-green-600 dark:text-green-400';
        } else {
            matchHint.textContent = 'Passwords do not match.';
            matchHint.className = 'text-xs mt-1.5 font-medium text-red-600 dark:text-red-400';
        }
    }
</script>
@endsection
