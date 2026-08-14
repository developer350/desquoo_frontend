<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SupportSection extends Component
{
    public $supportSectionCms;
    public $siteSettings;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->supportSectionCms = app('supportSectionCms');
        $this->siteSettings = app('siteSettings');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.support-section');
    }
}
