<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\DownloadController;
use App\Imports\ExportDisbursement;
use App\Models\BatchPayment;
use App\Models\ConstantHelper;
use App\Models\DisbursementPayment;
use App\Models\Organization;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use test\Mockery\ReturnTypeObjectTypeHint;

//use Barryvdh\DomPDF\PDF;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    private static function getExcelWithBalance($startDate, $endDate, $organizationId)
    {
        return DownloadController::downloadExcelWithBalance($startDate, $endDate, $organizationId);

    }

    private static function getAllPaymentInOneSheet($startDate, $endDate, $organizationId)
    {
        return DownloadController::downloadExcelInOneSheetBYDate($startDate, $endDate, $organizationId);
    }

    public function index()
    {

        return back();

    }

    public function disbursementPerOrganization()
    {


        return view('reports.by_organization');

    }

    /* get view to select date for disbursement by date for report purpose here */

    public function disbursementByDate()
    {

        $batchPayment = [];

        $organizations = Organization::query()->get();

        return view('reports.batches_disbursement_by_date', compact('organizations', 'batchPayment'));

    }

    /* fetch  disbursement by date for report purpose here */
    public function fetchDisbursementByDate(Request $request)
    {

        set_time_limit(600);
        ini_set('memory_limit', '4095M');

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;

        $organizationId = Auth::user()->organization_id;
        if ($type == 20) {

            return self::getAllPaymentInOneSheet($startDate, $endDate, $organizationId);

        } elseif ($type == 40) {

            if (isset($_POST['pdf'])) {
                return DownloadController::MultdownloadPdfAllInOne(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)), 30);

            }
            return DownloadController::downloadExcelInOneSheetWithRBBYDate($startDate, $endDate, $organizationId);
        }

        $batchPayment = self::getPaymentsByDate($startDate, $endDate, $organizationId);

        //        dd($batchPayment);
        $organizations = Organization::query()->get();

        return view('reports.batches_disbursement_by_date', compact('organizationId', 'organizations', 'startDate', 'endDate', 'batchPayment', 'type'));

    }
    /* export  disbursement by date for report purpose here */

    public function exportMultipleDisbursementByDate(Request $request)
    {

        ini_set('max_execution_time', '0'); // for infinite time of execution

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

            //dd(22);

            return $type == 30 ? DownloadController::downloadExcelWithRBBYDate($batches, $startDate, $endDate, $organizationId) :
                DownloadController::downloadExcelBYDate($batches, $startDate, $endDate, $organizationId);

        } else if (isset($_POST['csv'])) {

            return DownloadController::downloadCsvBYDate($batches, $startDate, $endDate, $organizationId);

        } else if (isset($_POST['pdf'])) {


            return DownloadController::MultdownloadPdf($startDate, $endDate, 30);



        }
    }

    /* return   disbursement by date for report purpose here */

    public static function getPaymentsByDate($tartDate, $endDate, $organizationId)
    {

        $from = date('Y-m-d', strtotime($tartDate));
        $to = date('Y-m-d', strtotime($endDate));

        $data = DB::table('batch_payments as bp')->select('bp.created_at', 'bp.batch_status_id', 'bp.total_amount', 'bp.user_batch_no', 'o.name', 'bp.batch_no')
            ->join('organizations as o', 'o.id', '=', 'bp.organization_id');
        if ($organizationId !== ConstantHelper::ALL_ORGANIZATION_GET_REPORT) {
            $data = $data->where(['organization_id' => $organizationId]);
        }
        $data = $data->whereDate('bp.created_at', '>=', $from)
            ->whereDate('bp.created_at', '<=', $to)
            ->get();

        //$data =   DB::select('call GetDisbursementByDateSP(?,?,?)', array($from, $to, $organizationId));

        return $data;
    }

    public function disbursementPerBatch()
    {

        $batchPayment = [];

        return view('reports.disbursement_per_batch', compact('batchPayment'));

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

        $batchPayment = BatchPayment::query()->where(['batch_no' => $batchNo])->first();

        return $batchPayment;

    }


    public function fetchDisbursementPerBatch(Request $request)
    {
        $batchNo = $request->batchNumber;
        $batchPayment = BatchPayment::query();
        $batchPayment = $batchPayment->where(['user_batch_no' => $batchNo, 'organization_id' => Auth::user()->organization_id]);
        $batchPayment = $batchPayment->first();
        return view('reports.disbursement_per_batch', compact('batchPayment'));
    }

    public function viewAllInBatch($batch_no)
    {

        try {
            $batch_no = decrypt($batch_no);
        } catch (DecryptException $de) {

            return redirect()->back();

        }

        $disbursementsPayments = DisbursementPayment::query()->where(['batch_no' => $batch_no])->get();


        //        return response()->json($disbursementsPayments);
        $entries = count($disbursementsPayments);

        $user_batch = BatchPayment::query()->where(['batch_no' => $batch_no])->first();

        $user_batch_no = $user_batch->user_batch_no;

        $amount = DisbursementPayment::query()->where(['batch_no' => $batch_no])->get()->sum('amount');


        return view('reports.view_all_member_in_batch', compact('user_batch_no', 'entries', 'amount', 'batch_no', 'disbursementsPayments'));
    }

    public function downloadAllInBatch($batchNo)
    {


        return DownloadController::downloadBatchPerBatch($batchNo);

    }

    public static function mailReport($start_date, $end_date, $email)
    {
        set_time_limit(500);



        Mail::send(
            'mail.bcx_daily_summary',
            $data,
            function ($message) use ($email) {
                $message->to($email)
                    ->from("noreply@bcx.co.tz", "Disbursement B2C")
                    ->subject('Transaction Report ' . date("d-m-Y"));
            }
        );

        return "End of execution.";
        //usage  ReportsController::mailReport('2020-02-01', '2020-02-17', 'pdf', 'BCX', 'evancekinging@gmail.com');

    }

}
