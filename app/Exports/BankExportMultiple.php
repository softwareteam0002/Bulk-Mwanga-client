<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankExportMultiple implements WithMultipleSheets
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

        for ($i = 0; $i < sizeof($this->numberOfBatches); $i++) {

            $sheets[] = new BankPaymentSheets($this->startDate, $this->endDate, $this->numberOfBatches[$i], $this->organizationId);
        }

        return $sheets;
    }
}
