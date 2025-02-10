<?php

namespace App\Http\Controllers\Helper;

use App\Exports\ExportAllInOneSheet;
use App\Exports\ExportMultiple;
use App\Exports\ExportWithRBMultiple;
use App\Exports\PaymentAllInOneSheets;
use App\Exports\PaymentAllInOneSheetsWithRB;
use App\Exports\PaymentWithBalance;
use App\Http\Controllers\Controller;
use App\Imports\ExportDisbursement;
use App\Imports\ExportDisbursementWithRb;
use App\Imports\ExportVerification;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\DisbursementOpeningBalance;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/* class that manage all downloads and exports.*/
class DownloadController extends Controller
{
    //


    public static function downloadExcelInOneSheetBYDate($startDate, $endDate, $organizationId)
    {
	
        return  (new PaymentAllInOneSheets($startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }


    public static function downloadExcelInOneSheetWithRBBYDate($startDate, $endDate, $organizationId)
    {
	
        return  (new PaymentAllInOneSheetsWithRB($startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }

    public static function downloadExcelWithBalance($startDate, $endDate, $organizationId)
    {
	
        return  (new PaymentWithBalance($startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }

    public function  downloadUploadExcelFormat(){


        $document_path = "disbursement_file.xlsx";

        if (isset($_POST['bank'])){

            $document_path = "bank_disbursement_file.xlsx";

        }

        if (!file_exists(public_path("/format/".$document_path))){

            Session::flash("alert-warning","An Error Occured, Try Again");
            return redirect()->back();
        }
        $file_path   = public_path("/format/".$document_path);

        return Response::download($file_path, $document_path, [

        ]);

    }

    public  function downloadBatch($batchNo){
        try {
            $batchNo = decrypt($batchNo);
            $user_batch_no = Batch::query()->where(['batch_no'=>$batchNo])->first()->user_batch_no;

        }catch (\Exception $de){


            return redirect()->back();
        }
        return  (new ExportVerification($batchNo))->download($batchNo.'.xlsx');
    }

    public  function downloadBatchDisbursement($batchNo){
        try {
            $batchNo = decrypt($batchNo);
        }catch (DecryptException $de){
            return redirect()->back();
        }
        return  (new ExportDisbursement($batchNo))->download($batchNo.'.xlsx');
    }

    public  static function downloadBatchPerBatch($batchNo){


        try {
            $batchNo = decrypt($batchNo);
        }catch (DecryptException $de){

            return redirect()->back();
        }

        $include_rb = $_POST['running_balance'] ?? 0;

        if (isset($_POST['excel'])){
            return self::downloadExcel($batchNo,$include_rb);
        }else if (isset($_POST['csv'])) {
            return self::downloadCsv($batchNo,$include_rb);
        }else{
            return self::downloadPdfPerBatch($batchNo,$include_rb);
        }
    }

    // functions that fetches reports to excel

    /**
     * @param $batchNo
     * @param $include_rb int|boolean include running balance
     * @return \Illuminate\Http\Response|BinaryFileResponse
     */
    public  static function downloadExcel($batchNo, $include_rb){
        return  ($include_rb?(new ExportDisbursementWithRb($batchNo)):(new ExportDisbursement($batchNo)))->download($batchNo.'.xlsx',Excel::XLSX);
    }
    // functions that fetches reports to csv

    public  static function downloadCsv($batchNo, $include_rb){
        return  ($include_rb?(new ExportDisbursementWithRb($batchNo)):(new ExportDisbursement($batchNo)))->download($batchNo.'.csv',Excel::CSV);
    }
    // functions that fetches reports to excel by date

    public  static function downloadExcelBYDate(array $batches,$startDate,$endDate,$organizationId){

         $document_path = $organizationId.time().'.xlsx';

    $check  =      (new ExportMultiple($batches,$startDate,$endDate,$organizationId))->store($document_path);


    if ($check) {
   
   // Session::flash('alert-success','Please download, will start soon');

        if (!file_exists(storage_path("app/".$document_path))){

            Session::flash("alert-warning","An Error Occured, Try Again");
            return redirect()->back();
        }
        $file_path   = storage_path("app/".$document_path);

        return Response::download($file_path, $document_path, [

        ]);


    }


   return back();

        // return  (new ExportMultiple($batches,$startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }

    public  static function downloadExcelWithRBBYDate(array $batches,$startDate,$endDate,$organizationId){

        return  (new ExportWithRBMultiple($batches,$startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }
    public  static function downloadExcelWithRBBYDatePDF(array $batches,$startDate,$endDate,$organizationId){

        return  (new ExportWithRBMultiple($batches,$startDate,$endDate,$organizationId))->download(time().'.pdf',Excel::DOMPDF);

    }
    // functions that fetches reports to csv by date

    public  static function downloadCsvBYDate(array $batches,$startDate,$endDate,$organizationId){


        return  (new ExportMultiple($batches,$startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::CSV);

    }
    // functions that fetches reports to pdf by date

    public  static function downloadPdfBYDate(array $batches,$startDate,$endDate,$organizationId){


        return  (new ExportMultiple($batches,$startDate,$endDate,$organizationId))->download(time().'.xlsx',Excel::XLSX);

    }

    public  static function downloadPdf($batchNo,$include_rb){

        $disbursements  =  DisbursementPayment::query()->where(['batch_no'=>$batchNo])->get();

        $paymentData  =  self::getHtml($disbursements,$include_rb);
        $html  = "<!doctype html>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>

    <html lang='en'>

    <head>
    <title>pdf</title>
    <link rel='stylesheet' href='".url(asset('public/css/bootstrap_report.min.css'))."'>

    <style>
     header {
                position: fixed;
                top: -50px;
                left: 0px;
                right: 0px;
                height: 60px;
                margin-bottom: 100px;

                /** Extra personal styles **/
//              background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }
</style>
    </head>

    <body>

<header>

      <div class='row'>

        <div class='col-md-12'>

        <table class='table'>

        <tbody>
        <tr style='background-color: #ED1C24;color: white;'>
        <th colspan='12'>Disbursement Payments Batch #: {$batchNo}</th>
        </tr>
        </tbody>
</table>
       </div>
       </div>
</header>

        $paymentData


    </body>
    </html>
";

        $dompdf = App::make('dompdf.wrapper');
        $dompdf->loadHTML($html);
        $dompdf->setPaper('a4', 'landscape');

//        return $pdf->stream();
        return $dompdf->stream($batchNo.".pdf", array("Attachment" => false));
    }

    public  static  function getHtml($payments,$include_rb){

        $table  =  "  <table class='table'>

        <tbody>
        <tr style='background-color: #ED1C24;color: white;'>
        <th colspan='12'>Disbursement Payments Batch #:</th>
        </tr>
        </tbody>
</table>
<table class='table table-bordered' style='margin-top: 30px;'>";
        $balance = 0;
        if (count($payments)>0){
            $batch_no = $payments[0]->batch_no;
            $bp = BatchPayment::query()->where(['batch_no'=>$batch_no])->first();
            $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id'=>$bp->id])->first();
            $balance = $ob_balance->organizationAccountBalance->available_balance ?? 0;

            $paySummary   = DB::table('batch_payments as bv')
                ->where(['bv.batch_no'=>$batch_no])
                ->select('bv.user_batch_no',
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
                    'dp.payment_status',
                    DB::raw('(SELECT mpesa_receipt FROM tx_disbursement WHERE entry_id=dp.id AND status="SUCCESS" LIMIT 1) as mpesa_receipt'),

                    DB::raw('IF(dp.status_description IS NULL,
                (CASE WHEN payment_status=1 THEN "Paid" WHEN payment_status=10 THEN "Sent"  WHEN payment_status=2 THEN "Failed" ELSE "Not processed" END),
                dp.status_description) as status')
                )
                ->join('disbursement_payments as dp','dp.batch_no','=','bv.batch_no')
                ->join('batch_processing as bp','bp.batch_id','=','bv.id')

                ->get();


//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $total  =   $paySummary->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();

            $entries['total'] = $paySummary->count();

//            dd($entries['total'] );

            $entries['processed'] = $paySummary->sum(function ($item){
                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?1:0;
            });
            $entries['successful'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_PAID?1:0;
            });
            $entries['failed'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_ERROR?1:0;
            });

            $entries['unknown'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_SENT?1:0;
            });

            $amounts['total'] = $paySummary->sum(function($item){
                return $item->amount + $item->withdrawal_fee;
            });
            $amounts['processed'] = $paySummary->sum(function ($item){
                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?($item->amount + $item->withdrawal_fee):0;
            });
            $amounts['successful'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_PAID?($item->amount + $item->withdrawal_fee):0;
            });
            $amounts['failed'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_ERROR?($item->amount + $item->withdrawal_fee):0;
            });

            $amounts['unknown'] = $paySummary->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_SENT?($item->amount + $item->withdrawal_fee):0;
            });
        }

        $table .="<thead><tr><th colspan='6'>DISBURSEMENTS - Batch No ".$batch_no."</th></tr>";
            $table .="<tr><th colspan='5'>Status</th><th>".Batch::getStatusName($bp->batch_status_id)."</th></tr>";
            $table .="<tr><th colspan='6'>SUMMARY</th></tr>";
        $table .="<tr><th colspan='1'></th><th>Total</th><th>Processed</th><th>Successful</th><th>Failed</th><th>On Hold</th></tr>";
        $table .="<tr><th>Entries</th><th>".$entries['successful']."</th><th>".$entries['processed']."</th><th>".$entries['successful']."</th><th>".$entries['failed']."</th><th>".$entries['unknown']."</th></tr>";
        $table .="<tr><th>Amount</th><th>".$amounts['total']."</th><th>".$amounts['processed']."</th><th>".$amounts['successful']."</th><th>".$amounts['failed']."</th><th>".$amounts['unknown']."</th></tr>";


        $table .="<tr><th>Initiator</th><th colspan='2'>".$bp->operator."</th><th>Approval</th><th colspan='2'>".$bp->handler."</th></tr>";
            $table .="<tr><th>Initiated date </th><th>".$bp->operator."</th><th>Approved date </th><th>".$bp->handler."</th><th>Completed date  </th><th>".$bp->batch_completed_date."</th></tr>";

        if ($include_rb){
            $table .=" <tr style='background-color: #ED1C24;color: white;'><th>#</th><th> Details</th><th>Amount</th><th>Acc. Balance</th>";
            $table .="<th colspan='2'>Status</th></tr> </thead><tbody>";
            foreach ($payments as $index=>$payment) {
                $span = 1;
                if ($payment->payment_status==1 && $payment->withdrawal_fee){
                    $span = 3;
                }elseif ($payment->payment_status==1){
                    $span = 2;
                }
                $index =$index+1;
                $table .= "<tr><td rowspan='$span' >$index</td><td >$payment->first_name $payment->last_name<br>$payment->phone_number ($payment->network_name)</td>";
                $table .= "<td>".number_format($payment->amount)."</td>";
                $table .="<td>".number_format(($balance-=$payment->amount))."</td>";
                $table .="<td colspan='2'>$payment->status_description</td></tr>";
                if ($payment->payment_status==1){
                    $table .= "<tr><td>Tx Charge</td>";
                    $table .= "<td>".number_format($payment->tx_charge)."</td>";
                    $table .="<td>".number_format(($balance-=$payment->tx_charge))."</td>";
                    $table .="<td></td></tr>";
                }
                if ($payment->withdrawal_fee){
                    $table .= "<tr><td>Withdraw Fee</td>";
                    $table .= "<td>".number_format($payment->withdrawal_fee)."</td>";
                    $table .="<td>".number_format(($balance-=$payment->withdrawal_fee))."</td>";
                    $table .="<td></td></tr>";
                }
            }
            $table.="</body></table>";
        }else{
            $table .=" <thead><tr style='background-color: #ED1C24;color: white;'><th>No</th> <th>Batch Number</th><th>First Name</th><th>Last Name</th><th>Phone Number</th><th>Amount</th><th>Payment Details</th>";
            $table .="<th>Network Name</th><th>Verification Status</th></tr> </thead><tbody>";

            foreach ($payments as $index=>$payment) {
                $index =$index+1;
                $table .= "<tr><td>$index</td><td>$payment->batch_no</td><td>$payment->first_name</td><td>$payment->last_name</td><td>$payment->phone_number</td>";
                $table .= "<td>$payment->amount</td><td>$payment->payment_details</td><td>$payment->network_name</td><td>$payment->status_description</td></tr>";
            }

            $table.="</body></table>";
        }

        return  $table;

    }

    public  static function MultdownloadPdf($start_date,$end_date,$include_rb){

        $batch  =  DB::select('call GetDisbursementByDateSP(?,?,?)',array($start_date,$end_date,Auth::user()->organization_id));

//        dd(Auth::user()->organization_id);

        $paymentData =  null;
        foreach ($batch as $bat) {

            $disbursements  =  DisbursementPayment::query()->where(['batch_no'=>$bat->batch_no])->get();

            $paymentData .= self::getHtml($disbursements, $include_rb);

        }
        $html  = "<!doctype html>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>

    <html lang='en'>

    <head>
    <title>pdf</title>
    <link rel='stylesheet' href='".url(asset('public/css/bootstrap_report.min.css'))."'>

    <style>
     header {
                position: fixed;
                top: -50px;
                left: 0px;
                right: 0px;
                height: 60px;
                margin-bottom: 100px;

                /** Extra personal styles **/
//              background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }
</style>
    </head>

    <body>

<header>

      <div class='row'>

        <div class='col-md-12'>




       </div>
       </div>
</header> $paymentData</body>
    </html>
";

        $dompdf = App::make('dompdf.wrapper');
        $dompdf->loadHTML($html);
        $dompdf->setPaper('a4', $include_rb?'portrait':'landscape');

//        return $pdf->stream();
        return $dompdf->stream(time().".pdf", array("Attachment" => false));
    }

    public  static function MultdownloadPdfAllInOne($start_date,$end_date,$include_rb){

        $batch  =  DB::select('call GetDisbursementByDateSP(?,?,?)',array($start_date,$end_date,Auth::user()->organization_id));

//        dd($batch);
//        dd(Auth::user()->organization_id);

        $paymentData =  null;
        foreach ($batch as $bat) {


            $disbursements  =  DisbursementPayment::query()->where(['batch_no'=>$bat->batch_no])->get();

            $paymentData .= self::getHtmlAllInOne($disbursements, $include_rb);

//            $paymentData .= $paymentData;


//            dd($paymentData);
        }
        $html  = "<html>

<style>
#tbl td, #tbl th {
  border: 1px solid #ddd;
  padding: 8px;
}
</style>
    <body>
 $paymentData</body>
    </html>
";

        $dompdf = App::make('dompdf.wrapper');
        $dompdf->loadHTML($html);

        $dompdf->setPaper('a4', $include_rb?'portrait':'landscape');

//        return $pdf->stream();
        return $dompdf->download(time().".pdf", array("Attachment" => false));
    }

    public  static  function getHtmlAllInOne($payments,$include_rb)
    {

        $table = "<table class='table table-bordered' id='tbl' style='margin-top: 30px;font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;'>";
        $balance = 0;
        if (count($payments) > 0) {
            $batch_no = $payments[0]->batch_no;

            $bp = BatchPayment::query()->where(['batch_no' => $batch_no])->first();
            $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id' => $bp->id])->first();
            $balance = $ob_balance->organizationAccountBalance->available_balance ?? 0;

//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $total  =   $paySummary->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();
//            $success  =   $paySummary->where(['payment_status'=>1])->count();

            if ($include_rb) {
                $table .= " <tr style='background-color: #ED1C24;color: white;'><th>#</th><th> Details</th><th>Amount</th><th>Acc. Balance</th>";
                $table .= "<th colspan='2'>Status</th><th>Batch no</th></tr> </thead><tbody>";
                foreach ($payments as $index => $payment) {
                    $span = 1;
                    if ($payment->payment_status == 1 && $payment->withdrawal_fee) {
                        $span = 3;
                    } elseif ($payment->payment_status == 1) {
                        $span = 2;
                    }
                    $index = $index + 1;
                    $table .= "<tr><td rowspan='$span' >$index</td><td >$payment->first_name $payment->last_name<br>$payment->phone_number ($payment->network_name)</td>";
                    $table .= "<td>" . number_format($payment->amount) . "</td>";
                    $table .= "<td>" . number_format(($balance -= $payment->amount)) . "</td>";
                    $table .= "<td colspan='2'>$payment->status_description</td><th>$batch_no</th></tr>";
                    if ($payment->payment_status == 1) {
                        $table .= "<tr><td>Tx Charge</td>";
                        $table .= "<td>" . number_format($payment->tx_charge) . "</td>";
                        $table .= "<td>" . number_format(($balance -= $payment->tx_charge)) . "</td>";
                        $table .= "<td></td></tr>";
                    }
                    if ($payment->withdrawal_fee) {
                        $table .= "<tr><td>Withdraw Fee</td>";
                        $table .= "<td>" . number_format($payment->withdrawal_fee) . "</td>";
                        $table .= "<td>" . number_format(($balance -= $payment->withdrawal_fee)) . "</td>";
                        $table .= "<td></td></tr>";
                    }
                }
                $table .= "</body></table>";
            } else {
                $table .= " <thead><tr style='background-color: #ED1C24;color: white;'><th>No</th> <th>Batch Number</th><th>First Name</th><th>Last Name</th><th>Phone Number</th><th>Amount</th><th>Payment Details</th>";
                $table .= "<th>Network Name</th><th>Verification Status</th></tr> </thead><tbody>";

                foreach ($payments as $index => $payment) {
                    $index = $index + 1;
                    $table .= "<tr><td>$index</td><td>$payment->batch_no</td><td>$payment->first_name</td><td>$payment->last_name</td><td>$payment->phone_number</td>";
                    $table .= "<td>$payment->amount</td><td>$payment->payment_details</td><td>$payment->network_name</td><td>$payment->status_description</td></tr>";
                }

                $table .= "</body></table>";
            }

            return $table;

        }
    }


    public  static  function getHtmlPerBatch($payments,$include_rb){

        $table  =  "  <table class='table'>

        <tbody>
        <tr style='background-color: #ED1C24;color: white;'>
        <th colspan='12'>Disbursement Payments Batch #:</th>
        </tr>
        </tbody>
</table>
<table class='table table-bordered' style='margin-top: 30px;'>";
        $balance = 0;
        if (count($payments)>0){
            $batch_no = $payments[0]->batch_no;

            $bp = BatchPayment::query()->where(['batch_no'=>$batch_no])->first();
            $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id'=>$bp->id])->first();
            $balance = $ob_balance->organizationAccountBalance->available_balance ?? 0;


            $entries['total'] = $payments->count();

            $entries['processed'] = $payments->sum(function ($item){
                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?1:0;
            });
            $entries['successful'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_PAID?1:0;
            });
            $entries['failed'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_ERROR?1:0;
            });

            $entries['unknown'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_SENT?1:0;
            });

            $amounts['total'] = $payments->sum(function($item){
                return $item->amount + $item->withdrawal_fee;
            });
            $amounts['processed'] = $payments->sum(function ($item){
                return $item->payment_status!=DisbursementPayment::STATUS_NOT_PAID?($item->amount + $item->withdrawal_fee):0;
            });
            $amounts['successful'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_PAID?($item->amount + $item->withdrawal_fee):0;
            });
            $amounts['failed'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_ERROR?($item->amount + $item->withdrawal_fee):0;
            });

            $amounts['unknown'] = $payments->sum(function ($item){
                return $item->payment_status==DisbursementPayment::STATUS_SENT?($item->amount + $item->withdrawal_fee):0;
            });
        }

        $table .="<thead><tr><th colspan='6'>DISBURSEMENTS - Batch No ".$batch_no."</th></tr>";
        $table .="<tr><th colspan='5'>Status</th><th>".Batch::getStatusName($bp->batch_status_id)."</th></tr>";
        $table .="<tr><th colspan='6'>SUMMARY</th></tr>";
        $table .="<tr><th colspan='1'></th><th>Total</th><th>Processed</th><th>Successful</th><th>Failed</th><th>On Hold</th></tr>";
        $table .="<tr><th>Entries</th><th>".$entries['successful']."</th><th>".$entries['processed']."</th><th>".$entries['successful']."</th><th>".$entries['failed']."</th><th>".$entries['unknown']."</th></tr>";
        $table .="<tr><th>Amount</th><th>".$amounts['total']."</th><th>".$amounts['processed']."</th><th>".$amounts['successful']."</th><th>".$amounts['failed']."</th><th>".$amounts['unknown']."</th></tr>";


        $table .="<tr><th>Initiator</th><th colspan='2'>".$bp->operator."</th><th>Approval</th><th colspan='2'>".$bp->handler."</th></tr>";
        $table .="<tr><th>Initiated date </th><th>".$bp->operator."</th><th>Approved date </th><th>".$bp->handler."</th><th>Completed date  </th><th>".$bp->batch_completed_date."</th></tr>";

        if ($include_rb){
            $table .=" <tr style='background-color: #ED1C24;color: white;'><th>#</th><th> Details</th><th>Amount</th><th>Acc. Balance</th>";
            $table .="<th colspan='2'>Status</th></tr> </thead><tbody>";
            foreach ($payments as $index=>$payment) {
                $span = 1;
                if ($payment->payment_status==1 && $payment->withdrawal_fee){
                    $span = 3;
                }elseif ($payment->payment_status==1){
                    $span = 2;
                }
                $index =$index+1;
                $table .= "<tr><td rowspan='$span' >$index</td><td >$payment->first_name $payment->last_name<br>$payment->phone_number ($payment->network_name)</td>";
                $table .= "<td>".number_format($payment->amount)."</td>";
                $table .="<td>".number_format(($balance-=$payment->amount))."</td>";
                $table .="<td colspan='2'>$payment->status_description</td></tr>";
                if ($payment->payment_status==1){
                    $table .= "<tr><td>Tx Charge</td>";
                    $table .= "<td>".number_format($payment->tx_charge)."</td>";
                    $table .="<td>".number_format(($balance-=$payment->tx_charge))."</td>";
                    $table .="<td></td></tr>";
                }
                if ($payment->withdrawal_fee){
                    $table .= "<tr><td>Withdraw Fee</td>";
                    $table .= "<td>".number_format($payment->withdrawal_fee)."</td>";
                    $table .="<td>".number_format(($balance-=$payment->withdrawal_fee))."</td>";
                    $table .="<td></td></tr>";
                }
            }
            $table.="</body></table>";
        }

        else{
            $table .=" <thead><tr style='background-color: #ED1C24;color: white;'><th>No</th> <th>Batch Number</th><th>First Name</th><th>Last Name</th><th>Phone Number</th><th>Amount</th><th>Payment Details</th>";
            $table .="<th>Network Name</th><th>Verification Status</th></tr> </thead><tbody>";

            foreach ($payments as $index=>$payment) {
                $index =$index+1;
                $table .= "<tr><td>$index</td><td>$payment->batch_no</td><td>$payment->first_name</td><td>$payment->last_name</td><td>$payment->phone_number</td>";
                $table .= "<td>$payment->amount</td><td>$payment->payment_details</td><td>$payment->network_name</td><td>$payment->status_description</td></tr>";
            }

            $table.="</body></table>";
        }

        return  $table;

    }

    public  static function downloadPdfPerBatch($batchNo,$include_rb){

        $disbursements  =  DisbursementPayment::query()->where(['batch_no'=>$batchNo])->get();

        $paymentData  =  self::getHtmlPerBatch($disbursements,$include_rb);
        $html  = "<!doctype html>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>

    <html lang='en'>

    <head>
    <title>pdf</title>
    <link rel='stylesheet' href='".url(asset('public/css/bootstrap_report.min.css'))."'>

    <style>
     header {
                position: fixed;
                top: -50px;
                left: 0px;
                right: 0px;
                height: 60px;
                margin-bottom: 100px;

                /** Extra personal styles **/
//              background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }
</style>
    </head>

    <body>

<header>

      <div class='row'>

        <div class='col-md-12'>

        <table class='table'>

        <tbody>
        <tr style='background-color: #ED1C24;color: white;'>
        <th colspan='12'>Disbursement Payments Batch #: {$batchNo}</th>
        </tr>
        </tbody>
</table>
       </div>
       </div>
</header>

        $paymentData


    </body>
    </html>
";

        $dompdf = App::make('dompdf.wrapper');
        $dompdf->loadHTML($html);
        $dompdf->setPaper('a4', 'landscape');

//        return $pdf->stream();
        return $dompdf->stream($batchNo.".pdf", array("Attachment" => false));
    }
}
