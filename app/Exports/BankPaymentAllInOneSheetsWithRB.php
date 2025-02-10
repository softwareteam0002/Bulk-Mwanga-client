<?php

namespace App\Exports;

use App\Helper\ModelDataHelper;
use App\Models\BankPaymentDisbursement;
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

class BankPaymentAllInOneSheetsWithRB implements FromView,WithTitle,WithEvents
{


    private  $startDate;
    private  $endDate;


    private  $organizationId;
    private static $disbursements = null;

    use Exportable, RegistersEventListeners;

    /**
     * PaymentAllInOneSheets constructor.
     * @param $startDate
     * @param $endDate
     * @param $organizationId
     */
    public function __construct($startDate, $endDate, $organizationId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->organizationId = $organizationId;
    }


    public function view(): View
    {
        $disbursements = DB::table('bank_batch_payments as bv')

            ->whereBetween(DB::raw('date(bv.created_at)'), [date('Y-m-d', strtotime($this->startDate)), date('Y-m-d', strtotime($this->endDate))])
            ->where(['organization_id' => $this->organizationId])
            ->select(
                'bv.id',
                'bv.user_batch_no',
                'bv.batch_no',
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
                'dp.updated_at as payment_date',
                'dp.payment_status',
                DB::raw('(SELECT mpesa_receipt FROM tx_bank_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
                DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status'),
				'dp.payment_detail'
            )
            ->join('bank_payment_disbursements as dp', 'dp.batch_no', '=', 'bv.batch_no')
//            ->leftJoin('tx_bank_disbursement as tx', 'tx.entry_id', '=', 'dp.id')

            ->get();

        $entries['total'] = $disbursements->count();


        $entries['processed'] = $disbursements->sum(function ($item) {
            return $item->payment_status != BankPaymentDisbursement::STATUS_NOT_PAID ? 1 : 0;
        });
        $entries['successful'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_PAID ? 1 : 0;
        });
        $entries['failed'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_ERROR ? 1 : 0;
        });

        $entries['unknown'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_SENT ? 1 : 0;
        });

        $amounts['total'] = $disbursements->sum(function ($item) {
            return $item->amount + $item->withdrawal_fee;
        });
        $amounts['processed'] = $disbursements->sum(function ($item) {
            return $item->payment_status != BankPaymentDisbursement::STATUS_NOT_PAID ? ($item->amount + $item->withdrawal_fee) : 0;
        });
        $amounts['successful'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_PAID ? ($item->amount + $item->withdrawal_fee) : 0;
        });
        $amounts['failed'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_ERROR ? ($item->amount + $item->withdrawal_fee) : 0;
        });

        $amounts['unknown'] = $disbursements->sum(function ($item) {
            return $item->payment_status == BankPaymentDisbursement::STATUS_SENT ? ($item->amount + $item->withdrawal_fee) : 0;
        });

        self::$disbursements = $disbursements;
        //        $handlers  =  BatchPayment::query()->select('operator','handler')->where(['batch_no'=>$this->batch])->first();
        $orgName  = ModelDataHelper::getOrganizationById($this->organizationId);

        return view('exports.bank.disbursements_one_sheets_with_rb', [
            'disbursements' => $disbursements,

            'amounts' => $amounts,
            'entries' => $entries,
            'orgName' => $orgName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,

        ]);
    }


    /**
     * @return string
     */
    public function title(): string
    {

        return ' Banks All In One Report ';
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
        $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray(
            [
                'font' => [
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


        $event->sheet->getDelegate()->getStyle('A3:A6')->applyFromArray(
            [
                'font' => [
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle('B3:H3')->applyFromArray(
            [
                'font' => [
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle('A9:J9')->applyFromArray(
            [
                'font' => [
                    'bold'      =>  true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $event->sheet->getDelegate()->getStyle('A8:J8')->applyFromArray(
            [
                'font' => [
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
        $event->sheet->getDelegate()->getStyle('A2:J2')->applyFromArray(
            [
                'font' => [
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
