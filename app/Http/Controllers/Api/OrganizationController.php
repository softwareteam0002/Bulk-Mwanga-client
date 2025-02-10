<?php

namespace App\Http\Controllers\Api;

use App\Helper\CredentialsRepo;
use App\Helper\HttpHelper;
use App\Helper\Util;
use App\Helper\XMLHelper;
use App\Http\Controllers\Controller;
use App\Models\TxOrganizationKYCSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class OrganizationController extends Controller
{

    public function __construct()
    {

    }

    public function kycCallback(Request $request){
        Log::info($request->getContent());
    }

    public function requestOrganizationKYC($short_code){

        $reference =  Util::generateRandom(20);

        $req_data =  [
            'username' => CredentialsRepo::getMpesaApiUsername(),
            'password' => CredentialsRepo::getMpesaApiPassword(),
            'conversationID' => Util::generateRandom(20),
            'service' => 'QueryOrgKYC',
            'orgCode' => $short_code
        ];

        $req_raw = XMLHelper::arrayToXML($req_data);

        $tx = TxOrganizationKYCSearch::query()->create(
            [
                'short_code'=>$short_code,
                'conversation_id'=>$reference,
                'status'=>'PENDING',
                'request_dump'=>$req_raw,
            ]
        );

        ['code'=>$httpCode,'data'=>$raw_response,'error'=>$error] = HttpHelper::send($req_raw,true,'raw',HttpHelper::API_ENDPOINT_GET_KYC);

        $data = empty($raw_response)?null:XMLHelper::XMLStringToArray($raw_response);

        if (!empty($error) || $httpCode!=200){
            $tx->update(['status'=>'FAILED','failure_reason'=>HttpHelper::guessFailureReason($httpCode,$error)]);
            return  ['data' => null, 'error' => $error];
        }else if (empty($data)){
            $tx->update(['status'=>'FAILED','failure_reason'=>'INVALID_RESPONSE']);
            return  ['data' => null, 'error' => "Invalid response!!"];
        }
        $tx->update(['status'=>'SUCCESS','response_dump'=>$raw_response]);

        return !empty($error) || $httpCode != 200 ? ['data' => null, 'error' => $error,'http_status'=>$httpCode] : ['data' => ['status'=>'request sent'], 'error' => null];
    }

}
