<?php

namespace App\Http\Controllers\Api;

use App\Helper\HttpHelper;
use App\Helper\Util;
use App\Http\Controllers\Controller;
use App\Models\Initiator;
use App\Models\Organization;
use App\Models\TxCheckBalance;
use Illuminate\Support\Facades\Auth;

class BalanceInquiryController extends Controller
{
    public function checkBalance(): \Illuminate\Http\JsonResponse
    {
        $url = env('BALANCE_INQUIRY_API');
        $organisationDetails = $this->getAccountNumber();
        $credentials = $this->getCredentials();

        if (empty($organisationDetails) && empty($credentials)) {
            return response()->json(['error' => 'Unable to fetch balance', 'code' => 500], 500);
        }

        $accountNo = $organisationDetails->short_code;
        $accountName = $organisationDetails->name;
        $data = [
            'accountNo' => $accountNo,
            'accountName' => $accountName
        ];

        // Prepare the Basic Auth credentials
        $username = $credentials->username;
        $password = decrypt($credentials->password);

        $conversation_id = Util::generateRandom(20);

        $requestDump = $url . '?' . http_build_query($data);

        TxCheckBalance::create([
            'organization_id' => $organisationDetails->id,
            'account_number' => $accountNo,
            'conversation_id' => $conversation_id,
            'request_dump' => $requestDump,
            'status' => 'PENDING',
            'initiator_id' => $credentials->id,
        ]);

        $response = HttpHelper::sendCurlRequest($url, 'GET', $data, $username, $password);

        if (isset($response->status) && $response->status === 500) {
            TxCheckBalance::query()->where('conversation_id', $conversation_id)->update(['status' => 'FAILED', 'response_dump' => json_encode($response),]);
            return response()->json(['error' => 'Unable to fetch balance', 'code' => 500], 500);
        }

        if ($response->available_bal) {
            TxCheckBalance::query()->where('conversation_id', $conversation_id)->update(['status' => 'SUCCESS', 'response_dump' => json_encode($response), 'current_balance' => $response->available_bal]);
            return response()->json(['code' => 200, 'balance' => $response->available_bal]);
        }

        TxCheckBalance::query()->where('conversation_id', $conversation_id)->update(['status' => 'FAILED', 'response_dump' => json_encode($response),]);
        return response()->json(['error' => 'Unable to fetch balance', 'code' => 500], 500);
    }

    private function getAccountNumber()
    {
        return Organization::query()->select('id', 'short_code', 'name')
            ->where('id', Auth::user()->organization_id)
            ->first();
    }

    private function getCredentials()
    {
        return Initiator::query()->select('id', 'username', 'password')
            ->where('organization_id', Auth::user()->organization_id)
            ->first();
    }

}
