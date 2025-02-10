<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InternalReportTransactionsExport implements FromView
{
    public $transactions;
    public function view(): View
    {
        return view('reports.internal_report_transactions', [
            'transactions'=>$this->transactions,
        ]);
    }
}

//InternalReportTransactionsExport