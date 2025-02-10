<?php

namespace App\Jobs;

use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(){

    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle()
    {
		Log::info('Reach here');
       $users_list = User::query()->select('username')->limit(10)->get();
       Log::info($users_list);
    }
}
