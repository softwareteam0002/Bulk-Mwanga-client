<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class ExportBankVerification implements FromCollection,WithHeadings,WithEvents,WithColumnFormatting
{
    use Exportable;


    public $batch_no = null;
    public $verifications = [];

    /**
     * ExportDisbursement constructor.
     * @param $batch_no
     */
    public function __construct($batch_no)
    {
        $this->batch_no = $batch_no;

    }


    /**
     * @return Collection
     */
    public function collection()
    {
        $this->verifications = DB::table('bank_verification_batches as bv');


        if (Auth::user()->user_type==2) {
            $this->verifications = $this->verifications->where(['bv.organization_id' => Auth::user()->organization_id, 'bv.batch_no' => $this->batch_no]);
        }
        else {
            $this->verifications = $this->verifications->where(['bv.batch_no' => $this->batch_no]);

        }
        $this->verifications = $this->verifications->select(
            'bv.user_batch_no',
            'dp.first_name',
            'dp.last_name',
            'dp.verified_first_name',
            'dp.verified_last_name',
            'dp.phone_number',
            'dp.amount',
            'dp.account_number',
            'dp.bank',
            'dp.bank_id',
            DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Processed"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END)
                ,dp.status_description)'),
                'dp.payment_detail'

        )
            ->join('bank_disbursement_verifications as dp','dp.batch_no','=','bv.batch_no')
            ->get();

        return  $this->verifications;
    }

    /**
     * @return array
     */
    public function headings(): array
    {

        return   [
            'batch_no',
            'first_name',
            'last_name',
            'verified_first_name',
            'verified_last_name',
            'phone_number',
            'amount',
            'account_number',
            'bank',
            'bank_id',
            'verification_status',
			'payment_detail'

        ];
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            // Using a class with an __invoke method.
            AfterSheet::class => function(AfterSheet $event) {
                $this->afterSheet($event);
            },
        ];
    }

    private function afterSheet(AfterSheet $event)
    {
        $event->sheet->autoSize();
        //protect the sheet with the key
        $event->getSheet()->getDelegate()->getProtection()->setPassword(bin2hex(random_bytes(32)));
        $event->getSheet()->getDelegate()->getProtection()->setSheet(true);
        foreach ($this->verifications as $i=>$item){
            if (strtolower($item->first_name)!=strtolower($item->verified_first_name)
                || strtolower($item->last_name)!=strtolower($item->verified_last_name)){
                $cells = "A".(2+$i).':L'.(2+$i);
                $event->sheet->getDelegate()->getStyle($cells)->applyFromArray(
                    [
                        'font'=>[
                            'bold'      =>  true,
                            'color'=> ['rgb'=>'F60000']
                        ],
                    ]
                );

                //Unlock the row that failed verifcation
                $event->getSheet()->getDelegate()->getStyle($cells)
                    ->getProtection()
                    ->setLocked(Protection::PROTECTION_UNPROTECTED);
            }
        }

    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        return [
            'A'=>NumberFormat::FORMAT_TEXT
        ];
    }
}
