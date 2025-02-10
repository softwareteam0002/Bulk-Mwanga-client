<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankExportWithRBMultiple implements WithMultipleSheets
{
    use Exportable;

    public  $number_of_batches=[];
    public  $startDate;
    public  $endDate;
    public  $organizationId;

    /**
     * ExportMultiple constructor.
     * @param $number_of_batches
     */
    public function __construct(array $number_of_batches ,$startDate,$endDate,$organizationId)
    {

        $this->number_of_batches = $number_of_batches;
        $this->startDate  = $startDate;
        $this->endDate =  $endDate;
        $this->organizationId=$organizationId;

    }


    /**
     * @return array
     */
    public function sheets(): array
    {

        $sheets  = [];

        Log::info('--------------STARTING BANK-EXPORT-BY-DATE-MULTIPLE-----------');
        for ($i=0; $i<sizeof($this->number_of_batches); $i++){

            $sheets[] = new BankPaymentWithRBSheets($this->startDate,$this->endDate,$this->number_of_batches[$i],$this->organizationId);
        }
        Log::info('--------------COMPLETE BANK-EXPORT-BY-DATE-MULTIPLE-----------');

        return  $sheets;
    }
}
