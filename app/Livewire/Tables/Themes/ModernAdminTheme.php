<?php

namespace App\Livewire\Tables\Themes;

use PowerComponents\LivewirePowerGrid\Themes\Tailwind;
use PowerComponents\LivewirePowerGrid\Themes\Theme;

class ModernAdminTheme extends Tailwind
{
    public string $name = 'modern-admin';

    public function table(): array
    {
        return [
            'layout' => [
                'base' => 'min-w-full inline-block align-middle',
                'div' => 'rounded-t-lg relative border-x border-t border-pg-primary-200 dark:bg-pg-primary-700 dark:border-pg-primary-600',
                'header' => 'p-3 flex sm:flex-row flex-col gap-2 sm:gap-0 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800',
                'actions' => 'flex items-center gap-2',
                'message' => 'text-pg-primary-500 text-sm dark:text-pg-primary-300 px-4 py-4',
            ],
            'header' => [
                'thead' => 'shadow-sm bg-gray-50 dark:bg-gray-800',
                'tr' => '',
                'th' => 'font-bold uppercase tracking-wider text-xs px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap',
                'thAction' => 'uppercase tracking-wider text-xs px-4 py-3 text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap text-center',
            ],
            'body' => [
                'tbody' => 'text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900',
                'tbodyEmpty' => '',
                'tr' => 'border-b border-gray-50 dark:border-gray-800/50 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors duration-150',
                'td' => 'px-4 py-3 whitespace-nowrap',
                'tdEmpty' => 'p-4 whitespace-nowrap text-center',
                'tdSummarize' => 'p-4 whitespace-nowrap text-sm text-right space-y-2',
                'trSummarize' => '',
                'tdFilters' => '',
                'trFilters' => '',
                'tdActionsContainer' => 'flex gap-2 justify-center',
            ],
        ];
    }
}
