<!-- Interactive Onboarding Tour Component (Spotlight Desktop, Mini Floating Pill with Expandable Detail on Mobile) -->
<div x-data="appOnboardingTour()"
     x-init="initTour()"
     @start-onboarding-tour.window="startTour()"
     x-cloak>

    <!-- Overlay Backdrop for Both Desktop & Mobile -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] pointer-events-auto overflow-hidden">
        
        <!-- Dark Backdrop for Welcome / Finish (Non-target) -->
        <div x-show="!hasTarget"
             class="fixed inset-0 bg-zinc-950/85 transition-opacity duration-200"></div>

        <!-- Crisp Cutout Spotlight Highlight (Pixel-Perfect, 100% Sharp, Active on Both Mobile & Desktop) -->
        <div x-show="hasTarget"
             :style="`top: ${targetRect.top}px; left: ${targetRect.left}px; width: ${targetRect.width}px; height: ${targetRect.height}px; border-radius: ${targetRect.radius}px;`"
             class="fixed pointer-events-none ring-4 ring-emerald-500 ring-offset-2 ring-offset-transparent shadow-[0_0_0_9999px_rgba(9,9,11,0.85)] shadow-emerald-500/30 transition-all duration-150 ease-out z-[101]">
            <!-- Animated Pulse Ping Indicator -->
            <span class="absolute -top-3 -right-3 flex h-6 w-6 sm:h-7 sm:w-7 z-10">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-80"></span>
                <span class="relative inline-flex rounded-full h-6 w-6 sm:h-7 sm:w-7 bg-emerald-500 text-white text-[10px] sm:text-xs font-black items-center justify-center shadow-lg ring-2 ring-white dark:ring-zinc-900" x-text="currentStep"></span>
            </span>
        </div>

        <!-- ============================================================== -->
        <!-- 1. DESKTOP POPOVER TOOLTIP (>= 768px - Exact and Untouched) -->
        <!-- ============================================================== -->
        <template x-if="!isMobile">
            <div class="fixed inset-0 z-[102] pointer-events-none flex"
                 :class="!hasTarget ? 'items-center justify-center p-4' : 'items-start justify-start p-4'">
                
                <div x-show="isOpen"
                     x-transition:enter="transition ease-out duration-200 transform"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     :style="hasTarget ? desktopTooltipStyle : ''"
                     class="pointer-events-auto w-full max-w-md bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-2xl p-5 sm:p-6 transition-all duration-200 relative overflow-hidden">
                    
                    <!-- Background Accent Glow -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 dark:bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-3 mb-2.5 relative z-10">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 text-base flex-shrink-0">
                                <template x-if="currentStepData.icon === 'welcome'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'bank'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></template>
                                <template x-if="currentStepData.icon === 'chart'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'project'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></template>
                                <template x-if="currentStepData.icon === 'catat'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></template>
                                <template x-if="currentStepData.icon === 'export'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'finish'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></template>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 inline-block"
                                      x-text="currentStep === 0 ? 'Panduan Santai' : (currentStep === totalSteps ? 'Selesai' : `Langkah ${currentStep} dari ${totalSteps - 1}`)">
                                </span>
                                <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mt-0.5 leading-snug truncate" x-text="currentStepData.title"></h3>
                            </div>
                        </div>

                        <button type="button" @click="closeTour(true)" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer flex-shrink-0" title="Tutup Panduan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 my-2.5 space-y-1.5">
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400" x-text="currentStepData.subtitle"></p>
                        <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed" x-text="currentStepData.content"></p>
                    </div>

                    <!-- Progress Dots -->
                    <div class="flex items-center gap-1.5 my-3.5">
                        <template x-for="(step, idx) in steps" :key="idx">
                            <div class="h-1.5 rounded-full transition-all duration-300"
                                 :class="currentStep === idx ? 'w-6 bg-emerald-500' : (idx < currentStep ? 'w-2 bg-emerald-300 dark:bg-emerald-800' : 'w-2 bg-zinc-200 dark:bg-zinc-800')">
                            </div>
                        </template>
                    </div>

                    <!-- Footer Controls -->
                    <div class="flex items-center justify-between gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800 relative z-10">
                        <button type="button" 
                                x-show="currentStep > 0"
                                @click="prevStep()" 
                                class="px-3.5 py-2 rounded-xl text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition cursor-pointer">
                            ← Kembali
                        </button>

                        <button type="button" 
                                x-show="currentStep === 0"
                                @click="closeTour(true)" 
                                class="text-xs font-medium text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition cursor-pointer px-2">
                            Lewati
                        </button>

                        <div class="flex items-center gap-2 ml-auto">
                            <button type="button" 
                                    x-show="currentStep < totalSteps"
                                    @click="nextStep()" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition shadow-md shadow-emerald-500/20 active:scale-95 cursor-pointer">
                                <span x-text="currentStep === 0 ? 'Yuk Mulai →' : 'Lanjut →'"></span>
                            </button>

                            <button type="button" 
                                    x-show="currentStep === totalSteps"
                                    @click="closeTour(true)" 
                                    class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition shadow-lg shadow-emerald-500/30 active:scale-95 cursor-pointer">
                                <span>Mulai Eksplorasi</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- ============================================================== -->
        <!-- 2. MOBILE MINI FLOATING PILL BAR (< 768px - Expandable on Tap) -->
        <!-- ============================================================== -->
        <template x-if="isMobile">
            <div class="fixed inset-0 z-[102] pointer-events-none flex"
                 :class="!hasTarget ? 'items-center justify-center p-4' : (mobilePillPosition === 'top' ? 'items-start justify-center p-3 pt-3' : 'items-end justify-center p-3 pb-3')">
                
                <!-- If Welcome or Finish on Mobile: Clean Card supporting Light/Dark -->
                <div x-show="!hasTarget"
                     x-transition:enter="transition ease-out duration-200 transform"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="pointer-events-auto w-full max-w-xs bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white rounded-3xl border border-zinc-200/80 dark:border-zinc-700 shadow-2xl p-5 text-center relative overflow-hidden">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center text-xl mx-auto mb-2.5 shadow-md shadow-emerald-500/30">
                        <template x-if="currentStepData.icon === 'welcome'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></template>
                        <template x-if="currentStepData.icon === 'finish'"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></template>
                    </div>
                    <h3 class="text-sm font-extrabold text-zinc-900 dark:text-white" x-text="currentStepData.title"></h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-300 mt-1 mb-4 leading-relaxed" x-text="currentStepData.content"></p>
                    
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" x-show="currentStep === 0" @click="closeTour(true)" class="px-3 py-1.5 rounded-xl text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition cursor-pointer">Lewati</button>
                        <button type="button" @click="nextStep()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 transition shadow-md shadow-emerald-500/30 cursor-pointer active:scale-95">
                            <span x-text="currentStep === 0 ? 'Yuk Intip' : 'Mulai Eksplorasi'"></span>
                        </button>
                    </div>
                </div>

                <!-- If Highlighting Component on Mobile: Ultra-Sleek Expandable Floating Pill Bar (Light & Dark Compatible) -->
                <div x-show="hasTarget"
                     x-transition:enter="transition ease-out duration-200 transform"
                     x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     class="pointer-events-auto w-full max-w-sm bg-white/95 dark:bg-zinc-900/95 text-zinc-900 dark:text-white backdrop-blur-md rounded-2xl border border-zinc-200/90 dark:border-zinc-700/80 shadow-2xl p-2.5 px-3 transition-all duration-200">
                    
                    <!-- Top Compact Row -->
                    <div class="flex items-center justify-between gap-2">
                        <!-- Left: Mini Icon & Crisp Label (Tap to toggle full explanation) -->
                        <div @click="isExpanded = !isExpanded" class="flex items-center gap-2 min-w-0 flex-1 cursor-pointer select-none" title="Klik untuk baca lengkap">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center text-sm flex-shrink-0 shadow-xs">
                                <template x-if="currentStepData.icon === 'welcome'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'bank'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></template>
                                <template x-if="currentStepData.icon === 'chart'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'project'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></template>
                                <template x-if="currentStepData.icon === 'catat'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></template>
                                <template x-if="currentStepData.icon === 'export'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></template>
                                <template x-if="currentStepData.icon === 'finish'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></template>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider" x-text="`${currentStep}/${totalSteps-1}`"></span>
                                    <h4 class="text-xs font-bold text-zinc-900 dark:text-white truncate" x-text="currentStepData.mobileShortTitle"></h4>
                                </div>
                                <div class="flex items-center gap-1">
                                    <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-medium truncate" x-text="currentStepData.mobileShortDesc"></p>
                                    <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center flex-shrink-0" x-text="isExpanded ? '▴ Tutup' : '▾ Detail'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Compact Control Buttons -->
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button type="button" x-show="currentStep > 0" @click="prevStep()" class="w-7 h-7 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 flex items-center justify-center text-xs font-bold transition cursor-pointer" title="Sebelumnya">
                                ‹
                            </button>
                            <button type="button" @click="nextStep()" class="h-7 px-2.5 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white flex items-center justify-center text-xs font-bold transition shadow-xs cursor-pointer active:scale-95" title="Lanjut">
                                <span x-text="currentStep === totalSteps ? 'Selesai' : 'Lanjut ›'"></span>
                            </button>
                            <button type="button" @click="closeTour(true)" class="w-6 h-6 rounded-lg text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 flex items-center justify-center text-xs transition cursor-pointer" title="Tutup">
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Expandable Full Text Drawer (Appears smoothly when tapped) -->
                    <div x-show="isExpanded"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-2 pt-2 border-t border-zinc-100 dark:border-zinc-800 text-left space-y-1">
                        <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400" x-text="currentStepData.subtitle"></p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed" x-text="currentStepData.content"></p>
                    </div>
                </div>

            </div>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appOnboardingTour', () => ({
        isOpen: false,
        isExpanded: false,
        currentStep: 0,
        targetRect: { top: 0, left: 0, width: 0, height: 0, radius: 16 },
        hasTarget: false,
        isMobile: window.innerWidth < 768,
        mobilePillPosition: 'bottom', // 'top' or 'bottom'
        steps: [
            {
                id: 'welcome',
                title: 'Halo Sobat BudgetIn!',
                subtitle: 'Yuk kenalan bentar, 1 menit aja!',
                icon: 'welcome',
                mobileShortTitle: 'Halo Sobat BudgetIn!',
                mobileShortDesc: 'Kenalan bentar sama fitur keren di sini!',
                target: null,
                content: 'Biar ga pusing ngatur duit dan boncos pas akhir bulan, yuk intip fitur-fitur keren di BudgetIn. Cepat & gampang banget kok!',
            },
            {
                id: 'accounts',
                title: '1. Pantau Semua Dompet & Kas',
                subtitle: 'Bank, e-wallet, sampe kas jadi satu',
                icon: 'bank',
                mobileShortTitle: 'Pantau Semua Dompet',
                mobileShortDesc: 'BCA, GoPay & kas kepantau barengan',
                target: '#tour-accounts',
                content: 'Mau BCA, Mandiri, GoPay, OVO, atau uang tunai di dompet, semuanya bisa kamu pantau barengan di sini tanpa ribet buka banyak aplikasi!',
            },
            {
                id: 'budget-planner',
                title: '2. Pasang Rem Belanja',
                subtitle: 'Jatah pos jajan biar saldo ga jebol',
                icon: 'chart',
                mobileShortTitle: 'Pasang Rem Belanja',
                mobileShortDesc: 'Batasin pos jajan biar saldo ga jebol',
                target: '#tour-budget-planner',
                content: 'Tentukan batas jajan makan, nongkrong, atau belanja bulanan. Begitu mendekati batas, sistem bakal langsung kasih lampu kuning biar kamu ngerem!',
            },
            {
                id: 'budget-projects',
                title: '3. Anggaran Acara & Proyek Impian',
                subtitle: 'Pernikahan, liburan, & renovasi terpantau rapi',
                icon: 'project',
                mobileShortTitle: 'Proyek & Acara Impian',
                mobileShortDesc: 'Pagu nikahan & liburan terpisah rapi',
                target: '#tour-budget-projects',
                content: 'Mau nikahan 50jt, liburan ke Jepang, atau renovasi rumah? Bikin pos rincian belanja (dekorasi, katering, tiket) biar budget khusus ga kecampur sama uang bulanan!',
            },
            {
                id: 'quick-catat',
                title: '4. Catat Instan Sekali Tap',
                subtitle: 'Keluar masuk duit langsung kecatat',
                icon: 'catat',
                mobileShortTitle: 'Catat Sekali Tap',
                mobileShortDesc: 'Tinggal tap tombol hijau, beres 5 detik!',
                target: '#tour-quick-catat',
                content: 'Habis jajan atau dapet transferan? Tinggal tap tombol hijau ini, masukin nominal, upload foto nota kalau ada, dan beres dalam 5 detik!',
            },
            {
                id: 'filter-export',
                title: '5. Rekap & Download Excel',
                subtitle: 'Butuh laporan? Sekali klik langsung beres',
                icon: 'export',
                mobileShortTitle: 'Rekap & Unduh Excel',
                mobileShortDesc: 'Sekali klik jadi file spreadsheet rapi',
                target: '#tour-filter-export',
                content: 'Mau cek mutasi bulan lalu atau download pembukuan lengkap ke file Excel? Tinggal pilih periode dan klik ekspor, langsung siap pakai!',
            },
            {
                id: 'finish',
                title: 'Mantap, Kamu Siap Tempur!',
                subtitle: 'Waktunya kelola duit lebih bijak',
                icon: 'finish',
                mobileShortTitle: 'Siap Tempur!',
                mobileShortDesc: 'Skor & AI ada di menu Profil, selamat mencoba!',
                target: null,
                content: 'Tips keren: Skor Kesehatan Keuangan & analisis pintar dari Gemini AI tersimpan aman di menu Profil Saya. Pasang juga BudgetIn di layar utama HP biar aksesnya makin sat-set!',
            }
        ],
        get totalSteps() {
            return this.steps.length - 1;
        },
        get currentStepData() {
            return this.steps[this.currentStep] || this.steps[0];
        },
        get desktopTooltipStyle() {
            const spaceBelow = window.innerHeight - (this.targetRect.top + this.targetRect.height);
            const cardHeight = 220;
            let top = 0;
            let left = Math.max(16, Math.min(this.targetRect.left, window.innerWidth - 460));

            if (spaceBelow > cardHeight + 20) {
                top = this.targetRect.top + this.targetRect.height + 12;
            } else {
                top = Math.max(16, this.targetRect.top - cardHeight - 12);
            }
            return `position: fixed; top: ${top}px; left: ${left}px; margin: 0; width: 420px;`;
        },
        initTour() {
            // Real-time position tracking on scroll and resize
            window.addEventListener('scroll', () => {
                if (this.isOpen && this.hasTarget) {
                    this.updatePosition();
                }
            }, { passive: true });

            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 768;
                if (this.isOpen) {
                    this.updatePosition();
                }
            });

            // Keyboard navigation
            window.addEventListener('keydown', (e) => {
                if (!this.isOpen) return;
                if (e.key === 'Escape') this.closeTour(true);
                if (e.key === 'ArrowRight') this.nextStep();
                if (e.key === 'ArrowLeft') this.prevStep();
            });

            // Check if user requested tour from another page
            const requestedTour = sessionStorage.getItem('budgetin_auto_start_tour');
            if (requestedTour === 'true') {
                sessionStorage.removeItem('budgetin_auto_start_tour');
                if ({{ request()->routeIs('admin.dashboard*') ? 'true' : 'false' }}) {
                    setTimeout(() => {
                        this.startTour();
                    }, 400);
                }
                return;
            }

            // Auto start on first visit to dashboard
            const hasSeen = localStorage.getItem('budgetin_onboarding_completed_v1');
            const isDashboard = {{ request()->routeIs('admin.dashboard*') ? 'true' : 'false' }};
            if (!hasSeen && isDashboard) {
                setTimeout(() => {
                    this.startTour();
                }, 1200);
            }
        },
        lockScroll() {
            if (this._isScrollLocked) return;
            this._isScrollLocked = true;

            this._wheelHandler = (e) => {
                if (this.isOpen) {
                    e.preventDefault();
                }
            };
            this._touchHandler = (e) => {
                if (this.isOpen) {
                    e.preventDefault();
                }
            };
            this._scrollKeyHandler = (e) => {
                const scrollKeys = ['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Space', 'Home', 'End'];
                if (this.isOpen && scrollKeys.includes(e.code)) {
                    e.preventDefault();
                }
            };
            window.addEventListener('wheel', this._wheelHandler, { passive: false });
            window.addEventListener('touchmove', this._touchHandler, { passive: false });
            window.addEventListener('keydown', this._scrollKeyHandler, { passive: false });
        },
        unlockScroll() {
            if (!this._isScrollLocked) return;
            this._isScrollLocked = false;

            if (this._wheelHandler) {
                window.removeEventListener('wheel', this._wheelHandler);
                this._wheelHandler = null;
            }
            if (this._touchHandler) {
                window.removeEventListener('touchmove', this._touchHandler);
                this._touchHandler = null;
            }
            if (this._scrollKeyHandler) {
                window.removeEventListener('keydown', this._scrollKeyHandler);
                this._scrollKeyHandler = null;
            }
        },
        startTour() {
            const isDashboard = {{ request()->routeIs('admin.dashboard*') ? 'true' : 'false' }};
            
            if (!isDashboard) {
                sessionStorage.setItem('budgetin_auto_start_tour', 'true');
                window.location.href = "{{ route('admin.dashboard') }}";
                return;
            }

            this.isMobile = window.innerWidth < 768;
            this.isExpanded = false;
            this.currentStep = 0;
            this.isOpen = true;
            this.lockScroll();
            this.goToStep(0);
        },
        nextStep() {
            this.isExpanded = false;
            if (this.currentStep < this.totalSteps) {
                this.goToStep(this.currentStep + 1);
            } else {
                this.closeTour(true);
            }
        },
        prevStep() {
            this.isExpanded = false;
            if (this.currentStep > 0) {
                this.goToStep(this.currentStep - 1);
            }
        },
        closeTour(markCompleted = true) {
            this.unlockScroll();
            this.isOpen = false;
            this.hasTarget = false;
            this.isExpanded = false;
            if (markCompleted) {
                localStorage.setItem('budgetin_onboarding_completed_v1', 'true');
            }
        },
        getTargetElement(step) {
            if (!step || !step.target) return null;
            if (step.id === 'quick-catat') {
                const mobileBtn = document.querySelector('#tour-mobile-catat');
                const desktopBtn = document.querySelector('#tour-quick-catat');
                if (window.innerWidth < 1024 && mobileBtn && mobileBtn.offsetParent !== null) {
                    return mobileBtn;
                }
                if (desktopBtn && desktopBtn.offsetParent !== null) {
                    return desktopBtn;
                }
                return mobileBtn || desktopBtn;
            }
            if (step.id === 'ai-insights') {
                const mobileProfile = document.querySelector('#tour-profile-nav');
                const desktopProfile = document.querySelector('#tour-profile-sidebar');
                if (window.innerWidth < 1024 && mobileProfile && mobileProfile.offsetParent !== null) {
                    return mobileProfile;
                }
                if (desktopProfile && desktopProfile.offsetParent !== null) {
                    return desktopProfile;
                }
                return mobileProfile || desktopProfile;
            }
            return document.querySelector(step.target);
        },
        goToStep(stepIndex) {
            this.currentStep = stepIndex;
            const step = this.steps[stepIndex];
            const el = this.getTargetElement(step);

            if (!el) {
                this.hasTarget = false;
                return;
            }

            this.hasTarget = true;
            this.$nextTick(() => {
                const rect = el.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const elementTop = rect.top + scrollTop;

                // Smart scroll
                if (this.isMobile) {
                    if (step.id === 'quick-catat') {
                        // Raised button at bottom navbar -> Pill goes to TOP
                        this.mobilePillPosition = 'top';
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    } else if (rect.height > 260) {
                        this.mobilePillPosition = 'bottom';
                        window.scrollTo({ top: Math.max(0, elementTop - 60), behavior: 'smooth' });
                    } else {
                        this.mobilePillPosition = 'bottom';
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Poll position during smooth scroll animation
                const startTime = performance.now();
                const trackLoop = (time) => {
                    this.updatePosition();
                    if (time - startTime < 600) {
                        requestAnimationFrame(trackLoop);
                    }
                };
                requestAnimationFrame(trackLoop);
            });
        },
        updatePosition() {
            const step = this.currentStepData;
            const el = this.getTargetElement(step);
            if (!el) {
                this.hasTarget = false;
                return;
            }

            const rect = el.getBoundingClientRect();
            if (rect.width === 0 && rect.height === 0) {
                this.hasTarget = false;
                return;
            }

            const isCircle = step.id === 'quick-catat' && window.innerWidth < 1024;
            const pad = isCircle ? 3 : 5;

            this.targetRect = {
                top: Math.max(0, rect.top - pad),
                left: Math.max(0, rect.left - pad),
                width: rect.width + (pad * 2),
                height: rect.height + (pad * 2),
                radius: isCircle ? 9999 : 16,
            };
            this.hasTarget = true;

            // Flip mobile pill position if target is in bottom half
            if (this.isMobile) {
                if (step.id === 'quick-catat') {
                    this.mobilePillPosition = 'top';
                } else {
                    const midScreen = window.innerHeight / 2;
                    this.mobilePillPosition = rect.top > midScreen ? 'top' : 'bottom';
                }
            }
        }
    }));
});
</script>
