<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    /**
     * Create a new component instance.
     */
    public $ajaxUrl;
    public $columns;
    public $jsonColumns;

    public function __construct($ajaxUrl, $columns, $jsonColumns)
    {
        $this->ajaxUrl = $ajaxUrl;
        $this->columns = $columns;
        $this->jsonColumns = $jsonColumns;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.data-table');
    }
}