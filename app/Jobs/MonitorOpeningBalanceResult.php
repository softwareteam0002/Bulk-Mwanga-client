<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\DisbursementOpeningBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonitorOpeningBalanceResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $batch_id;

    /**
     * Create a new job instance.
     *
     * @param $batch_id
     */
    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if its still on-queue, then check if we got the balance;
        //if no balance then just mark it as failed as we could not query balance
        $batch = BatchPayment::query()->where(['id'=>$this->batch_id])->first();
        if ($batch->batch_status_id == Batch::STATUS_QUEUED){
            $balance = DisbursementOpeningBalance::query()
                ->where(['batch_id'=>$this->batch_id,'sufficient'=>'YES'])
                ->first();
            if (empty($balance)){
                $batch->update(['batch_status_id'=>Batch::STATUS_FAILED,
                    'status_description'=>'Failed to query balance']);
            }
        }
    }
}
