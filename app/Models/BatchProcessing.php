<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BatchProcessing extends Model
{
    const STATUS_QUEUED = 'QUEUED';
    const STATUS_ON_PROGRESS = 'ON-PROGRESS';
    const STATUS_PROCESSED = 'PROCESSED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_SCHEDULED = 'SCHEDULED';
    const RESULT_FAILED = 'FAILED';
    const RESULT_SUCCESS = 'SUCCESS';
    const RESULT_PARTIAL_FAILURE ='PARTIAL_FAILURE';
    const RESULT_INSUFFIENT_BALANCE_FAILURE ='INSUFFIENT_BALANCE_FAILURE';

    protected $table = 'batch_processing';
    protected $guarded = [];


    /**
     * @param $operation
     * @param $batch
     * @param $initiator_id
     * @param string $status
     * @return Builder|BatchProcessing
     */
    public static function addQueuedBatch($operation,$batch,$initiator_id,$status = self::STATUS_QUEUED){
        $organization = Organization::query()->where(['id' => $batch->organization_id])->first();
        return self::query()->create([
           'batch_id'=>$batch->id,
           'organization_id'=>$organization->id,
           'operation'=>strtoupper($operation),
           'initiator_id'=>$initiator_id,
           'status'=>$status,
        ]);
    }

}
