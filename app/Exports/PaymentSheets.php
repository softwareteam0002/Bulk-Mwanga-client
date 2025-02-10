<?php

namespace App\Exports;

use App\Helper\ModelDataHelper;
use App\Models\BatchPayment;
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

class PaymentSheets implements FromView,WithTitle,WithEvents
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

        $data  =  DisbursementPayment::query()->with('organization');
        $data =$data->select('batch_no','first_name','last_name','phone_number','amount','payment_detail','network_name','failure_reason');
        $data  =$data->where(['batch_no'=>$this->batch]);

        $data = $data->whereBetween('created_at',[$this->startDate,$this->endDate]);

        return  $data;

    }

    public function view(): View
    {
        $disbursements = DB::table('batch_payments as bv')
            ->where(['bv.batch_no'=>$this->batch ])
            ->whereBetween(DB::raw('date(bv.created_at)'),[$this->startDate,$this->endDate])
            ->select('bv.user_batch_no',
                'dp.first_name',
                'dp.last_name',
                'dp.phone_number',
                'bv.batch_completed_date',
                'bv.approved_date',
                'bv.created_at as initiated_date',
                'dp.amount',
                'dp.withdrawal_fee',
                'dp.network_name',
                'dp.payment_status',
                'mpesa_receipt',
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status'),
				'dp.payment_detail'
            )
            ->join('disbursement_payments as dp','dp.batch_no','=','bv.batch_no')
            ->leftJoin('tx_disbursement as tx','tx.entry_id','=','dp.id')

            ->get();


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

        $amounts['unknown'] =  $disbursements->sum(function ($item){
            return $item->payment_status==DisbursementPayment::STATUS_SENT?($item->amount + $item->withdrawal_fee):0;
        });


        self::$disbursements = $disbursements;

        $handlers  =  DB::table('batch_payments')->select('operator','handler')->where(['batch_no'=>$this->batch])->first();

        $orgName  = ModelDataHelper::getOrganizationById($this->organizationId);
        return view('exports.disbursements_multiple_sheets', [
            'disbursements' => $disbursements,
            'batch_no' => $this->batch,
            'amounts' => $amounts,
            'entries' => $entries,
            'orgName'=>$orgName,
            'handlers'=>$handlers,
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

        $event->sheet->getDelegate()->getStyle( 'A8:H8')->applyFromArray(
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

        return $org['name'];
    }


}
