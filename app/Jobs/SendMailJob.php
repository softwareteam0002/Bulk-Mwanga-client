<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private   $email;
    private    $mailType;
    private $token;
    public function __construct($email,$mailType,$token)
    {
        //

        $this->email =  $email;

        $this->mailType =  $mailType;

        $this->token = $token;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::to([$this->email])->queue(new SendMail($this->mailType,$this->token));

    }
}
