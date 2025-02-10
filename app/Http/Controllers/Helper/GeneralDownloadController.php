<?php

namespace App\Http\Controllers\Helper;

use App\Exports\BankExportMultiple;
use App\Exports\BankExportWithRBMultiple;
use App\Exports\BankPaymentAllInOneSheetsWithRB;
use App\Exports\ExportAllInOneSheet;
use App\Exports\ExportMultiple;
use App\Exports\GeneralPaymentAllInOneSheets;
use App\Exports\PaymentWithBalance;
use App\Http\Controllers\Controller;
use App\Imports\ExportBankDisbursement;
use App\Imports\ExportBankDisbursementWithRb;
use App\Imports\ExportBankVerification;
use App\Imports\ExportDisbursement;
use App\Imports\ExportDisbursementWithRb;
use App\Models\BankVerificationBatch;
use App\Models\BatchPayment;
use App\Models\DisbursementOpeningBalance;
use App\Models\DisbursementPayment;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/* class that manage all downloads and exports.*/

class GeneralDownloadController extends Controller
{
    //

    public static function downloadExcelInOneSheetBYDate($startDate, $endDate, $organizationId)
    {
        return (new GeneralPaymentAllInOneSheets($startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }

    public static function downloadExcelInOneSheetWithRBBYDate($startDate, $endDate, $organizationId){
        return (new BankPaymentAllInOneSheetsWithRB($startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }

    public static function downloadExcelWithBalance($startDate, $endDate, $organizationId)
    {
        return (new PaymentWithBalance($startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }
    

    public  function downloadBatch($batchNo)
    {
        try {
            $batchNo = decrypt($batchNo);


            $user_batch_no = BankVerificationBatch::query()->where(['batch_no' => $batchNo])->first()->user_batch_no;
        } catch (\Exception $de) {


            return redirect()->back();
        }
        return (new ExportBankVerification($batchNo))->download($user_batch_no . '.xlsx');
    }

    public  function downloadBatchDisbursement($batchNo)
    {
        try {
            $batchNo = decrypt($batchNo);
        } catch (DecryptException $de) {
            return redirect()->back();
        }
        return (new ExportBankDisbursement($batchNo))->download($batchNo . '.xlsx');
    }

    public  static function downloadBatchPerBatch($batchNo)
    {


        try {
            $batchNo = decrypt($batchNo);
        } catch (DecryptException $de) {

            return redirect()->back();
        }

        $include_rb = $_POST['running_balance'] ?? 0;

        if (isset($_POST['excel'])) {
            return self::downloadExcel($batchNo, $include_rb);
        } else if (isset($_POST['csv'])) {
            return self::downloadCsv($batchNo, $include_rb);
        } else {
            return self::downloadPdf($batchNo, $include_rb);
        }
    }

    // functions that fetches reports to excel

    /**
     * @param $batchNo
     * @param $include_rb int|boolean include running balance
     * @return \Illuminate\Http\Response|BinaryFileResponse
     */
    public  static function downloadExcel($batchNo, $include_rb)
    {

        return ($include_rb ? (new ExportBankDisbursementWithRb($batchNo)) : (new ExportDisbursement($batchNo)))->download($batchNo . '.xlsx', Excel::XLSX);
    }
    // functions that fetches reports to csv

    public  static function downloadCsv($batchNo, $include_rb)
    {
        return ($include_rb ? (new ExportDisbursementWithRb($batchNo)) : (new ExportDisbursement($batchNo)))->download($batchNo . '.csv', Excel::CSV);
    }
    // functions that fetches reports to excel by date

    public  static function downloadExcelBYDate(array $batches, $startDate, $endDate, $organizationId){

        return (new BankExportMultiple($batches, $startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }

    public  static function downloadExcelWithRBBYDate(array $batches, $startDate, $endDate, $organizationId){
        return (new BankExportWithRBMultiple($batches, $startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }
    // functions that fetches reports to csv by date

    public  static function downloadCsvBYDate(array $batches, $startDate, $endDate, $organizationId)
    {


        return (new ExportMultiple($batches, $startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::CSV);
    }
    // functions that fetches reports to pdf by date

    public  static function downloadPdfBYDate(array $batches, $startDate, $endDate, $organizationId)
    {


        return (new ExportMultiple($batches, $startDate, $endDate, $organizationId))->download(time() . '.xlsx', Excel::XLSX);
    }

    public  static function downloadPdf($batchNo, $include_rb)
    {




        $disbursements  =  DisbursementPayment::query()->where(['batch_no' => $batchNo])->get(); //this is a bug

        $paymentData  =  self::getHtml($disbursements, $include_rb);
        $html  = "<!doctype html>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>

    <html lang='en'>

    <head>
    <title>pdf</title>
    <link rel='stylesheet' href='" . url(asset('public/css/bootstrap_report.min.css')) . "'>

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
        $dompdf->setPaper('a4', $include_rb ? 'portrait' : 'landscape');

        //        return $pdf->stream();
        return $dompdf->stream($batchNo . ".pdf", array("Attachment" => false));
    }

    public  static  function getHtml($payments, $include_rb)
    {

        $table  =  "  <table class='table table-bordered' style='margin-top: 30px;'>";
        $balance = 0;
        if (count($payments) > 0) {
            $batch_no = $payments[0]->batch_no;
            $bp = BatchPayment::query()->where(['batch_no' => $batch_no])->first();
            $ob_balance = DisbursementOpeningBalance::query()->where(['batch_id' => $bp->id])->first();
            $balance = $ob_balance->organizationAccountBalance->available_balance ?? 0;
        }
        if ($include_rb) {
            $table .= " <thead><tr style='background-color: #ED1C24;color: white;'><th>#</th><th> Details</th><th>Amount</th><th>Acc. Balance</th>";
            $table .= "<th>Status</th></tr> </thead><tbody>";
            foreach ($payments as $index => $payment) {
                $span = 1;
                if ($payment->payment_status == 1 && $payment->withdrawal_fee) {
                    $span = 3;
                } elseif ($payment->payment_status == 1) {
                    $span = 2;
                }
                $index = $index + 1;
                $table .= "<tr><td rowspan='$span'>$index</td><td>$payment->first_name $payment->last_name<br>$payment->phone_number ($payment->network_name)</td>";
                $table .= "<td>" . number_format($payment->amount) . "</td>";
                $table .= "<td>" . number_format(($balance -= $payment->amount)) . "</td>";
                $table .= "<td>$payment->status_description</td></tr>";
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

        return  $table;
    }
}
