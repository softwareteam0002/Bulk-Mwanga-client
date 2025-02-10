<?php

namespace App\Imports;

use App\Helper\Util;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\DisbursementPayment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportDisbursementPayment implements ToModel, WithHeadingRow,WithValidation
{

    const  ERROR_CODE_INVALID_BATCH_NUMBER = 100;
    const ERROR_CODE_MISSMATCHED_BATCH_NUMBER = 200;


    private $user_batch_number = null;
    private $batch_number = null;
    /**
     * @param array $row
     * @return DisbursementPayment
     * @throws \Exception
     */
    public function model(array $row)
    {
        //the batch number should exist in verified batches
        //the batch number should be the same across all excel rows

        if (empty($this->user_batch_number)){
            $batch = Batch::query()
                ->where(['organization_id'=>Auth::user()->organization_id,'user_batch_no'=>$row['batch_no']])
                ->whereNotIn('batch_status_id',[Batch::STATUS_QUEUED,Batch::STATUS_PENDING,Batch::STATUS_ON_PROGRESS])
                ->orderByDesc('created_at')
                ->first();
            if (empty($batch)) {
                throw new \Exception('Batch number provided is not valid',self::ERROR_CODE_INVALID_BATCH_NUMBER);
            }elseif (BatchPayment::query()->where(['user_batch_no'=>$row['batch_no'],'organization_id'=>Auth::user()->organization_id])->exists()) {
                throw new \Exception('Batch number provided has already been uploaded!',self::ERROR_CODE_INVALID_BATCH_NUMBER);
            } else{
                $this->user_batch_number = $row["batch_no"];
                $this->batch_number = $batch->batch_no;
            }
        }else if ($this->user_batch_number!=$row["batch_no"]){
            throw new \Exception('Batch number is not the same accross the rows',self::ERROR_CODE_MISSMATCHED_BATCH_NUMBER);
        }

        return new DisbursementPayment([
            'batch_no'=> $this->batch_number,//we do not need user batch number here
            'first_name' => $row["verified_first_name"]?? $row["first_name"],
            'last_name' => $row["verified_last_name"] ?? $row["last_name"],
            'phone_number' => $row["phone_number"],
            'amount'=>$row["amount"],
            'network_name'=> $row["network_name"],
			'conversation_id' => Util::generateRandom(20),
            'payment_detail'=>$row['payment_detail'],

        ]);

    }


    /**
     * @return array
     */
    public function rules(): array
    {
        return  [
            'batch_no'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_number'=>['required','regex:/^(0|(\+)?255)?[0-9]{9}$/'],
            'amount'=>'required|numeric|between:0.0,10000000.0',
            'network_name'=>'required'
        ];



    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'first' => 'Custom message for :attribute.',
        ];
    }

    /**
     * @return null
     */
    public function getBatchNumbers()
    {
        return ['user_batch_number'=>$this->user_batch_number,'batch_number'=>$this->batch_number];
    }
}
