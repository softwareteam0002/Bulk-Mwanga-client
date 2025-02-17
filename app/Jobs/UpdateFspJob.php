<?php

namespace App\Jobs;

use App\Helper\ConstantList;
use App\Helper\HttpHelper;
use App\Helper\Util;
use App\Http\Controllers\Services\TokenServiceController;
use App\Models\FspCode;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateFspJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle()
    {
        $this->getActiveFsp();
    }

    private function getActiveFsp(): void
    {
        $url = env("FSP_CODES");
        $payload = [
            "fspinfo" => [
                "paytype" => ConstantList::PAYMENT_TYPE,
                "date" => now()->format('d-M-Y'),
                "reqrefid" => Util::generateRandom(ConstantList::REFERENCE_LENGTH),
                "initiatedby" => "backoffice userid",
                "primaryarg" => [
                    "fspmode" => ConstantList::FSP_MODE,
                ]
            ]
        ];

        $token = $this->getToken();
        $response = HttpHelper::sendCurlRequest($url, ConstantList::METHOD_POST, $payload, '', '', $token);

        if (!isset($response->stscode) || $response->stscode !== ConstantList::HDPAY_SUCCESS) {
            Log::error("Failed to get FSP details: ", ['response' => $response]);
            return;
        }
        $this->storeFsp(json_decode($response, true));
    }

    private function getToken()
    {
        return TokenServiceController::fetchJwtToken();
    }

    private function storeFsp($response): void
    {
        try {
            if (isset($response['fspdetails']['prielements'])) {
                FspCode::query()->delete();

                foreach ($response['fspdetails']['prielements'] as $fsp) {
                    $data = [
                        'fsp_name' => $fsp['fspname'],
                        'fsp_code' => $fsp['fspcode'],
                        'status' => $fsp['active'],
                    ];

                    FspCode::query()->create($data);
                }
            } else {
                Log::error("FSP details are missing in the response");
                return;
            }
        } catch (\Exception $e) {
            Log::error("Failed to store FSP details", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return;
        }
    }

}
