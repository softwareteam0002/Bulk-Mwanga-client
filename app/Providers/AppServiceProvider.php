<?php

namespace App\Providers;

use App\Console\CredentialRepoCommand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            CredentialRepoCommand::class
        ]);
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('reject_dict_words', 'App\\Validators\\CustomValidationRules@rejectDictionaryWords');
        Validator::extend('safe', 'App\\Validators\\CustomValidationRules@checkSafeInput');

    }


}
