<?php

namespace App\Http\Controllers\Services;

use App\Helper\ConstantList;
use App\Helper\HttpHelper;
use App\Http\Controllers\Controller;
use App\Models\ApiCredential;
use App\Models\HdpayToken;
use Illuminate\Support\Facades\Log;

class TokenServiceController extends Controller
{
    public static function fetchJwtToken()
    {
        if (self::checkTokenExpiry()) {
            $url = env('TOKEN_SERVICE_URL');
            $credentials = self::getCredentials();
            $payload = [
                'USERID' => $credentials['username'],
                'PASSWORD' => $credentials['password'],
                'CHCODE' => ConstantList::CHANNEL
            ];
            $token = env('TOKEN_SERVICE_SECRET');
            Log::info("Token Service Payload: " . json_encode($payload));
            $response = HttpHelper::sendCurlRequest($url, ConstantList::METHOD_POST, $payload, '', '', $token);
            Log::info("Token Service Response: " . json_encode($response));
            if ($response) {
                // Store the new token
                self::storeToken($payload, $response);
                return $response->token;
            }
        }

        return HdpayToken::query()->first()?->token;
    }

    public static function storeToken($payload, $response): void
    {
        try {
            HdpayToken::query()->updateOrCreate(
                [],
                [
                    "token_request" => json_encode($payload),
                    "token_response" => json_encode($response),
                    "token" => $response->token,
                    "expires_at" => now()->addSeconds((int)$response->expires),
                    "result" => $response->result,
                    "message" => $response->message,
                    "status_code" => $response->stscode,
                ]
            );
        } catch (\Exception $e) {
            Log::error("Failed to store token", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

    public static function checkTokenExpiry(): bool
    {
        // Returns true if the token has expired
        return !HdpayToken::query()->exists() || HdpayToken::query()->where('expires_at', '<', now())->exists();
    }

    private static function getCredentials(): ?array
    {
        $credentials = ApiCredential::query()->select('id', 'username', 'password')
            ->where('name', "TOKEN_SERVICE")
            ->first();
        if ($credentials) {
            return ['username' => $credentials->username, 'password' => decrypt($credentials->password)];
        }
        return null;
    }
}
