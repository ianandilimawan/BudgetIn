<tr
    class="{{ theme_style($theme, 'table.header.tr') }}"
>
    <th
        class="{{ theme_style($theme, 'table.body.tdEmpty') }} py-8 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900 border-none font-medium"
        colspan="999"
    >
        <div class="flex flex-col items-center justify-center space-y-2">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <span>{!! $this->processNoDataLabel !!}</span>
        </div>
    </th>
</tr>
