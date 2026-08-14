<?php

namespace Modules\Admin\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MetaTags extends Component
{
    public $metaData;
    public $titleCol;
    public $keywordsCol;
    public $descriptionCol;
    public $otherCol;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $metaData = null,
        $titleCol = 'col-md-6',
        $keywordsCol = 'col-md-6',
        $descriptionCol = 'col-md-6',
        $otherCol = 'col-md-6'
    ) {
        $this->metaData = $metaData;
        $this->titleCol = $titleCol;
        $this->keywordsCol = $keywordsCol;
        $this->descriptionCol = $descriptionCol;
        $this->otherCol = $otherCol;
    }

    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        return view('admin::components.meta-tags');
    }
}
