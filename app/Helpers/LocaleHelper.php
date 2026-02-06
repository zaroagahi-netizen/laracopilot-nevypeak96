<?php

namespace App\Helpers;

class LocaleHelper
{
    public static function isRtl($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return in_array($locale, config('translatable.rtl_locales', []));
    }
    
    public static function direction($locale = null)
    {
        return self::isRtl($locale) ? 'rtl' : 'ltr';
    }
    
    public static function getCurrentLocaleName()
    {
        $locale = app()->getLocale();
        $locales = config('laravellocalization.supportedLocales');
        return $locales[$locale]['native'] ?? $locale;
    }
    
    public static function getLocaleFlag($locale)
    {
        $flags = [
            'tr' => '🇹🇷',
            'ku-latn' => '🟨🔴🟩',
            'ku-arab' => '🟨🔴🟩',
            'en' => '🇬🇧',
            'de' => '🇩🇪',
            'ar' => '🇸🇦',
            'fa' => '🇮🇷',
        ];
        return $flags[$locale] ?? '🌐';
    }
}