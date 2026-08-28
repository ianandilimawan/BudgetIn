<?php

namespace App\Helpers;

class ProjectIconHelper
{
    public static function getIconDefinitions(): array
    {
        return [
            'heart' => [
                'label' => 'Pernikahan & Keluarga',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
            ],
            'ticket' => [
                'label' => 'Liburan & Wisata',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>',
            ],
            'home' => [
                'label' => 'Rumah & Renovasi',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
            ],
            'academic-cap' => [
                'label' => 'Pendidikan & Kursus',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>',
            ],
            'truck' => [
                'label' => 'Kendaraan & Transportasi',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1"></path>',
            ],
            'gift' => [
                'label' => 'Hadiah & Acara',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V4.5a2.5 2.5 0 10-5 0V8h5zm0 0V4.5a2.5 2.5 0 115 0V8h-5zM5 8h14v13H5V8z"></path>',
            ],
            'briefcase' => [
                'label' => 'Bisnis & Karir',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
            ],
            'sparkles' => [
                'label' => 'Target & Impian',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>',
            ],
            'wallet' => [
                'label' => 'Tabungan Khusus',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
            ],
            'chart-bar' => [
                'label' => 'Investasi & Masa Depan',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
            ],
        ];
    }

    public static function getIconSvg(?string $icon): string
    {
        $emojiMap = [
            '💍' => 'heart',
            '🏖️' => 'ticket',
            '🏖' => 'ticket',
            '🏠' => 'home',
            '🎓' => 'academic-cap',
            '🚗' => 'truck',
            '🎁' => 'gift',
            '👶' => 'heart',
            '🎂' => 'gift',
            '💼' => 'briefcase',
            '✨' => 'sparkles',
        ];

        $key = $emojiMap[$icon] ?? ($icon ?? 'sparkles');
        $defs = self::getIconDefinitions();

        return $defs[$key]['svg'] ?? $defs['sparkles']['svg'];
    }

    public static function renderSvg(?string $icon, string $class = 'w-5 h-5'): string
    {
        $svgPath = self::getIconSvg($icon);
        return '<svg class="' . $class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $svgPath . '</svg>';
    }
}
