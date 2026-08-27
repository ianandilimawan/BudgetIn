<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - {{ $setting->app_name ?? 'Website' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a; /* Fallback slate-900 */
            color: #f3f4f6;
            margin: 0;
            overflow-x: hidden;
        }
        
        .glass-panel {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">

    <!-- Full Screen Abstract Tech Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/images/maintenance_bg.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <!-- Dark overlay to ensure text is always readable -->
        <div class="absolute inset-0 bg-gray-900/60 bg-gradient-to-b from-transparent to-gray-900/90"></div>
    </div>

    <!-- Centered Content -->
    <div class="relative z-10 w-full max-w-2xl px-6">
        <div class="glass-panel rounded-3xl p-10 md:p-16 text-center transform transition-all duration-500 hover:scale-[1.01]">
            
            <!-- Logo / App Name -->
            <div class="mb-10">
                @if($setting && $setting->app_logo)
                    <img src="{{ \App\Services\FileUploadService::getFileUrl($setting->app_logo) }}" alt="Logo" class="h-14 md:h-16 w-auto mx-auto drop-shadow-2xl">
                @elseif($setting && $setting->logo_text)
                    <h2 class="text-2xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-gray-200 to-gray-500 uppercase">
                        {{ $setting->logo_text }}
                    </h2>
                @endif
            </div>

            <!-- Minimalist Typography -->
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl font-semibold text-white tracking-wide">
                    We are Under Maintenance
                </h1>
                
                <p class="text-base md:text-lg text-gray-400 font-normal">
                    Will be Back Soon!
                </p>

                <!-- Status Pill -->
                <div class="pt-6 flex justify-center">
                    <div class="inline-flex items-center space-x-2 bg-gray-900/80 rounded-full px-5 py-2.5 border border-gray-700/50 shadow-inner">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]"></span>
                        </span>
                        <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">System Offline</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
