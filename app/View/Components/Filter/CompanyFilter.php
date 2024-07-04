<?php

namespace App\View\Components\Filter;

use App\Models\Company;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CompanyFilter extends Component
{
    public $companies;
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->companies = Company::get();
        $this->selected = Company::find(request('company')) ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter.company-filter');
    }
}
