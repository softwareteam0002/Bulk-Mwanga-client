<?php

namespace App\Http\Controllers\Api;

use App\Helper\CredentialsRepo;
use App\Helper\HttpHelper;
use App\Helper\Util;
use App\Helper\XMLHelper;
use App\Http\Controllers\Controller;
use Faker\Factory;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimulatorController extends Controller
{

    const KYC_CALLBACK_URL = 'http://b2co.com/disbursement-voda/kyc-callback';
    const DISBURSEMENT_CALLBACK_URL = 'http://b2co.com/bank-disbursement-callback';
    const DISBURSEMENT_CALLBACK_BALANCE_URL = 'http://b2co.com/balance-check-callback';


    /**
     * SimulatorController constructor.
     */
    public function __construct()
    {

    }

    public  function  balanceCheck(Request $request)
    {

        Log::info(json_encode($request->all()));
        $content = $request->getContent();
        $data = XMLHelper::XMLStringToArray($content);

        HttpHelper::closeConnection(XMLHelper::arrayToXML([
            'responseCode'=>0,
            'responseDesc'=>'Request received successfully'
        ],'response'),200);

        $response = XMLHelper::arrayToXML([
            'conversationID'=>$data['conversationID'],
            'service'=>'checkOrgBalanceResult',
            'resultCode'=>0,
            'shortCode'=>0,
            'resultDesc'=>'Success',
            'Currency'=>'TZS',
            'accountType'=>'Utility Collection Account',
            'CurrentBalance'=>640000,
            'AvailableBalance'=>630000,
            'ReservedBalance'=>45000,
            'UnclearedBalance'=>10000,
        ],'result');
        $raw = HttpHelper::send($response,true,'raw',self::DISBURSEMENT_CALLBACK_BALANCE_URL);
        return "";
    }

    public  function  customerNameSearch(Request $request)
    {
        if (random_int(0,1000)<=10){
            return response()->setContent("Timeout error")->setStatusCode(408);
        }

        $content = $request->getContent();
        $data = XMLHelper::XMLStringToArray($content);

        if(empty($data)){
            return XMLHelper::arrayToXML([
                'TYPE'=>'QuerySubscriberResp',
                'MESSAGE'=>'Failed',
                'TXNSTATUS'=>999,
                'TXNID'=>$data['REFERENCEID'],
                'REFERENCEID'=>'',
                'MSISDN'=>$data['MSISDN'],
                'FIRSTNAME'=>'',
                'LASTNAME'=>'',
                'FULLNAME'=>'',
            ],'COMMAND');
        }

        $faker = Factory::create();
        $fname = $faker->firstName();
        $lname = $faker->lastName;

        return XMLHelper::arrayToXML([
            'TYPE'=>'QuerySubscriberResp',
            'MESSAGE'=>'Success',
            'TXNSTATUS'=>0,
            'TXNID'=>$data['REFERENCEID'],
            'REFERENCEID'=>$this->guessNetwork($data['MSISDN']),
            'MSISDN'=>$data['MSISDN'],
            'FIRSTNAME'=>$fname,
            'LASTNAME'=>$lname,
            'FULLNAME'=>sprintf('%s %s',$fname,$lname),
        ],'COMMAND');
    }

    public function kyc(Request $request){
        $content = $request->getContent();
        $data = XMLHelper::XMLStringToArray($content);

        if(empty($data)){
            return XMLHelper::arrayToXML([
                'TYPE'=>'QueryNetworkResp',
                'MESSAGE'=>'Failed',
                'TXNSTATUS'=>999,
                'TXNID'=>$data['REFERENCEID'],
                'REFERENCEID'=>$data['REFERENCEID'],
                'MSISDN'=>$data['MSISDN'],
                'NETWORK'=>'',
            ],'response');
        }

        HttpHelper::closeConnection(XMLHelper::arrayToXML([
            'responseCode'=>01,
            'responseDesc'=>'Request received successfully'
        ],'response'),200);

        $faker = Factory::create();
        $response = XMLHelper::arrayToXML([
            'conversationID'=>$data['conversationID'],
            'SHORTCODE'=>$data['orgCode'],
            'ORGANIZATION-NAME'=>$faker->company,
            'REGION'=>$faker->state,
            'DISTRICT'=>$faker->state,
            'EMAIL'=>$faker->companyEmail,
            'PHONENUMBER'=>$faker->phoneNumber,
            'FULLNAME'=>$faker->name,
            'POSITION'=>$faker->jobTitle,
            'EMAIL2'=>$faker->email,
            'PHONENUMBER2'=>$faker->phoneNumber,
        ],'request');

        $raw = HttpHelper::send($response,true,'raw',self::KYC_CALLBACK_URL);

        //then we should send a callback
        return "";
    }

    public function disburse(Request $request){
        $content = $request->getContent();
        $data = XMLHelper::XMLStringToArray($content);

        HttpHelper::closeConnection(XMLHelper::arrayToXML([
            'responseCode'=>0,
            'responseDesc'=>'Request received',
            'msg'=>'Request received'
        ],'response'),200);

        $response = XMLHelper::arrayToXML([
            'ID'=>$data['conversationID'],
            'COMMAND'=>'disbursement',
            'RESULT_CODE'=>0,
            'RESULT_DESC'=>'Success',
            'MPESA_RECEIPT'=>'7B1'.random_int(1000000,9999999),
            'PARTNERSHORTCODE'=>$data['orgAccount'],
            'amount'=>$data['amount'],
            'recipientMSISDN'=>$data['recipient'],
            'network'=>$data['network'],
        ],'result');
        //simulaate no balance
        if ($data['recipient']=='255719906669x'){
            $response = XMLHelper::arrayToXML([
                'ID'=>$data['conversationID'],
                'COMMAND'=>'disbursement',
                'RESULT_CODE'=>99,
                'RESULT_DESC'=>'Insuffiecient balance',
                'MPESA_RECEIPT'=>'',
                'PARTNERSHORTCODE'=>$data['orgAccount'],
                'amount'=>$data['amount'],
                'recipientMSISDN'=>$data['recipient'],
                'network'=>$data['network'],
            ],'result');
        }

        HttpHelper::send($response,true,'raw',self::DISBURSEMENT_CALLBACK_URL);
        return "";
    }

    private function guessNetwork($MSISDN)
    {
        if (preg_match('/(^(255|0)?(74|75|76))/',$MSISDN)){
            return 'vodacom';
        }
        $networks = ['airtel','tigo','halotel'];
        return $networks[random_int(1,count($networks))-1];
    }
}
