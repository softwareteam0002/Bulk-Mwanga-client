<?php

namespace App\Jobs;

use App\Helper\DisbursementApiHelper;
use App\Models\Batch;
use App\Models\BatchPayment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;

class BalanceCheckForDisbursement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $batch_id;
    private $initiatorId;

    /**
     * Create a new job instance.
     *
     * @param $batch_id
     * @param $initiatorId
     */
    public function __construct($batch_id, $initiatorId)
    {
        $this->batch_id = $batch_id;
        $this->initiatorId = $initiatorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batch = BatchPayment::query()
            ->where(['id' => $this->batch_id])
            ->first();
        $organization_id = $batch->organization_id;
        ['status'=>$status,'message'=>$message]=DisbursementApiHelper::doBalanceCheck($organization_id,$this->initiatorId);
        if ($status=='failed'){
            $batch->update(['batch_status_id' => Batch::STATUS_FAILED,'status_description'=>"Failed to query balance: {$message}"]);
        } else{
            //setup monitor to check after 15 minutes if the balance callback is not received
            Queue::later(Carbon::now()->addMinutes(15), new MonitorOpeningBalanceResult($this->batch_id));
            //Status should be queued
            $batch->update(['batch_status_id' => Batch::STATUS_QUEUED,'status_description'=>null]);
        }
    }
}
