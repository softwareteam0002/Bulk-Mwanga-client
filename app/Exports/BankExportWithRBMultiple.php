<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankExportWithRBMultiple implements WithMultipleSheets
{
    use Exportable;

    public $numberOfBatches = [];
    public $startDate;
    public $endDate;
    public $organizationId;

    /**
     * ExportMultiple constructor.
     * @param array $numberOfBatches
     */
    public function __construct(array $numberOfBatches, $startDate, $endDate, $organizationId)
    {

        $this->numberOfBatches = $numberOfBatches;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->organizationId = $organizationId;

    }


    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets = [];

        Log::info('--------------STARTING BANK-EXPORT-BY-DATE-MULTIPLE-----------');
        for ($i = 0; $i < sizeof($this->numberOfBatches); $i++) {

            $sheets[] = new BankPaymentWithRBSheets($this->startDate, $this->endDate, $this->numberOfBatches[$i], $this->organizationId);
        }
        Log::info('--------------COMPLETE BANK-EXPORT-BY-DATE-MULTIPLE-----------');

        return $sheets;
    }
}
