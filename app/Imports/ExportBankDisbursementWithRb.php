<?php

namespace App\Imports;

use App\Exports\PaymentSheets;
use App\Helper\BankDisbursementApiHelper;
use App\Models\BankBatchPayment;
use App\Models\Batch;
use App\Models\DisbursementOpeningBalance;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportBankDisbursementWithRb implements FromView,WithTitle,WithEvents
{
    use Exportable,RegistersEventListeners;


    public $batch_no = null;
    private static $disbursements = null;

    /**
     * ExportDisbursement constructor.
     * @param $batch_no
     */
    public function __construct($batch_no)
    {
        $this->batch_no= $batch_no;

    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Batch No.".$this->batch_no;
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        self::$disbursements = DB::table('bank_batch_payments as bv')
            ->where(['bv.organization_id'=>Auth::user()->organization_id,'bv.batch_no'=>$this->batch_no ])
            ->select(
                'dp.batch_no',
                'bv.user_batch_no',
                'bv.batch_completed_date',
                'bv.approved_date',
                'bv.created_at as initiated_date',
                'dp.first_name',
                'dp.last_name',
                'dp.phone_number',
				'dp.account_number',
                'dp.amount',
                'dp.tx_charge',
                'dp.withdrawal_fee',
                'dp.bank',
                'dp.payment_status',
                DB::raw('(SELECT mpesa_receipt FROM tx_bank_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status')
            )
            ->join('bank_payment_disbursements as dp','dp.batch_no','=','bv.batch_no')
			->orderBy('dp.id', 'ASC')
            ->get();

        $summary = BankDisbursementApiHelper::getDisbursementStatus($this->batch_no,false,true);

//        dd($summary);
        $summary['unknown'] = $summary['total'] -  $summary['successful'] -  $summary['failed'];
        $amounts = $summary['extended']; unset($summary['extended']); $entries = $summary;
        $amounts['unknown'] = $amounts['total'] -  $amounts['successful'] -  $amounts['failed'];

        $orgName  =  PaymentSheets::getOrganizationByBatchNumber($this->batch_no);

        $handlers  =  BankBatchPayment::query()->select('operator','handler')->where(['batch_no'=>$this->batch_no])->first();
        $bp = BankBatchPayment::query()->where(['batch_no'=>$this->batch_no])->first();
        $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id'=>$bp->id])->where('transaction_type','bank')->first();
        $balance = $ob_balance->organizationAccountBalance->available_balance??0;

        return view('exports.bank.disbursements_with_rb', [
            'disbursements' => self::$disbursements,
            'user_batch_no' => BankBatchPayment::query()->where(['batch_no'=>$this->batch_no])->first()->user_batch_no,
            'amounts' => $amounts,
            'entries' => $entries,
            'orgName' => $orgName,
            'handlers'=>$handlers,
            'balance'=>$balance,
            'batch_status' =>  Batch::getStatusName($entries['status']).($entries['status']==Batch::STATUS_FAILED?' - '.$entries['status_description']:''),
        ]);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->autoSize();
        $event->sheet->getDelegate()->getStyle( 'A1:I1')->applyFromArray(
            [
                'font'=>[
                    'size'      =>  15,
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A4:A6')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'B4:H4')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A8')->applyFromArray(
            [
                'font'=>[
                    'size'      =>  12,
                    'bold'      =>  true,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A9:F9')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D9D9D9',
                    ]
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A10:F0')->applyFromArray(
            [
                'font'=>[
                    'bold' => true,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]
        );

        $event->sheet->getDelegate()->getStyle('B11:B'.(11+count(self::$disbursements)*3))->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]
        );


        $event->sheet->getDelegate()->getStyle('E11:E'.(11+count(self::$disbursements)*3))->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]
        );

        $event->sheet->getDelegate()->getStyle('A11:A'.(11+count(self::$disbursements)*3))->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]
        );

        $event->sheet->getDelegate()->getStyle('F11:F'.(11+count(self::$disbursements)*3))->applyFromArray(
            [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]
        );

        $event->sheet->getDelegate()->getStyle('E11:E'.(11+count(self::$disbursements)*3))->getNumberFormat()->applyFromArray(
            [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A3')->applyFromArray(
            [
                'font'=>[
                    'size'=>  12,
                    'bold'=>  true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D9D9D9',
                    ]
                ],
            ]
        );

        foreach (self::$disbursements as $i=>$item){
            if ($item->payment_status == DisbursementPayment::STATUS_ERROR){
                $event->sheet->getDelegate()->getStyle( "F".(10+$i))->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'F60000',
                            ]
                        ],
                    ]
                );
            }
        }

        /*$row_c = count(self::$disbursements)+10;
        $event->sheet->getDelegate()->getStyle( 'A'.$row_c.':I'.$row_c)->applyFromArray(
            [
                'font'=>[
                    'size'      =>  12,
                    'bold'      =>  true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D9D9D9',
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );*/
    }

}
