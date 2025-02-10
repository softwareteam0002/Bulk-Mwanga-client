<?php

namespace App\Jobs;

use App\Helper\BankDisbursementApiHelper;
use App\Http\Controllers\Api\PaymentController;
use App\Models\BankBatchPayment;
use App\Models\BankBatchProcessing;
use App\Models\BankVerificationBatch;
use App\Models\Batch;
use App\Models\BatchProcessing;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankProcessBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 5;
    public  $timeout  =  0;


    private $batch_id;
    private $batch_processing_id;
    private $operation;

    public function __construct($batch_id, $operation,$batch_processing_id){
        $this->batch_id = $batch_id;
        $this->operation = $operation;
        $this->batch_processing_id = $batch_processing_id;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle()
    {

        set_time_limit(0);

        try{

            $batch_processing = BankBatchProcessing::query()->find($this->batch_processing_id);
            $batch = ($this->operation=='disburse'? BankBatchPayment::query():BankVerificationBatch::query())
                ->where(['id'=>$this->batch_id])->first();

            $this->internalHandle($batch,$batch_processing);
        }

        catch (Exception | \Error $e){

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            if (!empty($batch_processing)){
                $batch_processing->update([
                    'status'=>BatchProcessing::STATUS_PROCESSED,
                    'result'=>BatchProcessing::RESULT_FAILED]);
            }

            if (!empty($batch)){

                $batch->update(['batch_status_id'=>Batch::STATUS_FAILED,'status_description'=>'Internal System Error']);

            }
        }
    }


    private function internalHandle($batch, $batch_processing){

        if (empty($batch_processing) || $batch_processing->status != BatchProcessing::STATUS_QUEUED) {

            if ($batch->batch_status_id != Batch::STATUS_COMPLETED && $batch->batch_status_id!=Batch::STATUS_ON_PROGRESS){
                $batch->update(['batch_status_id'=>Batch::STATUS_FAILED]);
                Log::error("Batch id {$batch->id} with status {$batch->batch_status_id} has batch_processing status of ".($batch_processing->status??"null")." - Could not be processed");
            }

            elseif ($batch_processing->status == BatchProcessing::STATUS_CANCELLED){
                $batch->update(['batch_status_id'=>Batch::STATUS_CANCELLED]);
            }
            echo "Batch processing is in invalid status: ".(empty($batch_processing)?'null':$batch_processing->status)."\n";
            return;
        }

        $batch_processing->update(['status'=>BatchProcessing::STATUS_ON_PROGRESS]);
        $status = $this->doProcessing();
		Log::info("BankProcessBatch internalHandle Status: $status");
        //checking if it was updated on other threads,
        //batch status should not be on-hold and batch processing should not be processed
        //Should use trasaction here,
        DB::beginTransaction();
        try{

            $batch_processing = BankBatchProcessing::query()->lockForUpdate()->find($this->batch_processing_id);
            $batch = ($this->operation=='disburse'? BankBatchPayment::query():BankVerificationBatch::query())
                ->where(['id'=>$this->batch_id])->lockForUpdate()->first();

            if ($batch->status == Batch::STATUS_ON_HOLD
                || $batch_processing->result == BatchProcessing::RESULT_INSUFFIENT_BALANCE_FAILURE)
            {
                return;
            }

             if (isset($status) && $status == PaymentController::PROCESSING_STATUS_PARTIAL_FAILURE){

                echo "Batch partial failure";
                $batch->update(['batch_status_id'=>Batch::STATUS_COMPLETED_WITH_FAILED_ITEM,'status_description'=>'Some entries failed']);
                $batch_processing->update([
                    'status'=>BatchProcessing::STATUS_PROCESSED,
                    'result'=>BatchProcessing::RESULT_PARTIAL_FAILURE]);

            }

            else{
                $this->updateBatchProcessingStatus($batch_processing,$status);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * @return int|null processing status, one of success, failed, partial failure or invalid batch
     * @throws Exception
     */
    private function doProcessing(){

        if ($this->operation=='verify'){
            return BankDisbursementApiHelper::doBatchVerification($this->batch_processing_id);
        }elseif ($this->operation=='disburse'){
            return BankDisbursementApiHelper::doBatchPayment($this->batch_processing_id);
        }

        return null;
    }

    /**
     * @param $batch_processing
     * @param $status
     */

    private function updateBatchProcessingStatus($batch_processing, $status){

        if ($status == PaymentController::PROCESSING_STATUS_SUCCESS){
            $batch_processing->update([
                'status'=>BatchProcessing::STATUS_PROCESSED,
                'result'=>BatchProcessing::RESULT_SUCCESS]);
        }else{
            $batch_processing->update([
                'status'=>BatchProcessing::STATUS_PROCESSED,
                'result'=>BatchProcessing::RESULT_FAILED]);
        }
    }

}
