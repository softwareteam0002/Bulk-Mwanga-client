<?php
/**
 * Author: Memory Mwageni <memorymwageni@gmail.com>
 * Date: 10/07/2019
 * Time: 00:55
 */

namespace App\Helper;
use Illuminate\Support\Facades\Log;


class SMSHelper
{
    const SENDER_CUSTOM_API = 'custom-api';
    const SENDER_KANNEL = 'kannel';
    const SENDER_VODASHULE = 'vodashule';
    const URL_SENDER_KANNEL = 'http://10.10.65.45:14013/cgi-bin/sendsms';

//        "http://localhost:13131/cgi-bin/sendsms?username=vodashule&password=sndsVdb26cX&from=MpesaPortal&to=255719906669&text=test";

        //'http://10.10.65.45:14013/cgi-bin/sendsms';

    /**
     * @param $phone
     * @param $sms
     * @param string $sender
     * @return bool
     */

    public static function sendSingle($phone,$sms,$sender=self::SENDER_KANNEL){

        $phone = self::addPrefix($phone);
        $vodacom = '/^2557[456][2-9].+/';
        if (preg_match($vodacom,$phone))
            $outgoing_name = 'MpesaPortal';
        else
            $outgoing_name = 'M-PESA';

        if ($sender==self::SENDER_VODASHULE){
            if (empty($phone)) return false;
            $data = [
                'service'=>'sendSMS',
                'token'=>"6833be7bdb42b6227c507557a899fdd2a5cf2114147bedfc0666ee6493f4076",
                'phoneNumber'=>$phone,
                'message'=>$sms,
            ];
            return !!HttpHelper::send($data,false,'object',HttpHelper::API_ENDPOINT_SMS);
        }else if ($sender == self::SENDER_CUSTOM_API){
            $xml = XMLHelper::arrayToXML([
                'service'=>'sms',
                'partnerID'=>CredentialsRepo::MPESA_PARTNER_ID,
                'username'=>CredentialsRepo::getMpesaApiUsername(),
                'password'=>CredentialsRepo::getMpesaApiPassword(),
                'timestamp'=>date('YmdHis'),
                'conversationID'=>self::generateConversationId(),
                'phoneNumber'=>$phone,
                'messageEn'=>$sms,
                'messageSw'=>$sms,
            ],'request');
            $response =  HttpHelper::send($xml,true);
       Log::info('SMS-RESPO-custom',['MESSAGE'=>$response]);
            return !empty($response->data) && $response->data->resultCode != 0;

        }
		else if($sender == "B2C"){
            $data['submit']= [
                'da'=>['number'=>$phone],
                'oa'=>['number'=>'M-PESA'], //MPESABUSINESS
                'ud'=>$sms,
                'from'=>[
                    'username'=>'vodashule',
                    'password'=>'sndsVdb26cX',
                ],
            ];

            $response = HttpHelper::send(XMLHelper::arrayToXML($data,'message'),true,'raw',self::URL_SENDER_KANNEL);
  Log::info('SMS-RESPO b2c',['MESSAGE'=>$response]);
            return preg_match('/^0/',$response['data']);
        }
        
		
		
		else{
            $data['submit']= [
                'da'=>['number'=>$phone],
                'oa'=>['number'=>$outgoing_name], //MPESABUSINESS
                'ud'=>$sms,
                'from'=>[
                    'username'=>'vodashule',
                    'password'=>'sndsVdb26cX',
                ],
            ];

            $response = HttpHelper::send(XMLHelper::arrayToXML($data,'message'),true,'raw',self::URL_SENDER_KANNEL);
  Log::info('SMS-RESPO',['MESSAGE'=>$response]);
            return preg_match('/^0/',$response['data']);
        }
    }


    public static function sendSingleMultiLanguage($phone,$sms){

    }

    public static function sendMultiple($phone_numbers,$message){

    }

    public static function addPrefix($phone)
    {
        return strlen($phone)<=9?'255'.$phone:preg_replace('/^0/','255',$phone);
    }

    private static function generateConversationId()
    {
        return str_pad(random_int(0, 1000000), 7, "0", STR_PAD_LEFT)
            . time()
            . str_pad(random_int(0, 10000), 7, "0", STR_PAD_LEFT);
    }

}
