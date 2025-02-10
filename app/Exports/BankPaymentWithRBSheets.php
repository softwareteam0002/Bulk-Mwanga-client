<?php

namespace App\Exports;

use App\Helper\ModelDataHelper;
use App\Models\BankBatchPayment;
use App\Models\BankPaymentDisbursement;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
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

class BankPaymentWithRBSheets implements FromView,WithTitle,WithEvents
{


    private  $startDate;
    private  $endDate;
    private  $batch;

    private  $organizationId;
    private static $disbursements = null;

    use Exportable,RegistersEventListeners;

    /**
     * PaymentSheets constructor.
     * @param $startDate
     * @param $endDate
     */
    public function __construct($startDate, $endDate,$batch,$organizationId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->batch =  $batch;
        $this->organizationId=$organizationId;
    }


    /**
     * @return \Illuminate\Support\Collection
     */



    /**
     * @return Builder
     */
    public function query()
    {

        $data  =  BankPaymentDisbursement::query()->with('organization');
        $data =$data->select('batch_no','first_name','last_name','phone_number','amount','payment_detail','network_name','failure_reason');
        $data  =$data->where(['batch_no'=>$this->batch]);

        $data = $data->whereBetween('created_at',[$this->startDate,$this->endDate]);

        return  $data;

    }

    public function view(): View
    {
        $disbursements = DB::table('bank_batch_payments as bv')
            ->where(['bv.batch_no'=>$this->batch ])
            ->whereBetween(DB::raw('date(bv.created_at)'),[$this->startDate,$this->endDate])
            ->select(
                'bv.organization_id','bv.id',
                'bv.user_batch_no',
                'dp.first_name',
                'dp.last_name',
                'dp.phone_number',
                'dp.account_number',
                'bv.batch_completed_date',
                'bv.approved_date',
                'bv.created_at as initiated_date',
                'dp.amount',
                'dp.tx_charge',
                'dp.withdrawal_fee',
                'dp.bank',
                'dp.payment_status',
                DB::raw('(SELECT mpesa_receipt FROM tx_bank_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status'),
				'dp.payment_detail'
            )
            ->join('bank_payment_disbursements as dp','dp.batch_no','=','bv.batch_no')
//            ->leftJoin('tx_bank_disbursement as tx', 'tx.entry_id', '=', 'dp.id')

            ->cursor();

        $entries['total'] = $disbursements->count();


        $entries['processed'] = $disbursements->sum(function ($item){
            return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?1:0;
        });
        $entries['successful'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_PAID?1:0;
        });
        $entries['failed'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_ERROR?1:0;
        });

        $entries['unknown'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_SENT?1:0;
        });

        $amounts['total'] = $disbursements->sum(function($item){
            return $item->amount + $item->withdrawal_fee;
        });
        $amounts['processed'] = $disbursements->sum(function ($item){
            return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?($item->amount + $item->withdrawal_fee):0;
        });
        $amounts['successful'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_PAID?($item->amount + $item->withdrawal_fee):0;
        });
        $amounts['failed'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_ERROR?($item->amount + $item->withdrawal_fee):0;
        });

        $amounts['unknown'] = $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_SENT?($item->amount + $item->withdrawal_fee):0;
        });


        self::$disbursements = $disbursements;

        $handlers  =  DB::table('bank_batch_payments')
            ->select('operator','handler')
            ->where(['batch_no'=>$this->batch])->first();

        $orgName  = '';
        $balance  = 0.00;

        if (isset($disbursements[0]->id)){
            $orgName  = ModelDataHelper::getOrganizationById($disbursements[0]->organization_id);
            $ob_balance = DB::table('disbursement_opening_balances as dop')
                ->select('available_balance')
                ->join('organization_account_balance as oab','oab.id','=','dop.balance_id')
                ->where(['batch_id'=>$disbursements[0]->id??null])
                ->where('transaction_type','=','bank')->first();
            $balance = $ob_balance->available_balance??0;
        }


        return view('exports.bank.disbursements_multiple_sheets_with_rb', [
            'disbursements' => $disbursements,
            'batch_no' => $this->batch,
            'amounts' => $amounts,
            'entries' => $entries,
            'orgName'=>$orgName,
            'handlers'=>$handlers,
            'balance'=>$balance,
        ]);
    }


    /**
     * @return string
     */
    public function title(): string
    {

        return 'Batch ' . $this->batch;

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.

        return   [

            'batch_no',
            'first_name',
            'last_name',
            'phone_number',
            'amount',
            'payment_detail',
            'network_name',
            'failure_reason'

        ];
    }


    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->autoSize();
        $event->sheet->getDelegate()->getStyle('C11:C'.(count(self::$disbursements)*3))->applyFromArray(
            [
                'alignment'=>[
                    'wrapText' => true
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

        $event->sheet->getDelegate()->getStyle('D11:D'.(11+count(self::$disbursements)*3))->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle('D11:D'.(11+count(self::$disbursements)*3))->getNumberFormat()->applyFromArray(
            [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ]
        );
        $event->sheet->getDelegate()->getStyle('E11:E'.(11+count(self::$disbursements)*3))->getNumberFormat()->applyFromArray(
            [
                'formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A1:H1')->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle( 'A3:A5')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'B3:H3')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A10:H10')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A9')->applyFromArray(
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
            ]
        );
        $event->sheet->getDelegate()->getStyle( 'A2')->applyFromArray(
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
            ]
        );


    }


    public static  function getOrganizationByBatchNumber($batch){

        $org  =  BankBatchPayment::query()
            ->select('organizations.name')
            ->join('organizations','organizations.id','=','bank_batch_payments.organization_id')
            ->where(['batch_no'=>$batch])
            ->first();

        return $org->name;
    }


}
