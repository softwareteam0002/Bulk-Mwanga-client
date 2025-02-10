<?php

namespace App\Exports;

use App\Models\BatchPayment;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PaymentWithBalance implements FromView,WithTitle,WithEvents
{


    private  $startDate;
    private  $endDate;


    private  $organizationId;
    private static $disbursements = null;

    use Exportable,RegistersEventListeners;

    /**
     * PaymentAllInOneSheets constructor.
     * @param $startDate
     * @param $endDate
     * @param $organizationId
     */
    public function __construct($startDate, $endDate,$organizationId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->organizationId=$organizationId;
    }


    public function view(): View
    {
        $disbursements = DB::table('batch_payments as bv')

            ->whereBetween(DB::raw('date(bv.created_at)'),[date('Y-m-d', strtotime($this->startDate)),date('Y-m-d', strtotime($this->endDate))])
            ->where(['bv.organization_id'=>$this->organizationId])
            ->select('bv.user_batch_no',
                'dp.first_name',
                'dp.last_name',
                'dp.phone_number',
                'dp.amount',
                'dp.withdrawal_fee',
                'dp.network_name',
                'dp.payment_status',
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status'),
                'oab.current_balance',
				'dp.payment_detail'
            )
            ->join('disbursement_payments as dp','dp.batch_no','=','bv.batch_no')
            ->join('disbursement_opening_balances as dob','dob.batch_id','=','bv.id')
            ->join('organization_account_balance as oab','oab.id','=','dob.balance_id')

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

        return view('exports.disbursements_one_sheets_with_balance', [
            'disbursements' => $disbursements,

            'amounts' => $amounts,
            'entries' => $entries,

        ]);
    }


    /**
     * @return string
     */
    public function title(): string
    {

        return ' General Report ';

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

        $event->sheet->getDelegate()->getStyle( 'A8:I8')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A7')->applyFromArray(
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

        $org  =  BatchPayment::query()
            ->select('organizations.name')
            ->join('organizations','organizations.id','=','batch_payments.organization_id')
            ->where(['batch_no'=>$batch])
            ->first();

        return $org->name;
    }


}
