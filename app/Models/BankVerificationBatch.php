<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankVerificationBatch extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_QUEUED = 1;
    const STATUS_ON_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_SCHEDULED = 6;
    const STATUS_ON_HOLD = 7;
    const STATUS_COMPLETED_WITH_FAILED_ITEM = 8;

    protected $table = 'bank_verification_batches';
    protected $guarded = [];

    public  function  organization(){

        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public static function getStatusName($status)
    {
        return [
            Batch::STATUS_PENDING => 'Pending',
            Batch::STATUS_QUEUED => 'Queued',
            Batch::STATUS_ON_PROGRESS => 'On Progress',
            Batch::STATUS_COMPLETED => 'Completed',
            Batch::STATUS_FAILED => 'Failed',
            Batch::STATUS_CANCELLED => 'Cancelled',
            Batch::STATUS_SCHEDULED => 'Scheduled',
            Batch::STATUS_ON_HOLD => 'Insufficient balance',
            Batch::STATUS_COMPLETED_WITH_FAILED_ITEM => 'Completed with failure',
        ][$status];
    }

}
