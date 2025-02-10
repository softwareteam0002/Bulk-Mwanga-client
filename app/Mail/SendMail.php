<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public  $type;

    public  $token ;

    public function __construct($type,$token)
    {
        //
        $this->type=  $type;

        $this->token = $token;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $type  = $this->type;

        $token  =  $this->token;

        return $this->subject("Notification")->view('mail.send_mail',compact('type','token'));

    }

}
