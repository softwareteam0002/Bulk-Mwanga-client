<?php

namespace App\Imports;

use App\Models\BatchPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportPayment implements FromView,WithTitle
{

    public $batch_no;
    use Exportable;

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

        $data = DB::table('batch_payments as bv');

        if (Auth::user()->user_type == 2) {
            $data = $data->where(['bv.organization_id' => Auth::user()->organization_id]);
        }

        return $data->where(['bv.batch_no' => $this->batch_no])
            ->select('dp.batch_no','bv.user_batch_no', 'dp.first_name', 'dp.last_name', 'dp.phone_number', 'dp.amount', 'dp.payment_detail',
                'dp.network_name', 'dp.status_description')
            ->join('disbursement_payments as dp', 'dp.batch_no', '=', 'bv.batch_no')
			->orderBy('dp.id', 'ASC')
            ->get();

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Batch Number',
            'First Name',
            'Last Name',
            'Phone Number',
            'Amount',
            'Payment Detail',
            'Network Name',
            'Failure Reason'

        ];
    }

    /**
     * @return View
     */
    public function view(): View
    {

    }



    /**
     * @return string
     */
    public function title(): string
    {
        return "Batch No " . BatchPayment::query()->where(['batch_no'=>$this->batch_no])->first()->user_batch_no;
    }

}
