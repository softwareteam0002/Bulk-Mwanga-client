<?php

namespace App\Http\Controllers\Report;

use App\Exports\BankExportWithRBMultiplePDF;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\BankDownloadController;
use App\Imports\ExportDisbursement;
use App\Models\BankBatchPayment;
use App\Models\BankPaymentDisbursement;
use App\Models\ConstantHelper;
use App\Models\Organization;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use test\Mockery\ReturnTypeObjectTypeHint;

//use Barryvdh\DomPDF\PDF;

class BankReportController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    private static function getExcelWithBalance($startDate, $endDate, $organizationId)
    {
        return BankDownloadController::downloadExcelWithBalance($startDate, $endDate, $organizationId);

    }

    private static function getAllPaymentInOneSheet($startDate, $endDate, $organizationId)
    {
        return BankDownloadController::downloadExcelInOneSheetBYDate($startDate, $endDate, $organizationId);
    }

    public function index()
    {

        return back();

    }

    public function disbursementPerOrganization()
    {


        return view('reports.bank.by_organization');

    }

    /* get view to select date for disbursement by date for report purpose here */

    public function disbursementByDate()
    {

        $batchPayment = [];

        $organizations = Organization::query()->get();

        return view('reports.bank.batches_disbursement_by_date', compact('organizations', 'batchPayment'));

    }

    /* fetch  disbursement by date for report purpose here */
    public function fetchDisbursementByDate(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        $organizationId = Auth::user()->organization_id;
        if ($type == 20) {

            return self::getAllPaymentInOneSheet($startDate, $endDate, $organizationId);
        } elseif ($type == 40) {

            return BankDownloadController::downloadExcelInOneSheetWithRBBYDate($startDate, $endDate, $organizationId);
        }


        $batchPayment = self::getPaymentsByDate($startDate, $endDate, $organizationId);
        $organizations = Organization::query()->get();

        return view('reports.bank.batches_disbursement_by_date', compact('organizationId', 'organizationId', 'organizations', 'startDate', 'endDate', 'batchPayment', 'type'));

    }
    /* export  disbursement by date for report purpose here */

    public function exportMultipleDisbursementByDate(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->post('type');

        $organizationId = $request->organizationId;

        if (Auth::user()->user_type === 2) {
            $organizationId = Auth::user()->organization_id;
        }


        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $batches = $request->batchNo;

        if (empty($batches)) {
            Session::flash('alert-danger', 'No Report Found For This Search');
            return back();

        }
        if (isset($_POST['excel'])) {

            return $type == 30 ? BankDownloadController::downloadExcelWithRBBYDate($batches, $startDate, $endDate, $organizationId) :
                BankDownloadController::downloadExcelBYDate($batches, $startDate, $endDate, $organizationId);

        } else if (isset($_POST['csv'])) {

            return BankDownloadController::downloadCsvBYDate($batches, $startDate, $endDate, $organizationId);

        } else if (isset($_POST['pdf'])) {

            return (new BankExportWithRBMultiplePDF($batches, $startDate, $endDate, $organizationId))->download(time() . '.pdf', Excel::DOMPDF);
            //
//            $disbursements = DB::table('bank_batch_payments as bv')
//                ->where(['bv.batch_no'=>$this->batch ])
//                ->whereBetween(DB::raw('date(bv.created_at)'),[$startDate,$endDate)
//                ->select('bv.user_batch_no',
//                    'dp.first_name',
//                    'dp.last_name',
//                    'dp.phone_number',
//                    'bv.batch_completed_date',
//                    'bv.approved_date',
//                    'bv.created_at as initiated_date',
//                    'dp.amount',
//                    'dp.tx_charge',
//                    'dp.withdrawal_fee',
//                    'dp.bank',
//                    'dp.payment_status',
//                    DB::raw('(SELECT mpesa_receipt FROM tx_bank_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),
//
//                    DB::raw('IF(dp.status_description IS NULL,
//                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
//                dp.status_description) as status')
//                )
//                ->join('bank_payment_disbursements as dp','dp.batch_no','=','bv.batch_no')
//                ->get();
//
//
//            $entries['total'] = $disbursements->count();
//
//
//            $entries['processed'] = $disbursements->sum(function ($item){
//                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?1:0;
//            });
//            $entries['successful'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_PAID?1:0;
//            });
//            $entries['failed'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_ERROR?1:0;
//            });
//
//            $entries['unknown'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_SENT?1:0;
//            });
//
//            $amounts['total'] = $disbursements->sum(function($item){
//                return $item->amount + $item->withdrawal_fee;
//            });
//            $amounts['processed'] = $disbursements->sum(function ($item){
//                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?($item->amount + $item->withdrawal_fee):0;
//            });
//            $amounts['successful'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_PAID?($item->amount + $item->withdrawal_fee):0;
//            });
//            $amounts['failed'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_ERROR?($item->amount + $item->withdrawal_fee):0;
//            });
//
//            $amounts['unknown'] = $disbursements->sum(function ($item){
//                return $item->payment_status==DisbursementPayment::STATUS_SENT?($item->amount + $item->withdrawal_fee):0;
//            });
//
//
//            self::$disbursements = $disbursements;
//
//            $handlers  =  BankBatchPayment::query()->select('operator','handler')->where(['batch_no'=>$this->batch])->first();
//            $orgName  = self::getOrganizationByBatchNumber($this->batch);
//            $bp = BankBatchPayment::query()->where(['batch_no'=>$this->batch])->first();
//            $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id'=>$bp->id])->where('transaction_type','!=','MNO')->first();
//            $balance = $ob_balance->organizationAccountBalance->available_balance??0;
//            $pdf = app('dompdf.wrapper');
//            $pdf->loadView('reports.data_by_organization');
//
//            return $pdf->stream('2222.pdf');


        }
    }

    /* return   disbursement by date for report purpose here */

    public static function getPaymentsByDate($tartDate, $endDate, $organizationId)
    {

        $from = date('Y-m-d', strtotime($tartDate));
        $to = date('Y-m-d', strtotime($endDate));

        $data = BankBatchPayment::query()->with('organization');
        if ($organizationId !== ConstantHelper::ALL_ORGANIZATION_GET_REPORT) {
            $data = $data->where(['organization_id' => $organizationId]);
        }
        $data = $data->whereBetween(DB::raw('created_at'), [$from, $to])->get();

        return $data;

    }

    public function disbursementPerBatch()
    {

        $batchPayment = [];

        return view('reports.bank.disbursement_per_batch', compact('batchPayment'));

    }

    public function getDisbursementPerOrganization(Request $request)
    {

        $shortCode = $request->shortCode;

        if (isset($_POST['excel'])) {

            $now = Carbon::now('Africa/Nairobi');

            $checkResult = DB::table('batch_payments')
                ->where('short_code', '=', $shortCode)
                ->first();

            if (!$checkResult) {

                Session::flash('alert-warning', 'No Data Available.');
                return redirect()->back();

            }

            return (new ExportDisbursement($shortCode))->download($now . '.xlsx');

        }

        if (isset($_POST['pdf'])) {

            $data = DB::table('batch_payments as bp')->where('bp.short_code', '=', 200)
                ->select(
                    'dp.first_name',
                    'dp.last_name',
                    'dp.phone_number',
                    'dp.amount',
                    'dp.zone',
                    'dp.updated_at as payment_date'
                )
                ->join('disbursement_payments as dp', 'dp.batch_no', '=', 'bp.batch_no')
                ->get();

            //return response()->json($data);

            $pdf = PDF::loadView('reports.data_by_organization', $data);
            $file = 'data-' . Carbon::now() . '.pdf';

            return $pdf->download($file);

        }

        Session::flash('alert-waring', 'Invalid Request');
        return redirect()->back();
    }


    public function getDisbursementByDate(Request $request)
    {

        $batchNo = $request->batchNumber;

        $batch = self::getPaymentPerBatch($batchNo);


        Session::flash('alert-waring', 'Invalid Request');
        return redirect()->back();

    }


    public static function getPaymentPerBatch($batchNo)
    {


        $batchPayment = BankBatchPayment::query()->where(['batch_no' => $batchNo])->first();

        return $batchPayment;

    }


    public function fetchDisbursementPerBatch(Request $request)
    {

        $batchNo = $request->batchNumber;

        $batchPayment = BankBatchPayment::query();
        $batchPayment = $batchPayment->where(['user_batch_no' => $batchNo, 'organization_id' => Auth::user()->organization_id]);
        $batchPayment = $batchPayment->first();
        return view('reports.bank.disbursement_per_batch', compact('batchPayment'));
    }

    public function viewAllInBatch($batch_no)
    {

        try {
            $batch_no = decrypt($batch_no);
        } catch (DecryptException $de) {

            return redirect()->back();

        }

        $disbursementsPayments = BankPaymentDisbursement::query()->where(['batch_no' => $batch_no])->get();


        //        return response()->json($disbursementsPayments);
        $entries = count($disbursementsPayments);

        $user_batch = BankBatchPayment::query()->where(['batch_no' => $batch_no])->first();

        $user_batch_no = $user_batch->user_batch_no;

        $amount = BankPaymentDisbursement::query()->where(['batch_no' => $batch_no])->get()->sum('amount');


        return view('reports.bank.view_all_member_in_batch', compact('user_batch_no', 'entries', 'amount', 'batch_no', 'disbursementsPayments'));
    }

    public function downloadAllInBatch($batchNo)
    {

        return BankDownloadController::downloadBatchPerBatch($batchNo);

    }

}
