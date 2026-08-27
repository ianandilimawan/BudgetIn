@extends('admin.layouts.app')

@section('title', 'Edit Pengguna Finance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Edit Pengguna Finance</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Ubah data profil dan status keaktifan akun pengguna finance.</p>
        </div>
        <a href="{{ route('admin.finance_users.index') }}"
            class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.finance_users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <x-input-floating type="text" name="name" label="Nama Lengkap" value="{{ old('name', $user->name) }}" required="true" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <x-input-floating type="email" name="email" label="Alamat Email" value="{{ old('email', $user->email) }}" required="true" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Keaktifan (is_active) -->
            <div>
                <label class="block text-sm font-semibold text-zinc-900 dark:text-white mb-2">Status Akun</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('is_active', $user->is_active) == 1 ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300' }}">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active) == 1 ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                            <span class="block text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">User dapat login dan mencatat transaksi</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all {{ old('is_active', $user->is_active) == 0 ? 'border-red-500 bg-red-50/50 dark:bg-red-950/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300' }}">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $user->is_active) == 0 ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500">
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Nonaktif
                            </span>
                            <span class="block text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Login ditolak & diarahkan hubungi admin</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Password Reset Section -->
            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">Ganti Password (Opsional)</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">Biarkan kosong jika tidak ingin mengubah password akun.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-floating type="password" name="password" label="Password Baru" value="" :show-error="true" />
                    </div>
                    <div>
                        <x-input-floating type="password" name="password_confirmation" label="Konfirmasi Password Baru" value="" :show-error="false" />
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <a href="{{ route('admin.finance_users.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all hover:scale-[1.02]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
