<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class layout extends Component
{
    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title; // Store the title in the class property
    }

    public function render(): View|Closure|string
    {
        return view('components.layout', ['title' => $this->title]);
    }
}
