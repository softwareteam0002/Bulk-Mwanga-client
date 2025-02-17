<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankExportWithRBMultiplePDF implements WithMultipleSheets
{
    use Exportable;

    public $numberOfBatches = [];
    public $startDate;
    public $endDate;
    public $organizationId;

    /**
     * ExportMultiple constructor.
     * @param $numberOfBatches
     */
    public function __construct(array $numberOfBatches, $startDate, $endDate, $organizationId)
    {

        $this->number_of_batches = $numberOfBatches;
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

        for ($i = 0; $i < sizeof($this->numberOfBatches); $i++) {

            $sheets[] = new BankPaymentWithRBPDF($this->startDate, $this->endDate, $this->numberOfBatches[$i], $this->organizationId);
        }

        return $sheets;
    }
}
