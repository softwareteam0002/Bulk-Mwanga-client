<?php

namespace App\Imports;

use App\Exports\PaymentSheets;
use App\Helper\DisbursementApiHelper;
use App\Models\Batch;
use App\Models\BatchPayment;
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

class ExportDisbursement implements FromView,WithTitle,WithEvents
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
        self::$disbursements = DB::table('batch_payments as bv')
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
                'dp.amount',
                'dp.tx_charge',
                'dp.withdrawal_fee',
                'dp.network_name',
				'dp.updated_at as payment_date',
                'dp.payment_status',
				'dp.payment_detail',
                DB::raw('(SELECT mpesa_receipt FROM tx_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status')
            )
            ->join('disbursement_payments as dp','dp.batch_no','=','bv.batch_no')
			->orderBy('dp.id', 'ASC')
            ->get();

        $summary = DisbursementApiHelper::getDisbursementStatus($this->batch_no,false,true);

        $summary['unknown'] = $summary['total'] -  $summary['successful'] -  $summary['failed'];
        $amounts = $summary['extended']; unset($summary['extended']); $entries = $summary;
        $amounts['unknown'] = $amounts['total'] -  $amounts['successful'] -  $amounts['failed'];

        $orgName  =  PaymentSheets::getOrganizationByBatchNumber($this->batch_no);

        $handlers  =  BatchPayment::query()->select('operator','handler')->where(['batch_no'=>$this->batch_no])->first();

        return view('exports.disbursements', [
            'disbursements' => self::$disbursements,
            'user_batch_no' => BatchPayment::query()->where(['batch_no'=>$this->batch_no])->first()->user_batch_no,
            'amounts' => $amounts,
            'entries' => $entries,
            'orgName' => $orgName,
            'handlers'=>$handlers,
            'batch_status' =>  Batch::getStatusName($entries['status']).($entries['status']==Batch::STATUS_FAILED?' - '.$entries['status_description']:''),
        ]);
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

        $event->sheet->getDelegate()->getStyle( 'A4:A7')->applyFromArray(
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

        $event->sheet->getDelegate()->getStyle( 'A9:H9')->applyFromArray(
            [
                'font'=>[
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
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

}
