<?php

namespace App\View\Components\Combobox;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QualiaStructure extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public ?string $value = null,
        public string $placeholder = '',
        public ?string $onChange = null,
        public ?string $onSelect = null,
    )
    {
        $this->onChange = $onChange ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.qualiastructure');
    }
}