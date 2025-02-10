<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;
    protected $phoneNumber;
    private const MHB_SMS_HEADER = "MHB BANK";

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text, $phoneNumber)
    {
        $this->credentials['channel'] = env('SMS_CHANNEL');
        $this->credentials['password'] = env('SMS_CREDENTIAL');

        $this->text = $text;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->sendSms($this->text, $this->phoneNumber);
    }

    /**
     * @throws GuzzleException
     */
    private function sendSms($text, $phoneNumber): void
    {
        $url = env('SMS_GATEWAY_URL');
        $token = env('SMS_GATEWAY_BEARER');
        $client = new Client;

        $payload = [
            "from" => self::MHB_SMS_HEADER,
            "to" => $this->checkPhoneNumber($phoneNumber),
            "body" => $text
        ];

        Log::channel('sms')->info("SMS-REQUEST-TO: " . $phoneNumber);

        $result = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "Bearer $token",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $payload
        ]);

        $response = json_decode($result->getBody());

        Log::channel('sms')->info("SMS-RESPONSE: " . json_encode($response));
    }

    private function checkPhoneNumber($phone): string
    {
        if (str_starts_with($phone, '0')) {
            return '255' . substr($phone, 1);
        }
        return $phone;
    }
}
