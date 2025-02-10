<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\BatchProcessing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;

class ScheduledDisbursement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $batch_processing_id;
    private $initiatorId;

    /**
     * Create a new job instance.
     *
     * @param $batch_processing_id
     * @param $initiatorId
     */
    public function __construct($batch_processing_id, $initiatorId)
    {
        $this->batch_processing_id = $batch_processing_id;
        $this->initiatorId = $initiatorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batch_processing = BatchProcessing::query()->where(['id'=>$this->batch_processing_id])->first();
        $batch = BatchPayment::query()
            ->where(['id' => $batch_processing->batch_id])
            ->first();

        if ($batch_processing->status == BatchProcessing::STATUS_SCHEDULED
            && $batch->batch_status_id == Batch::STATUS_SCHEDULED){
            $batch->update(['batch_status_id'=>Batch::STATUS_QUEUED]);
            $batch_processing->update(['status'=>BatchProcessing::STATUS_QUEUED]);
            Queue::later(0, new BalanceCheckForDisbursement($batch->id,$this->initiatorId));
        }
    }
}
