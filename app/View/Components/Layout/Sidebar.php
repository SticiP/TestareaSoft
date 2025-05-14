<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $devices = collect(); // Inițializează ca colecție goală

        // Verifică autentificarea
        if (auth()->check()) {
            $devices = auth()->user()->devices()->get();
        }

        return view('components.layout.sidebar', compact('devices'));
    }
}
