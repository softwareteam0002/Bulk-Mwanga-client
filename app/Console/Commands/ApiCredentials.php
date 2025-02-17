<?php

namespace App\Console\Commands;

use App\Http\Controllers\Services\TokenServiceController;
use Illuminate\Console\Command;

class ApiCredentials extends Command
{
    protected $signature = 'store:credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely and securely store API credentials';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
           $token= TokenServiceController::fetchJwtToken();
//            $data = [
//                'name' => 'TOKEN_SERVICE',
//                'username' => env('USER_ID'),
//                'password' => encrypt(env('USER_PWD')),
//            ];
//            ApiCredential::query()->create($data);
            //$this->info("Credentials stored successfully");
            $this->info("Token: {$token}");
        } catch (\Exception $e) {
            $this->error("Failed to store credentials - " . $e->getMessage());
        }
    }

}
