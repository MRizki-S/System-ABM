<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $viewName;

    public function __construct(array $data, string $viewName)
    {
        $this->data = $data;
        $this->viewName = $viewName;
    }

    public function view(): View
    {
        return view($this->viewName, $this->data);
    }
}
