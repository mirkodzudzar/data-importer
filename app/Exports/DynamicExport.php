<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DynamicExport implements FromView
{
    protected $data;
    protected $headers;

    public function __construct($data, $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function view(): View
    {
        return view('exports.dynamic', [
            'data' => $this->data,
            'headers' => $this->headers,
        ]);
    }
}
