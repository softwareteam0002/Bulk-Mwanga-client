<?php

namespace App\Jobs;

use App\Models\BatchPayment;
use App\Models\DisbursementPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PaymentDemoSimulator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public  $batch_no;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($batch_no)
    {
        //

        $this->batch_no =  $batch_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

            $batch_items = DisbursementPayment::query()->where(['batch_no'=>$this->batch_no])->get();

            foreach ($batch_items as $item)
            {

                if ($item->network_name!=="UNKNOWN")
                {
                    DisbursementPayment::query()->where(['batch_no'=>$this->batch_no])
                        ->update(['payment_status'=>1,'status_description'=>'Success']);

                }

                else{

                    DisbursementPayment::query()->where(['batch_no'=>$this->batch_no])
                        ->update(['payment_status'=>2,'status_description'=>'Not Registered']);
                }

            }

        $batch = BatchPayment::query()->where(['batch_no'=>$this->batch_no])->update(['batch_status_id'=>3]);

    }
}
