<?php

namespace App\Helpers;

class CategoryIconHelper
{
    public static function getIconDefinitions(): array
    {
        return [
            'briefcase' => [
                'label' => 'Gaji & Karir',
                'keywords' => 'gaji kerja kantor karir penghasilan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
            ],
            'gift' => [
                'label' => 'Bonus & Hadiah',
                'keywords' => 'bonus thr hadiah kado reward apresiasi',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V4.5a2.5 2.5 0 10-5 0V8h5zm0 0V4.5a2.5 2.5 0 115 0V8h-5zM5 8h14v13H5V8z"></path>',
            ],
            'chart-bar' => [
                'label' => 'Investasi & Saham',
                'keywords' => 'investasi saham dividen crypto reksadana profit',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
            ],
            'building-library' => [
                'label' => 'Bank & Pinjaman',
                'keywords' => 'bank pinjaman bunga simpanan kredit kpr',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>',
            ],
            'wallet' => [
                'label' => 'Dompet & Tabungan',
                'keywords' => 'dompet tabungan kas saldo e-wallet',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
            ],
            'utensils' => [
                'label' => 'Makanan & Minuman',
                'keywords' => 'makanan makan minum resto kafe kopi jajan cemilan lunch dinner breakfast',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>',
            ],
            'shopping-bag' => [
                'label' => 'Belanja & Fashion',
                'keywords' => 'belanja baju celana sepatu fashion mall online shop',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"></path>',
            ],
            'shopping-cart' => [
                'label' => 'Supermarket & Grosir',
                'keywords' => 'supermarket minimarket sembako kebutuhan sayur pasar',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>',
            ],
            'truck' => [
                'label' => 'Transportasi & Bensin',
                'keywords' => 'transportasi bensin ojek tol parkir travel kendaraan motor mobil servis bbm',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1"></path>',
            ],
            'bolt' => [
                'label' => 'Listrik & Utilitas',
                'keywords' => 'listrik pln pdam air gas tagihan utilitas',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>',
            ],
            'credit-card' => [
                'label' => 'Cicilan & Hutang',
                'keywords' => 'cicilan hutang kredit paylater angsuran pinjol',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
            ],
            'home' => [
                'label' => 'Rumah & Tempat Tinggal',
                'keywords' => 'rumah kost sewa kontrakan iuran apartemen perumahan renovasi',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
            ],
            'heart' => [
                'label' => 'Kesehatan & Medis',
                'keywords' => 'kesehatan obat apotek dokter rumah sakit klinik vitamin bpjs dental gigi',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
            ],
            'academic-cap' => [
                'label' => 'Pendidikan & Kursus',
                'keywords' => 'pendidikan sekolah kuliah spp buku kursus les sertifikasi bimbel',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>',
            ],
            'film' => [
                'label' => 'Hiburan & Liburan',
                'keywords' => 'hiburan film bioskop streaming netflix spotify game nonton rekreasi liburan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>',
            ],
            'device-phone-mobile' => [
                'label' => 'Pulsa & Internet',
                'keywords' => 'pulsa internet kuota wifi telkomsel indosat xl provider kartu langganan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>',
            ],
            'sparkles' => [
                'label' => 'Perawatan & Skincare',
                'keywords' => 'skincare salon potong rambut makeup perawatan gym olahraga fitness kosmetik',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>',
            ],
            'wrench-screwdriver' => [
                'label' => 'Perbaikan & Servis',
                'keywords' => 'servis perbaikan benerin tukang bengkel alat perkakas perabot',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
            ],
            'user-group' => [
                'label' => 'Keluarga & Sosial',
                'keywords' => 'keluarga anak orang tua sedekah zakat donasi arisan kondangan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
            ],
            'ticket' => [
                'label' => 'Tiket & Wisata',
                'keywords' => 'tiket wisata hotel pesawat kereta wahana perjalanan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>',
            ],
            'arrow-down-left' => [
                'label' => 'Pemasukan Lain',
                'keywords' => 'pemasukan income transfer masuk refund pengembalian',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5l-14 14m0 0h10m-10 0v-10"></path>',
            ],
            'arrow-up-right' => [
                'label' => 'Pengeluaran Lain',
                'keywords' => 'pengeluaran expense transfer keluar denda admin',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19l14-14m0 0h-10m10 0v10"></path>',
            ],
            'tag' => [
                'label' => 'Lain-lain / Kategori Umum',
                'keywords' => 'lainnya umum lain misc tag serbaguna',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>',
            ],
        ];
    }

    public static function getIconSvg(?string $iconName): string
    {
        $icons = self::getIconDefinitions();
        return $icons[$iconName]['svg'] ?? ($icons['tag']['svg'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>');
    }

    public static function renderBadge(?string $iconName, string $type = 'expense', string $class = 'w-8 h-8'): string
    {
        $svgPath = self::getIconSvg($iconName);
        $bgClass = $type === 'income'
            ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40'
            : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-800/40';

        return '<div class="inline-flex items-center justify-center ' . $class . ' rounded-xl ' . $bgClass . ' shadow-xs flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $svgPath . '</svg></div>';
    }
}
