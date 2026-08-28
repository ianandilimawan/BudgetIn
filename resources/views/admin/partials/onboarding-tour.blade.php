<!-- Interactive Spotlight Onboarding Tour Component -->
<div x-data="appOnboardingTour()"
     x-init="initTour()"
     @start-onboarding-tour.window="startTour()"
     x-cloak>

    <!-- Overlay Backdrop with Cutout Spotlight Focus -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] pointer-events-auto overflow-hidden">
        
        <!-- Dark Dim Backdrop -->
        <div class="absolute inset-0 bg-zinc-950/75 backdrop-blur-[2px] transition-all duration-300"></div>

        <!-- Spotlight Cutout & Glowing Highlight Box -->
        <div x-show="hasTarget"
             :style="`top: ${targetRect.top}px; left: ${targetRect.left}px; width: ${targetRect.width}px; height: ${targetRect.height}px;`"
             class="absolute pointer-events-none rounded-2xl ring-4 ring-emerald-500/80 shadow-[0_0_0_9999px_rgba(9,9,11,0.75)] shadow-emerald-500/20 transition-all duration-500 ease-out z-[101]">
            <span class="absolute -top-3 -right-3 flex h-6 w-6">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-6 w-6 bg-emerald-500 text-white text-[10px] font-bold items-center justify-center shadow-xs" x-text="currentStep"></span>
            </span>
        </div>

        <!-- Floating Popover Tooltip / Modal Box -->
        <div class="fixed inset-0 z-[102] pointer-events-none flex p-4"
             :class="hasTarget && !isMobile ? 'items-start justify-start' : 'items-center justify-center'">
            
            <div x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 :style="tooltipStyle"
                 class="pointer-events-auto w-full max-w-md bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200/90 dark:border-zinc-700/80 shadow-2xl p-5 sm:p-6 transition-all duration-300 relative overflow-hidden">
                
                <!-- Background Accent Glow -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 dark:bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Header: Icon, Step Badge & Close -->
                <div class="flex items-center justify-between gap-3 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 text-lg flex-shrink-0">
                            <span x-text="currentStepData.iconEmoji">✨</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60"
                                  x-text="currentStep === 0 ? 'Panduan Pengguna' : (currentStep === totalSteps ? 'Selesai' : `Langkah ${currentStep} dari ${totalSteps - 1}`)">
                            </span>
                            <h3 class="text-sm sm:text-base font-extrabold text-zinc-900 dark:text-white mt-1 leading-snug" x-text="currentStepData.title"></h3>
                        </div>
                    </div>

                    <button type="button" @click="closeTour(true)" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 p-1.5 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer" title="Tutup Panduan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Subtitle & Content Description -->
                <div class="relative z-10 my-3 space-y-2">
                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400" x-text="currentStepData.subtitle"></p>
                    <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed" x-text="currentStepData.content"></p>
                </div>

                <!-- Step Progress Dots -->
                <div class="flex items-center gap-1.5 my-4">
                    <template x-for="(step, idx) in steps" :key="idx">
                        <div class="h-1.5 rounded-full transition-all duration-300"
                             :class="currentStep === idx ? 'w-6 bg-emerald-500' : (idx < currentStep ? 'w-2 bg-emerald-300 dark:bg-emerald-800' : 'w-2 bg-zinc-200 dark:bg-zinc-800')">
                        </div>
                    </template>
                </div>

                <!-- Action Footer Controls -->
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
                            class="text-xs font-medium text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition cursor-pointer">
                        Lewati
                    </button>

                    <div class="flex items-center gap-2 ml-auto">
                        <button type="button" 
                                x-show="currentStep < totalSteps"
                                @click="nextStep()" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition shadow-md shadow-emerald-500/20 active:scale-95 cursor-pointer">
                            <span x-text="currentStep === 0 ? 'Mulai Panduan →' : 'Lanjut →'"></span>
                        </button>

                        <button type="button" 
                                x-show="currentStep === totalSteps"
                                @click="closeTour(true)" 
                                class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition shadow-lg shadow-emerald-500/30 active:scale-95 cursor-pointer">
                            <span>Mulai Eksplorasi 🎉</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('appOnboardingTour', () => ({
        isOpen: false,
        currentStep: 0,
        targetRect: { top: 0, left: 0, width: 0, height: 0 },
        hasTarget: false,
        isMobile: window.innerWidth < 768,
        steps: [
            {
                title: 'Selamat Datang di BudgetIn! 🌟',
                subtitle: 'Platform Manajemen Finansial Cerdas & Terstruktur',
                iconEmoji: '✨',
                target: null,
                content: 'BudgetIn hadir untuk membantu Anda mengelola seluruh arus keuangan pribadi—dari pencatatan mutasi multi-rekening, pembatasan pos anggaran belanja, hingga analisis kesehatan finansial bertenaga Google Gemini AI. Mari ikuti tur singkat 1 menit untuk mengenal fitur-fitur utamanya!',
            },
            {
                title: '1. Saldo Dompet & Multi-Rekening 💳',
                subtitle: 'Pantau Semua Rekening di Satu Tempat',
                iconEmoji: '🏦',
                target: '#tour-accounts',
                content: 'Di bagian ini, Anda dapat memantau saldo seluruh rekening bank (BCA, Mandiri, BRI, dll), e-wallet (GoPay, OVO, Dana), kartu kredit, atau uang kas tunai secara terpisah maupun total akumulasi kekayaan kas Anda secara real-time.',
            },
            {
                title: '2. Catat Transaksi Instan 📝',
                subtitle: 'Input Pemasukan & Pengeluaran Harian',
                iconEmoji: '⚡',
                target: window.innerWidth < 640 ? '#tour-mobile-catat' : '#tour-quick-catat',
                content: 'Gunakan tombol Catat Cepat untuk menginput transaksi masuk dan keluar harian dalam hitungan detik. Anda bisa memilih pos kategori, menentukan sumber rekening dompet, serta mengunggah foto struk/nota nota pembayaran.',
            },
            {
                title: '3. Target Anggaran (Budget Planner) 🎯',
                subtitle: 'Kendalikan Pagu Belanja agar Tidak Jebol',
                iconEmoji: '📊',
                target: '#tour-budget-planner',
                content: 'Tetapkan batas maksimal pengeluaran bulanan per pos kategori (misal: Makanan Rp 1,5 jt, Belanja Rp 800 rb). Sistem akan otomatis memberi alarm visual jika belanja Anda mendekati atau melebihi pagu yang ditentukan.',
            },
            {
                title: '4. Skor Kesehatan & Gemini AI Insights 🧠',
                subtitle: 'Evaluasi Finansial & Rekomendasi Cerdas',
                iconEmoji: '🤖',
                target: '#tour-ai-insights',
                content: 'Fitur unggulan bertenaga Google Gemini AI yang mengevaluasi kesehatan keuangan Anda (0-100) dari 4 pilar utama (Tabungan, Disiplin Anggaran, Dana Darurat, & Stabilitas Kas) lengkap dengan saran perbaikan konkret.',
            },
            {
                title: '5. Filter Periode & Ekspor Laporan Excel 📥',
                subtitle: 'Rekap Pembukuan Fleksibel Sekali Klik',
                iconEmoji: '📑',
                target: '#tour-filter-export',
                content: 'Pilih rentang tanggal transaksi dengan mudah (Bulan ini, Bulan lalu, Tahun ini, atau Custom) dan unduh seluruh rekap pembukuan Anda ke format spreadsheet Excel (.xlsx) kapan saja.',
            },
            {
                title: 'Anda Siap Menata Finansial Lebih Baik! 🚀',
                subtitle: 'Mulai Catat & Wujudkan Target Keuangan Anda',
                iconEmoji: '🎉',
                target: null,
                content: 'Tips: Pasang BudgetIn di layar utama smartphone Anda (PWA) melalui menu browser "Tambahkan ke Layar Utama" untuk pengalaman cepat dan nyaman layaknya aplikasi native!',
            }
        ],
        get totalSteps() {
            return this.steps.length - 1;
        },
        get currentStepData() {
            return this.steps[this.currentStep] || this.steps[0];
        },
        get tooltipStyle() {
            if (!this.hasTarget || this.isMobile) {
                return 'margin: auto;';
            }
            const spaceBelow = window.innerHeight - (this.targetRect.top + this.targetRect.height);
            const cardHeight = 300;
            let top = 0;
            let left = Math.max(16, Math.min(this.targetRect.left, window.innerWidth - 460));

            if (spaceBelow > cardHeight + 20) {
                top = this.targetRect.top + this.targetRect.height + 14;
            } else {
                top = Math.max(16, this.targetRect.top - cardHeight - 14);
            }
            return `position: absolute; top: ${top}px; left: ${left}px; margin: 0;`;
        },
        initTour() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 768;
                if (this.isOpen) this.updateSpotlight();
            });

            // Check if user is on dashboard and has not completed the tour
            const hasSeen = localStorage.getItem('budgetin_onboarding_completed_v1');
            const isDashboard = window.location.pathname.includes('/admin/dashboard') || window.location.pathname === '/admin';
            if (!hasSeen && isDashboard) {
                setTimeout(() => {
                    this.startTour();
                }, 1200);
            }
        },
        startTour() {
            this.currentStep = 0;
            this.isOpen = true;
            this.updateSpotlight();
        },
        nextStep() {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                this.updateSpotlight();
            } else {
                this.closeTour(true);
            }
        },
        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.updateSpotlight();
            }
        },
        closeTour(markCompleted = true) {
            this.isOpen = false;
            this.hasTarget = false;
            if (markCompleted) {
                localStorage.setItem('budgetin_onboarding_completed_v1', 'true');
            }
        },
        updateSpotlight() {
            const step = this.currentStepData;
            let targetSelector = step.target;

            if (step.target === '#tour-quick-catat' && window.innerWidth < 640) {
                targetSelector = '#tour-mobile-catat';
            }

            if (!targetSelector) {
                this.hasTarget = false;
                return;
            }

            this.$nextTick(() => {
                const el = document.querySelector(targetSelector);
                if (el) {
                    this.hasTarget = true;
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    setTimeout(() => {
                        const rect = el.getBoundingClientRect();
                        const pad = 8;
                        this.targetRect = {
                            top: Math.max(0, rect.top - pad),
                            left: Math.max(0, rect.left - pad),
                            width: rect.width + (pad * 2),
                            height: rect.height + (pad * 2),
                        };
                    }, 250);
                } else {
                    this.hasTarget = false;
                }
            });
        }
    }));
});
</script>
