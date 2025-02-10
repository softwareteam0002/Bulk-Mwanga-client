<?php
namespace App\Http\Controllers\Report;

use App\Exports\InternalReportTransactionsExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use Excel;
use Maatwebsite\Excel\Excel as BaseExcel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class InternalReportController extends Controller{
    public static function generateReport(){

        $date = date('Y-m-d');
        $start_date = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $date) ) ));
        $end_date = $start_date;

        /**
         * B2C Transactions
         */
        $transactions = DB::select("
            SELECT 
                tx.id as tx_id,
                tx.created_at,
                first_name,
                last_name,
                dp.amount,
                CONCAT(dp.phone_number, ' (', dp.network_name, ')') as account_name,
                tx_charge,
                tx.mpesa_receipt,
                tx.short_code,
                o.name as organization_name
            FROM 
                disbursement_payments as dp
                INNER JOIN 
                    batch_payments as bp on bp.batch_no = dp.batch_no
                INNER JOIN 
                    organizations as o on o.id = bp.organization_id
                INNER JOIN 
                    batch_processing as bpp on bpp.batch_id = bp.id
                INNER JOIN 
                tx_disbursement as tx on tx.batch_processing_id = bpp.id
            WHERE 
                dp.payment_status = 1 AND
                tx.entry_id = dp.id AND
                bpp.operation='DISBURSE' AND	
                date(tx.created_at)<='$end_date' AND
                date(tx.created_at)>='$start_date'
            ORDER BY 
                dp.id DESC;
        ");

        $transaction_volume = count($transactions);
        $transaction_value = 0;
        $charges_value = 0;
        foreach($transactions as $transaction){
            $transaction_value = ($transaction_value + $transaction->amount);
            $charges_value = ($charges_value + $transaction->tx_charge);
        }

        //create the attachments
        $file_name = "B2C Transaction Report $start_date - $end_date";
        //create an xls report
        $excel = new InternalReportTransactionsExport();
        $excel->transactions = $transactions;
        $attachment =  Excel::raw($excel, BaseExcel::XLSX);
        $file_name .=".xlsx";

        /**
         * B2B Transactions
         */
        $transactions_b2b = DB::select("
                SELECT 
                    tx.id as tx_id,
                    tx.created_at,
                    first_name,
                    last_name,
                    dp.amount,
                    CONCAT(dp.account_number, ' (', dp.bank, ')') as account_name,
                    tx.phone_number,
                    tx_charge,
                    tx.mpesa_receipt,
                    tx.short_code,
                    o.name as organization_name
                FROM 
                    bank_payment_disbursements as dp
                    INNER JOIN 
                        bank_batch_payments as bp on bp.batch_no = dp.batch_no
                    INNER JOIN 
                        organizations as o on o.id = bp.organization_id
                    INNER JOIN 
                        bank_batch_processing as bpp on bpp.batch_id = bp.id
                    INNER JOIN 
                    tx_bank_disbursement as tx on tx.batch_processing_id = bpp.id
                WHERE 
                    dp.payment_status = 1 AND
                    tx.entry_id = dp.id AND
                    bpp.operation='DISBURSE'  AND	
                    date(tx.created_at)<='$end_date' AND
                    date(tx.created_at)>='$start_date'
                ORDER BY 
                    dp.id DESC;
        ");

        $transaction_volume_b2b = count($transactions_b2b);
        $transaction_value_b2b = 0;
        $charges_value_b2b = 0;
        foreach($transactions_b2b as $transaction_b2b){
            $transaction_value_b2b = ($transaction_value_b2b + $transaction_b2b->amount);
            $charges_value_b2b = ($charges_value_b2b + $transaction_b2b->tx_charge);
        }

        //create the attachments
        $file_name_b2b = "B2B Transaction Report $start_date - $end_date";
        //create an xls report
        $excel_b2b = new InternalReportTransactionsExport();
        $excel_b2b->transactions = $transactions_b2b;
        $attachment_b2b =  Excel::raw($excel_b2b, BaseExcel::XLSX);
        $file_name_b2b .=".xlsx";

        
        //send the email to users
        $emails = "innovation@ubx.co.tz,godson.mandla@ubx.co.tz,m-pesabusiness@vodacom.co.tz,Silvanus.Swai@m-pesa.co.tz";
        //$emails = "evance.nganyaga@bcx.co.tz";
		//Log::info("Sendig Report to: $emails");
        $subject = "B2C & B2B Report - ( $start_date to $end_date )";

        $tos = explode(",", $emails);
        for ($i = 0; $i < count($tos); $i++) {
            $to = $tos[$i];
            //send email to support/ team
            $data = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'transaction_volume' => $transaction_volume,
                'transaction_value' => $transaction_value,
                'charges_value' => $charges_value,
                'transaction_volume_b2b' => $transaction_volume_b2b,
                'transaction_value_b2b' => $transaction_value_b2b,
                'charges_value_b2b' => $charges_value_b2b,
            );

            Mail::send(
                'mail.bcx_daily_summary',
                $data,
                function ($message) use ($to, $subject, $attachment, $attachment_b2b, $file_name, $file_name_b2b) {
                    $message->to($to)
                        ->from("mpesabusiness@vodacom.co.tz", "MPesa Business B2C & B2B")
                        ->subject($subject)
                        ->attachData($attachment, $file_name)
                        ->attachData($attachment_b2b, $file_name_b2b);
                }
            );

        }
		
		//Log::info("Daily Report Sent Successfully!");
		return "End of execution.";
		//usage   App\Http\Controllers\Report\InternalReportController::generateReport();

    }
	
	
	
	public static function generateMonthlyReport(){
		$start_date = date("Y-m-1", strtotime("last month"));
		$end_date = date("Y-m-t", strtotime("last month"));
        //get thedata from db
        $transactions = DB::select("
            SELECT 
                tx.id as tx_id,
                tx.created_at,
                first_name,
                last_name,
                dp.amount,
                CONCAT(dp.phone_number, ' (', dp.network_name, ')') as account_name,
                tx_charge,
                tx.mpesa_receipt,
                tx.short_code,
                o.name as organization_name
            FROM 
                disbursement_payments as dp
                INNER JOIN 
                    batch_payments as bp on bp.batch_no = dp.batch_no
                INNER JOIN 
                    organizations as o on o.id = bp.organization_id
                INNER JOIN 
                    batch_processing as bpp on bpp.batch_id = bp.id
                INNER JOIN 
                tx_disbursement as tx on tx.batch_processing_id = bpp.id
            WHERE 
                dp.payment_status = 1 AND
                tx.entry_id = dp.id AND
                bpp.operation='DISBURSE' AND	
                date(tx.created_at)<='$end_date' AND
                date(tx.created_at)>='$start_date'
            ORDER BY 
                dp.id DESC;
        ");
		 
		
		
        $transaction_volume = count($transactions);
        $transaction_value = 0;
        $charges_value = 0;
        foreach($transactions as $transaction){
            $transaction_value = ($transaction_value + $transaction->amount);
            $charges_value = ($charges_value + $transaction->tx_charge);
        }

        //create the attachments
        $file_name = "B2C Monthly Transaction Report $start_date - $end_date";
        //create an xls report
        $excel = new InternalReportTransactionsExport();
        $excel->transactions = $transactions;
        $attachment =  Excel::raw($excel, BaseExcel::XLSX);
        $file_name .=".xlsx";
		
		/**
         * B2B Transactions
         */
        $transactions_b2b = DB::select("
                SELECT 
                    tx.id as tx_id,
                    tx.created_at,
                    first_name,
                    last_name,
                    dp.amount,
                    CONCAT(dp.account_number, ' (', dp.bank, ')') as account_name,
                    tx.phone_number,
                    tx_charge,
                    tx.mpesa_receipt,
                    tx.short_code,
                    o.name as organization_name
                FROM 
                    bank_payment_disbursements as dp
                    INNER JOIN 
                        bank_batch_payments as bp on bp.batch_no = dp.batch_no
                    INNER JOIN 
                        organizations as o on o.id = bp.organization_id
                    INNER JOIN 
                        bank_batch_processing as bpp on bpp.batch_id = bp.id
                    INNER JOIN 
                    tx_bank_disbursement as tx on tx.batch_processing_id = bpp.id
                WHERE 
                    dp.payment_status = 1 AND
                    tx.entry_id = dp.id AND
                    bpp.operation='DISBURSE'  AND	
                    date(tx.created_at)<='$end_date' AND
                    date(tx.created_at)>='$start_date'
                ORDER BY 
                    dp.id DESC;
        ");

        $transaction_volume_b2b = count($transactions_b2b);
        $transaction_value_b2b = 0;
        $charges_value_b2b = 0;
		
        foreach($transactions_b2b as $transaction_b2b){
            $transaction_value_b2b = ($transaction_value_b2b + $transaction_b2b->amount);
            $charges_value_b2b = ($charges_value_b2b + $transaction_b2b->tx_charge);
        }
		
		$charge_total = $charges_value + $charges_value_b2b;
		
		
		$bcx_share = round(($charge_total * 0.3),2);
		$vodacom_share = round(($charge_total * 0.7),2);

        //create the attachments
        $file_name_b2b = "B2B Monthly Transaction Report $start_date - $end_date";
        //create an xls report
        $excel_b2b = new InternalReportTransactionsExport();
        $excel_b2b->transactions = $transactions_b2b;
        $attachment_b2b =  Excel::raw($excel_b2b, BaseExcel::XLSX);
        $file_name_b2b .=".xlsx";
		
        //send the email to users
        $emails = "innovation@ubx.co.tz,godson.mandla@ubx.co.tz,m-pesabusiness@vodacom.co.tz,Silvanus.Swai@m-pesa.co.tz";
        //$emails = "evance.nganyaga@bcx.co.tz";
		//Log::info("Sendig Report to: $emails");
        $subject = "B2C & B2B Monthly Transaction Report - ( $start_date to $end_date )";
 
        $tos = explode(",", $emails);
        for ($i = 0; $i < count($tos); $i++) {
            $to = $tos[$i];
            //send email to support/ team
            $data = array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'transaction_volume' => $transaction_volume,
                'transaction_value' => $transaction_value,
                'charges_value' => $charges_value,
				'charges_total' => $charge_total,
				'transaction_volume_b2b' => $transaction_volume_b2b,
                'transaction_value_b2b' => $transaction_value_b2b,
                'charges_value_b2b' => $charges_value_b2b,
                'bcx_share' => $bcx_share,
                'vodacom_share' => $vodacom_share,
            );

            Mail::send(
                'mail.bcx_monthly_summary',
                $data,
                function ($message) use ($to, $subject, $attachment, $attachment_b2b, $file_name, $file_name_b2b) {
                    $message->to($to)
                        ->from("mpesabusiness@vodacom.co.tz", "MPesa Business B2C & B2B")
                        ->subject($subject)
                        ->attachData($attachment, $file_name)
                        ->attachData($attachment_b2b, $file_name_b2b);
                }
            );
			
        }
		
		//Log::info("Daily Report Sent Successfully!");
		return "End of monthly summary execution.";
		//usage   App\Http\Controllers\Report\InternalReportController::generateReport();

    }
	
	public function intervalReportTransactions(Request $r){
        header("Access-Control-Allow-Origin: *");

        if(!empty($r->fromDate) && !empty($r->toDate)){
            $startDate = $r->fromDate;
            $endDate = $r->toDate;
        }else{
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        }
        
        $start_date = $startDate;
        $end_date = $endDate;
        /**
         * B2C Transactions
         */
        $transactions = DB::select("
            SELECT 
                tx.id as tx_id,
                tx.created_at,
                first_name,
                last_name,
                tx.phone_number,
                dp.amount,
                CONCAT(dp.phone_number, ' (', dp.network_name, ')') as account_name,
                dp.batch_no,
				bpp.id as batch_processing_id,
				bpp.batch_id as batch_id,
                tx_charge,
                withdrawal_fee,
                dp.status_description,
                tx.mpesa_receipt,
				tx.network_name,
                tx.short_code,
                o.name as organization_name
            FROM 
                disbursement_payments as dp
                INNER JOIN 
                    batch_payments as bp on bp.batch_no = dp.batch_no
                INNER JOIN 
                    organizations as o on o.id = bp.organization_id
                INNER JOIN 
                    batch_processing as bpp on bpp.batch_id = bp.id
                INNER JOIN 
                tx_disbursement as tx on tx.batch_processing_id = bpp.id
            WHERE 
                dp.payment_status = 1 AND
                tx.entry_id = dp.id AND
                bpp.operation='DISBURSE' AND	
                date(tx.created_at)<='$end_date' AND
                date(tx.created_at)>='$start_date'
            ORDER BY 
                dp.id DESC;
        ");

        $transaction_volume = count($transactions);
        $transaction_value = 0;
        $charges_value = 0;
        foreach ($transactions as $transaction) {
            $transaction_value = ($transaction_value + $transaction->amount);
            $charges_value = ($charges_value + $transaction->tx_charge);
        }

        /**
         * B2B Transactions
         */
        $transactions_b2b = DB::select("
                SELECT 
                    tx.id as tx_id,
                    tx.created_at,
                    first_name,
                    last_name,
                    dp.amount,
                    CONCAT(dp.account_number, ' (', dp.bank, ')') as account_name,
			        dp.batch_no,
					bpp.id as batch_processing_id,
					bpp.batch_id as batch_id,
                    tx.phone_number,
                    tx_charge,
                    tx.short_code,
                    dp.status_description,
                    tx.mpesa_receipt,
					tx.phone_number,
					tx.account_number,
					tx.bank_name,
                    o.name as organization_name
                FROM 
                    bank_payment_disbursements as dp
                    INNER JOIN 
                        bank_batch_payments as bp on bp.batch_no = dp.batch_no
                    INNER JOIN 
                        organizations as o on o.id = bp.organization_id
                    INNER JOIN 
                        bank_batch_processing as bpp on bpp.batch_id = bp.id
                    INNER JOIN 
                    tx_bank_disbursement as tx on tx.batch_processing_id = bpp.id
                WHERE 
                    dp.payment_status = 1 AND
                    tx.entry_id = dp.id AND
                    bpp.operation='DISBURSE'  AND	
                    date(tx.created_at)<='$end_date' AND
                    date(tx.created_at)>='$start_date'
                ORDER BY 
                    dp.id DESC;
        ");

        $transaction_volume_b2b = count($transactions_b2b);
        $transaction_value_b2b = 0;
        $charges_value_b2b = 0;
        foreach ($transactions_b2b as $transaction_b2b) {
            $transaction_value_b2b = ($transaction_value_b2b + $transaction_b2b->amount);
            $charges_value_b2b = ($charges_value_b2b + $transaction_b2b->tx_charge);
        }
        $data = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'transactions' => $transactions,
            'transactions_b2b' => $transactions_b2b,
            'transaction_volume' => $transaction_volume,
            'transaction_value' => $transaction_value,
            'charges_value' => $charges_value,
            'transaction_volume_b2b' => $transaction_volume_b2b,
            'transaction_value_b2b' => $transaction_value_b2b,
            'charges_value_b2b' => $charges_value_b2b,
        );

        return $data;
    }
	
	public static function dailyTransactions(Request $r)
    {
		header("Access-Control-Allow-Origin: *");
		
		if(!empty($r->date)){
			$date = $r->date;
		}else{
			$date = date('Y-m-d');
		}

		
        $start_date = $date;
        $end_date = $start_date;
        /**
         * B2C Transactions
         */
        $transactions = DB::select("
            SELECT 
                tx.id as tx_id,
                tx.created_at,
                first_name,
                last_name,
                tx.phone_number,
                dp.amount,
                CONCAT(dp.phone_number, ' (', dp.network_name, ')') as account_name,
                dp.batch_no,
				bpp.id as batch_processing_id,
				bpp.batch_id as batch_id,
                dp.tx_charge,
				dp.withdrawal_fee,
				dp.status_description,
                tx.mpesa_receipt,
				tx.network_name,
                tx.short_code,
                o.name as organization_name
            FROM 
                disbursement_payments as dp
                INNER JOIN 
                    batch_payments as bp on bp.batch_no = dp.batch_no
                INNER JOIN 
                    organizations as o on o.id = bp.organization_id
                INNER JOIN 
                    batch_processing as bpp on bpp.batch_id = bp.id
                INNER JOIN 
                tx_disbursement as tx on tx.batch_processing_id = bpp.id
            WHERE 
                dp.payment_status = 1 AND
                tx.entry_id = dp.id AND
                bpp.operation='DISBURSE' AND	
                date(tx.created_at)<='$end_date' AND
                date(tx.created_at)>='$start_date'
            ORDER BY 
                dp.id DESC;
        ");

        $transaction_volume = count($transactions);
        $transaction_value = 0;
        $charges_value = 0;
        foreach ($transactions as $transaction) {
            $transaction_value = ($transaction_value + $transaction->amount);
            $charges_value = ($charges_value + $transaction->tx_charge);
        }



        /**
         * B2B Transactions
         */
        $transactions_b2b = DB::select("
                SELECT 
                    tx.id as tx_id,
                    tx.created_at,
                    first_name,
                    last_name,
                    dp.amount,
                    CONCAT(dp.account_number, ' (', dp.bank, ')') as account_name,
			dp.batch_no,
					bpp.id as batch_processing_id,
					bpp.batch_id as batch_id,
                    tx.phone_number,
                    tx_charge,
                    tx.short_code,
					dp.status_description,
                    tx.mpesa_receipt,
					tx.phone_number,
					tx.account_number,
					tx.bank_name,
                    o.name as organization_name
                FROM 
                    bank_payment_disbursements as dp
                    INNER JOIN 
                        bank_batch_payments as bp on bp.batch_no = dp.batch_no
                    INNER JOIN 
                        organizations as o on o.id = bp.organization_id
                    INNER JOIN 
                        bank_batch_processing as bpp on bpp.batch_id = bp.id
                    INNER JOIN 
                    tx_bank_disbursement as tx on tx.batch_processing_id = bpp.id
                WHERE 
                    dp.payment_status = 1 AND
                    tx.entry_id = dp.id AND
                    bpp.operation='DISBURSE'  AND	
                    date(tx.created_at)<='$end_date' AND
                    date(tx.created_at)>='$start_date'
                ORDER BY 
                    dp.id DESC;
        ");

        $transaction_volume_b2b = count($transactions_b2b);
        $transaction_value_b2b = 0;
        $charges_value_b2b = 0;
        foreach ($transactions_b2b as $transaction_b2b) {
            $transaction_value_b2b = ($transaction_value_b2b + $transaction_b2b->amount);
            $charges_value_b2b = ($charges_value_b2b + $transaction_b2b->tx_charge);
        }
        $data = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'transactions' => $transactions,
            'transactions_b2b' => $transactions_b2b,
            'transaction_volume' => $transaction_volume,
            'transaction_value' => $transaction_value,
            'charges_value' => $charges_value,
            'transaction_volume_b2b' => $transaction_volume_b2b,
            'transaction_value_b2b' => $transaction_value_b2b,
            'charges_value_b2b' => $charges_value_b2b,
        );

        return $data;
    }
}
