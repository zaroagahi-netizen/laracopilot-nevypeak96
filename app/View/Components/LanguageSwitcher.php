<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageSwitcher extends Component
{
    public $locales;
    public $currentLocale;
    public $currentLocaleName;
    
    public function __construct()
    {
        $this->locales = config('laravellocalization.supportedLocales');
        $this->currentLocale = app()->getLocale();
        $this->currentLocaleName = $this->locales[$this->currentLocale]['native'] ?? $this->currentLocale;
    }
    
    public function render()
    {
        return view('components.language-switcher');
    }
}