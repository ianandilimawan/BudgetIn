@extends('admin.layouts.app')

@section('title', 'Settings')

@section('header')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Settings</h1>
            <p class="mt-1 sm:mt-2 text-sm text-gray-500 dark:text-gray-400">Manage your application settings and configurations.</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6" x-data="{
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'general',
        isSubmitting: false,
        submitForm(e) {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
            let form = e.target;
            let formData = new FormData(form);
    
            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Settings updated successfully!', type: 'success' } }));
                    setTimeout(() => {
                        window.location.href = form.action + '?tab=' + this.activeTab;
                    }, 1500);
                } else {
                    this.isSubmitting = false;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Error saving settings.', type: 'error' } }));
                }
            }).catch(error => {
                this.isSubmitting = false;
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'An error occurred.', type: 'error' } }));
            });
        }
    }">

        <!-- Tabs Navigation -->
        <div class="mb-6 sm:mb-8">
            <!-- Mobile Select Menu -->
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" @change="activeTab = $event.target.value; window.history.replaceState(null, null, '?tab=' + $event.target.value)"
                    class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white transition-all text-sm font-medium">
                    <option value="general" :selected="activeTab === 'general'">General Information</option>
                    <option value="email" :selected="activeTab === 'email'">Email / SMTP Configuration</option>
                    <option value="appearance" :selected="activeTab === 'appearance'">Appearance Settings</option>
                </select>
            </div>
            
            <!-- Desktop Tabs -->
            <div class="hidden sm:block border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                <nav class="-mb-px flex gap-8 min-w-max pb-1 px-1" aria-label="Tabs">
                    <button @click="activeTab = 'general'; window.history.replaceState(null, null, '?tab=general')"
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        General
                    </button>
    
                    <button @click="activeTab = 'email'; window.history.replaceState(null, null, '?tab=email')"
                        :class="activeTab === 'email' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Email / SMTP
                    </button>
    
                    <button @click="activeTab = 'appearance'; window.history.replaceState(null, null, '?tab=appearance')"
                        :class="activeTab === 'appearance' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                            </path>
                        </svg>
                        Appearance
                    </button>
                </nav>
            </div>
        </div>

        <form x-data="ajaxForm" @submit.prevent="submit" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
            @submit.prevent="submitForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="active_tab" x-model="activeTab">

            <!-- General Settings Tab -->
            <div x-show="activeTab === 'general'" style="display: none;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl mb-6 overflow-hidden">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-5">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 dark:text-white">General Information
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Basic details about your application.
                            </p>
                        </div>

                        <div class="space-y-8 max-w-4xl">

                            <div>
                                <x-input-floating type="text" name="app_name" label="Application Name *" value="{{ old('app_name', $setting->app_name) }}" required="true" />
                                @error('app_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-5 border border-gray-200 dark:border-gray-700/50">
                                <div class="flex items-start">
                                    <div class="flex items-center h-6">
                                        <input id="maintenance_mode" name="maintenance_mode" type="checkbox" value="1"
                                            {{ old('maintenance_mode', $setting->maintenance_mode) ? 'checked' : '' }}
                                            class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded cursor-pointer transition-colors">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="maintenance_mode"
                                            class="font-semibold text-gray-800 dark:text-gray-200 cursor-pointer">Maintenance
                                            Mode</label>
                                        <p class="text-gray-500 dark:text-gray-400 mt-1">Put the application in maintenance
                                            mode. Non-admins might be locked out.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-8" x-data="{ logoType: '{{ old('logo_type', $setting->logo_type) }}' }">
                                <div class="mb-6">
                                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white mb-2">Brand
                                        Identity</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Configure how your brand appears
                                        across the admin panel.</p>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Logo
                                        Type</label>
                                    <div class="flex flex-wrap items-center gap-4">
                                        <label
                                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                            :class="logoType === 'text' ?
                                                'ring-2 ring-blue-500 border-blue-500 bg-blue-50/50 dark:bg-blue-900/20' :
                                                ''">
                                            <input name="logo_type" type="radio" value="text" x-model="logoType"
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 cursor-pointer">
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Text
                                                Logo</span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                            :class="logoType === 'image' ?
                                                'ring-2 ring-blue-500 border-blue-500 bg-blue-50/50 dark:bg-blue-900/20' :
                                                ''">
                                            <input name="logo_type" type="radio" value="image" x-model="logoType"
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 cursor-pointer">
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Image
                                                Logo</span>
                                        </label>
                                    </div>
                                    @error('logo_type')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-show="logoType === 'text'" style="display: none;"
                                    class="mb-6 transition-all duration-300 ease-in-out">
                                    <x-input-floating type="text" name="logo_text" label="Logo Text" value="{{ old('logo_text', $setting->logo_text) }}" />
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">This text will be displayed in
                                        the sidebar header.</p>
                                    @error('logo_text')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-show="logoType === 'image'" style="display: none;"
                                    class="mb-6 transition-all duration-300 ease-in-out">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Application
                                        Logo Image</label>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6" x-data="{ previewUrl: '{{ $logoUrl }}' }">
                                        <div
                                            class="flex-shrink-0 h-20 w-40 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 overflow-hidden relative group">
                                            <template x-if="previewUrl">
                                                <img :src="previewUrl" alt="Logo" class="h-14 object-contain">
                                            </template>
                                            <template x-if="!previewUrl">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor"
                                                        fill="none" viewBox="0 0 48 48">
                                                        <path
                                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <input type="file" name="app_logo" id="app_logo" accept="image/*"
                                                @change="if($event.target.files.length) previewUrl = URL.createObjectURL($event.target.files[0])"
                                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer transition-colors">
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to
                                                2MB.<br>Recommended ratio 3:1 for best fit in sidebar.</p>
                                        </div>
                                    </div>
                                    @error('app_logo')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Favicon</label>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6" x-data="{ previewUrl: '{{ $faviconUrl }}' }">
                                    <div
                                        class="flex-shrink-0 h-16 w-16 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 overflow-hidden relative">
                                        <template x-if="previewUrl">
                                            <img :src="previewUrl" alt="Favicon" class="h-8 w-8 object-contain">
                                        </template>
                                        <template x-if="!previewUrl">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </template>
                                    </div>
                                    <div>
                                        <input type="file" name="favicon" id="favicon"
                                            accept="image/x-icon,image/png"
                                            @change="if($event.target.files.length) previewUrl = URL.createObjectURL($event.target.files[0])"
                                            class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer transition-colors">
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">ICO or PNG up to 1MB.
                                            Recommended size 32x32px.</p>
                                    </div>
                                </div>
                                @error('favicon')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Email / SMTP Settings Tab -->
            <div x-show="activeTab === 'email'" style="display: none;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl mb-6 overflow-hidden">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-5">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 dark:text-white">Email Configuration
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Configure SMTP settings for sending
                                system emails like OTP codes or password resets.</p>
                        </div>

                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-8 flex flex-col sm:flex-row items-start sm:items-center max-w-4xl gap-3 sm:gap-0">
                            <div class="flex-shrink-0 mt-0.5 sm:mt-0">
                                <svg class="h-5 w-5 text-blue-500 dark:text-blue-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    These settings take priority and will override the application's <code>.env</code> file.
                                    If you leave these fields empty, the system will fall back to using your
                                    <code>.env</code> configurations.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2 max-w-4xl">

                            <div>
                                <x-input-floating type="text" name="smtp_host" label="SMTP Host" value="{{ old('smtp_host', $setting->smtp_host) }}" />
                            </div>

                            <div>
                                <x-input-floating type="text" name="smtp_port" label="SMTP Port" value="{{ old('smtp_port', $setting->smtp_port) }}" />
                            </div>

                            <div>
                                <x-input-floating type="text" name="smtp_username" label="SMTP Username" value="{{ old('smtp_username', $setting->smtp_username) }}" />
                            </div>

                            <div>
                                <x-input-floating type="password" name="smtp_password" label="SMTP Password" value="{{ old('smtp_password', $setting->smtp_password) }}" />
                            </div>

                            <div>
                                <label for="smtp_encryption"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Encryption
                                    Method</label>
                                <select id="smtp_encryption" name="smtp_encryption"
                                    class="block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value=""
                                        {{ old('smtp_encryption', $setting->smtp_encryption) == '' ? 'selected' : '' }}>
                                        None</option>
                                    <option value="tls"
                                        {{ old('smtp_encryption', $setting->smtp_encryption) == 'tls' ? 'selected' : '' }}>
                                        TLS</option>
                                    <option value="ssl"
                                        {{ old('smtp_encryption', $setting->smtp_encryption) == 'ssl' ? 'selected' : '' }}>
                                        SSL</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-8 max-w-4xl">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Sender Details</h4>

                            <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                                <div>
                                    <x-input-floating type="email" name="smtp_from_address" label="From Address" value="{{ old('smtp_from_address', $setting->smtp_from_address) }}" />
                                </div>

                                <div>
                                    <x-input-floating type="text" name="smtp_from_name" label="From Name" value="{{ old('smtp_from_name', $setting->smtp_from_name) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance Settings Tab -->
            <div x-show="activeTab === 'appearance'" style="display: none;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl mb-6 overflow-hidden">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-5">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 dark:text-white">Appearance Settings
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Customize the look and feel of the
                                admin dashboard.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2 max-w-4xl">

                            <div>
                                <label for="theme_default"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Default Theme
                                    Mode</label>
                                <select id="theme_default" name="theme_default"
                                    class="block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="system"
                                        {{ old('theme_default', $setting->theme_default) == 'system' ? 'selected' : '' }}>
                                        System Default (Matches OS)</option>
                                    <option value="light"
                                        {{ old('theme_default', $setting->theme_default) == 'light' ? 'selected' : '' }}>
                                        Light Mode</option>
                                    <option value="dark"
                                        {{ old('theme_default', $setting->theme_default) == 'dark' ? 'selected' : '' }}>
                                        Dark Mode</option>
                                </select>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Users can still toggle the theme
                                    locally using the button in the header.</p>
                            </div>

                            <div>
                                <label for="sidebar_style"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sidebar
                                    Default Layout</label>
                                <select id="sidebar_style" name="sidebar_style"
                                    class="block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="full"
                                        {{ old('sidebar_style', $setting->sidebar_style) == 'full' ? 'selected' : '' }}>
                                        Full / Expanded</option>
                                    <option value="collapsed"
                                        {{ old('sidebar_style', $setting->sidebar_style) == 'collapsed' ? 'selected' : '' }}>
                                        Collapsed / Minimized</option>
                                </select>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Sets the default width state of
                                    the left navigation menu.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <div class="flex justify-end">
                <button type="submit" :disabled="isSubmitting"
                    class="btn btn-primary btn-md w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="loading">
<span x-show="!loading"><svg x-show="!isSubmitting" class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span x-text="isSubmitting ? 'Saving...' : 'Save Configuration'"></span></span>
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
@endsection
