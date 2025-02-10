<?php

namespace App\Providers;

use App\Models\Organization;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * manage serfvice,
         */
        Gate::define('is-org-deleted', function ($user) {

            $id = Auth::user()->organization_id;

            $check = Organization::where(['id' => $id])->first();

            if ($check->status == 2) {

                return false;
            }

            return true;
        });

        Gate::define('has-approval-level', function ($user){

            $org = Organization::query()->select('number_approval')->where(['id' => Auth::user()->organization_id])->first();

            if ($org->number_approval>=1){
                return true;
            }

            return  false;
        });
    }



}
