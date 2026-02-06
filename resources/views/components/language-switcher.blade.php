<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- Desktop Language Switcher -->
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $currentLocaleName }}</span>
        <svg class="w-4 h-4 text-gray-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute {{ \App\Helpers\LocaleHelper::isRtl() ? 'left-0' : 'right-0' }} mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-y-auto">
        <div class="py-2">
            @foreach($locales as $code => $locale)
                <a href="{{ url($code . '/' . ltrim(request()->path() === '/' ? '' : preg_replace('/^[a-z]{2}(-[a-z]{4})?\//i', '', request()->path()), '/')) }}" 
                   class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ $currentLocale === $code ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                   @if(isset($locale['dir']) && $locale['dir'] === 'rtl') dir="rtl" @endif>
                    <span class="text-lg">{{ \App\Helpers\LocaleHelper::getLocaleFlag($code) }}</span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 {{ $currentLocale === $code ? 'text-blue-600 dark:text-blue-400' : '' }}">{{ $locale['native'] }}</span>
                    @if($currentLocale === $code)
                        <svg class="w-4 h-4 {{ \App\Helpers\LocaleHelper::isRtl() ? 'mr-auto' : 'ml-auto' }} text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
