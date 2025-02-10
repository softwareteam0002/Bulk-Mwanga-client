<?php

namespace App\Exports;

use App\Helper\ModelDataHelper;
use App\Models\BatchPayment;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PaymentAllInOneSheetsWithRB implements FromView,WithTitle,WithEvents
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
        Log::info('BATCH-ALL-IN-ONE-REPORT',['MESSAGE'=>'---------STARTING----FETCHING---']);
        $disbursements = DB::table('batch_payments as bv')

            ->whereBetween(DB::raw('date(bv.created_at)'),[date('Y-m-d', strtotime($this->startDate)),date('Y-m-d', strtotime($this->endDate))])
            ->where(['organization_id'=>$this->organizationId])
            ->select(
                'bv.id',
                'bv.user_batch_no',
                'bv.batch_no',
                'dp.first_name',
                'dp.last_name',
                'dp.phone_number',
                'bv.batch_completed_date',
                'bv.approved_date',
                'bv.created_at as initiated_date',
                'dp.amount',
                'dp.tx_charge',
                'dp.withdrawal_fee',
                'dp.network_name',
				'dp.updated_at as payment_date',
                'dp.payment_status',
                DB::raw('(SELECT mpesa_receipt FROM tx_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status'),
				'dp.payment_detail'
            )
            ->join('disbursement_payments as dp','dp.batch_no','=','bv.batch_no')
//            ->leftJoin('tx_disbursement as tx','tx.entry_id','=','dp.id')
            ->get();
        Log::info('BATCH-ALL-IN-ONE-REPORT',['MESSAGE'=>'---------COMPLETED----FETCHING---']);

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
        $balance = 0;
        $orgName  = ModelDataHelper::getOrganizationById($this->organizationId);

        if (count($disbursements)>0){
            $disbursements = $disbursements->toArray();
            $ob_balance = DB::table('disbursement_opening_balances as dop')
                ->select('available_balance')
                ->join('organization_account_balance as oab','oab.id','=','dop.balance_id')
                ->where(['batch_id'=>$disbursements[0]->id??null])
                ->where('transaction_type','=','bank')->first();
            $balance = $ob_balance->available_balance??0;
        }

        return view('exports.disbursements_one_sheets_with_rb', [
            'disbursements' => $disbursements,
            'amounts' => $amounts,
            'entries' => $entries,
            'balance'=>$balance,
			'orgName'=>$orgName,
			'startDate'=>$this->startDate,
			'endDate'=>$this->endDate,
        ]);
    }


    /**
     * @return string
     */
    public function title(): string
    {

        return ' All In One Report ';

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
        $event->sheet->getDelegate()->getStyle( 'A1:J1')->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle( 'D6')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A3:A6')->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle( 'A9:J9')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle( 'A8')->applyFromArray(
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
