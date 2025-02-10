<?php

namespace App\Http\Controllers;

use App\Helper\DisbursementApiHelper;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\DisbursementPayment;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'ctoken', 'orgapproved']);
    }

    public function dashboard()
    {
        $stime = date("H:i:s");

        $initiatorAlert = null;
        $sumBatchTotalAmount = function ($batch) {
            return $batch->total_amount;
        };

        $sumEntriesTotalAmount = function ($entry) {
            return $entry->amount + $entry->withdrawal_fee;
        };

        $organization_id = Auth::user()->organization_id;

        $checkInitiator = DB::table('initiators')->where(['organization_id' => $organization_id])->latest()->first();

        if (!$checkInitiator) {

            $initiatorAlert = 'Your Organization Does Not Have Any Initiator For Payment';
        } else if ($checkInitiator->status == 'INVALID') {

            $initiatorAlert = 'Initiator credentials are invalid, please add correct credentials to be able to perform transactions';
        }

        $organizationName = Organization::query()->where(['id' => $organization_id])->first();

        //$batches = BatchPayment::query()->where(['organization_id' => $organization_id])->with('disbursements')->get();


        $batches = BatchPayment::select('id', 'batch_no', 'total_amount', 'batch_status_id')
            ->where(['organization_id' => $organization_id])
            ->with('disbursements:id,batch_no,amount,withdrawal_fee,payment_status')->limit(50)->get();

        //return $batches;




        //overall variables
        $overall_transactions = 0;
        $overall_amount = 0;

        //processed variables
        $processed_batches = 0;
        $processed_transactions = 0;
        $processed_amount = 0;


        //failed variables
        $failed_batches = 0;
        $failed_transactions = 0;
        $failed_amount = 0;

        //successful variables
        $successful_batches = 0;
        $successful_transactions = 0;
        $successful_amount = 0;


        foreach ($batches as $b) {

            //process overall batches
            $overall_transactions += count($b->disbursements);
            $overall_amount += $b->total_amount;

            //process failed batches
            if ($b->batch_status_id == 4) {
                $failed_batches++;
            }

            //process successful batches
            if ($b->batch_status_id == 3) {
                $successful_batches++;
            }

            //process amounts
            foreach ($b->disbursements as $d) {
                //successfull
                if ($d->payment_status == 1) {
                    $successful_amount += $d->amount + $d->withdrawal_fee;
                    $successful_transactions++;
                }

                //failed
                if ($d->payment_status == 2) {
                    $failed_amount += $d->amount;
                    $failed_transactions++;
                }

                //processed
                if ($d->payment_status == 10 || $d->payment_status == 1 || $d->payment_status == 2) {
                    $processed_amount += $d->amount + $d->withdrawal_fee;
                    $processed_transactions++;
                }
            }
        }

        //add failed and successfull to get processed batches and transactions
        $processed_batches = ($successful_batches + $failed_batches);
        // /$processed_transactions = ($successful_transactions + $failed_transactions);

        //pack response data arrays
        $overall = [
            'batches' => count($batches),
            'transactions' => $overall_transactions,
            'amount' => $overall_amount,
        ];

        $processed = [
            'batches' => $processed_batches,
            'transactions' => $processed_transactions,
            'amount' => $processed_amount,
        ];

        $failed = [
            'batches' => $failed_batches,
            'transactions' => $failed_transactions,
            'amount' => $failed_amount,
        ];

        $successful = [
            'batches' => $successful_batches,
            'transactions' => $successful_transactions,
            'amount' => $successful_amount,
        ];

        //get chart data
        $chart['successful'] = DisbursementPayment::query()
            ->selectRaw('DATE_FORMAT(created_at,"%Y%m") AS year_month1,DATE_FORMAT(created_at,"%m") AS month,SUM(amount+IF(withdrawal_fee IS NULL,0,withdrawal_fee)) as amount')
            ->groupBy(['year_month1', 'month'])
            ->orderBy('year_month1', 'desc')
            ->where(['payment_status' => DisbursementPayment::STATUS_PAID])
            ->whereRaw("batch_no  IN (SELECT batch_no FROM batch_payments WHERE organization_id={$organization_id})")
            ->limit(40)
            ->get()
            ->toArray();

        $chart['failed'] = DisbursementPayment::query()
            ->selectRaw('DATE_FORMAT(created_at,"%Y%m") AS year_month1,DATE_FORMAT(created_at,"%m") AS month,SUM(amount+IF(withdrawal_fee IS NULL,0,withdrawal_fee)) as amount')
            ->groupBy(['year_month1', 'month'])
            ->orderBy('year_month1', 'desc')
            ->where(['payment_status' => DisbursementPayment::STATUS_ERROR])
            ->whereRaw("batch_no  IN (SELECT batch_no FROM batch_payments WHERE organization_id={$organization_id})")
            ->limit(12)
            ->get()->toArray();

        $chart_months = [];

        for ($i = 0; $i < 12; ++$i) {
            $chart_months[] = date('Ym', $i > 0 ? strtotime("-{$i} months") : time());
        }

        $rearrange = function ($items) use ($chart_months) {
            $rearranged = [];
            foreach ($chart_months as $month) {
                $found = array_search($month, array_column($items, 'year_month1'));
                if ($found !== false) {
                    $rearranged[] = $items[$found];
                } else {
                    $rearranged[] = [
                        'year_month1' => $month,
                        'month' => substr($month, 4),
                        'amount' => 0,
                    ];
                }
            }
            return array_reverse($rearranged);
        };

        $chart['successful'] = $rearrange($chart['successful']);
        $chart['failed'] = $rearrange($chart['failed']);

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $months_walker = function (&$array, $_, $months) {
            $array['month'] = $months[$array['month'] - 1];
            return $array;
        };
        array_walk($chart['successful'], $months_walker, $months);
        array_walk($chart['failed'], $months_walker, $months);

        $find_max = function ($items) {
            usort($items, function ($a, $b) {
                return $b['amount'] - $a['amount'];
            });
            return count($items) <= 0 ? 0 : $items[0]['amount'];
        };

        $chart['max_amount'] = max($find_max($chart['successful']), $find_max($chart['failed']));
        $recent_batch = $this->lastestBatchSummary();
        $org_dashboard = compact('overall', 'processed', 'successful', 'failed', 'chart', 'recent_batch');

        $etime = date("H:i:s");
        //return compact('initiatorAlert','organizationName','org_dashboard','stime','etime');
        return view('dashboard', compact('initiatorAlert', 'organizationName', 'org_dashboard', 'stime', 'etime'));
    }

    private function lastestBatchSummary()
    {
        $batches = DB::table('batch_payments')->selectRaw('user_batch_no,batch_no,"payment" as type,created_at')->union(
            DB::table('batches')->selectRaw('user_batch_no,batch_no,"verification" as type,created_at')
                ->where(['organization_id' => Auth::user()->organization_id])
                ->whereNotIn('batch_status_id', [Batch::STATUS_PENDING, Batch::STATUS_QUEUED])
        )->where(['organization_id' => Auth::user()->organization_id])
            ->whereNotIn('batch_status_id', [Batch::STATUS_PENDING, Batch::STATUS_QUEUED])
            ->orderByDesc('created_at')
            ->get();

        $summary = [];
        if ($batches->isNotEmpty()) {
            $batch = $batches[0];
            if ($batch->type == 'verification') {
                $summary = DisbursementApiHelper::getVerificationStatus($batch->batch_no, false, true);
            } else {

                $summary = DisbursementApiHelper::getDisbursementStatus($batch->batch_no, false, true);
            }

            $summary['batch_no'] = $batch->user_batch_no;
        }
        return $summary;
    }
    public static function dashboardData(): \Illuminate\Http\JsonResponse
    {
        try {
            $stime = date("H:i:s");

            $initiatorAlert = null;
            $sumBatchTotalAmount = function ($batch) {
                return $batch->total_amount;
            };

            $sumEntriesTotalAmount = function ($entry) {
                return $entry->amount + $entry->withdrawal_fee;
            };

            $organization_id = Auth::user()->organization_id;

            $batches = BatchPayment::select('id', 'batch_no', 'total_amount', 'batch_status_id')
                ->where(['organization_id' => $organization_id])
                ->with('disbursements:id,batch_no,amount,withdrawal_fee,payment_status')->limit(100)->get();



            //overall variables
            $overall_transactions = 0;
            $overall_amount = 0;

            //processed variables
            $processed_batches = 0;
            $processed_transactions = 0;
            $processed_amount = 0;


            //failed variables
            $failed_batches = 0;
            $failed_transactions = 0;
            $failed_amount = 0;

            //successful variables
            $successful_batches = 0;
            $successful_transactions = 0;
            $successful_amount = 0;


            foreach ($batches as $b) {

                //process overall batches
                $overall_transactions += count($b->disbursements);
                $overall_amount += $b->total_amount;

                //process failed batches
                if ($b->batch_status_id == 4) {
                    $failed_batches++;
                }

                //process successful batches
                if ($b->batch_status_id == 3) {
                    $successful_batches++;
                }

                //process amounts
                foreach ($b->disbursements as $d) {
                    //successfull
                    if ($d->payment_status == 1) {
                        $successful_amount += $d->amount + $d->withdrawal_fee;
                        $successful_transactions++;
                    }

                    //failed
                    if ($d->payment_status == 2) {
                        $failed_amount += $d->amount;
                        $failed_transactions++;
                    }

                    //processed
                    if ($d->payment_status == 10 || $d->payment_status == 1 || $d->payment_status == 2) {
                        $processed_amount += $d->amount + $d->withdrawal_fee;
                        $processed_transactions++;
                    }
                }
            }

            //add failed and successfull to get processed batches and transactions
            $processed_batches = ($successful_batches + $failed_batches);
            // /$processed_transactions = ($successful_transactions + $failed_transactions);

            //pack response data arrays
            $overall = [
                'batches' => count($batches),
                'transactions' => $overall_transactions,
                'amount' => $overall_amount,
            ];

            $processed = [
                'batches' => $processed_batches,
                'transactions' => $processed_transactions,
                'amount' => $processed_amount,
            ];

            $failed = [
                'batches' => $failed_batches,
                'transactions' => $failed_transactions,
                'amount' => $failed_amount,
            ];

            $successful = [
                'batches' => $successful_batches,
                'transactions' => $successful_transactions,
                'amount' => $successful_amount,
            ];


            //get chart data
            $chart['successful'] = DisbursementPayment::query()
                ->selectRaw('DATE_FORMAT(created_at,"%Y%m") AS year_month1,DATE_FORMAT(created_at,"%m") AS month,SUM(amount+IF(withdrawal_fee IS NULL,0,withdrawal_fee)) as amount')
                ->groupBy(['year_month1', 'month'])
                ->orderBy('year_month1', 'desc')
                ->where(['payment_status' => DisbursementPayment::STATUS_PAID])
                ->whereRaw("batch_no  IN (SELECT batch_no FROM batch_payments WHERE organization_id={$organization_id})")
                ->limit(40)
                ->get()
                ->toArray();

            $chart['failed'] = DisbursementPayment::query()
                ->selectRaw('DATE_FORMAT(created_at,"%Y%m") AS year_month1,DATE_FORMAT(created_at,"%m") AS month,SUM(amount+IF(withdrawal_fee IS NULL,0,withdrawal_fee)) as amount')
                ->groupBy(['year_month1', 'month'])
                ->orderBy('year_month1', 'desc')
                ->where(['payment_status' => DisbursementPayment::STATUS_ERROR])
                ->whereRaw("batch_no  IN (SELECT batch_no FROM batch_payments WHERE organization_id={$organization_id})")
                ->limit(12)
                ->get()->toArray();

            $chart_months = [];

            for ($i = 0; $i < 12; ++$i) {
                $chart_months[] = date('Ym', $i > 0 ? strtotime("-{$i} months") : time());
            }

            $rearrange = function ($items) use ($chart_months) {
                $rearranged = [];
                foreach ($chart_months as $month) {
                    $found = array_search($month, array_column($items, 'year_month1'));
                    if ($found !== false) {
                        $rearranged[] = $items[$found];
                    } else {
                        $rearranged[] = [
                            'year_month1' => $month,
                            'month' => substr($month, 4),
                            'amount' => 0,
                        ];
                    }
                }
                return array_reverse($rearranged);
            };

            $chart['successful'] = $rearrange($chart['successful']);
            $chart['failed'] = $rearrange($chart['failed']);

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            $months_walker = function (&$array, $_, $months) {
                $array['month'] = $months[$array['month'] - 1];
                return $array;
            };
            array_walk($chart['successful'], $months_walker, $months);
            array_walk($chart['failed'], $months_walker, $months);

            $find_max = function ($items) {
                usort($items, function ($a, $b) {
                    return $b['amount'] - $a['amount'];
                });
                return count($items) <= 0 ? 0 : $items[0]['amount'];
            };

            $chart['max_amount'] = max($find_max($chart['successful']), $find_max($chart['failed']));
            $recent_batch = self::latestBatchSummary();
            $org_dashboard = compact('overall', 'processed', 'successful', 'failed', 'chart', 'recent_batch');

            $etime = date("H:i:s");

            return response()->json(['org_dashboard' => $org_dashboard, 'stime' => $stime, 'etime' => $etime, 'status_code' => 300]);
        } catch (\Throwable $exception) {

            Log::error('DASHBOARD-DATA-ERROR', ['MESSAGE' => $exception]);
            return response()->json(['org_dashboard' => null, 'stime' => null, 'etime' => null, 'message' => 'error', 'status_code' => 500]);

        }
    }
    public static function latestBatchSummary()
    {
        $batches = DB::table('batch_payments')->selectRaw('user_batch_no,batch_no,"payment" as type,created_at')->union(
            DB::table('batches')->selectRaw('user_batch_no,batch_no,"verification" as type,created_at')
                ->where(['organization_id' => Auth::user()->organization_id])
                ->whereNotIn('batch_status_id', [Batch::STATUS_PENDING, Batch::STATUS_QUEUED])
        )->where(['organization_id' => Auth::user()->organization_id])
            ->whereNotIn('batch_status_id', [Batch::STATUS_PENDING, Batch::STATUS_QUEUED])
            ->orderByDesc('created_at')
            ->get();

        $summary = [];
        if ($batches->isNotEmpty()) {
            $batch = $batches[0];
            if ($batch->type == 'verification') {
                $summary = DisbursementApiHelper::getVerificationStatus($batch->batch_no, false, true);
            } else {

                $summary = DisbursementApiHelper::getDisbursementStatus($batch->batch_no, false, true);
            }

            $summary['batch_no'] = $batch->user_batch_no;
        }

        return $summary;
    }
}
