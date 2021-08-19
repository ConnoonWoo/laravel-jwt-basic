<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Horizon\Horizon;

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
        // horizon windows跑不通， 没有pcntl扩展
        //Horizon::auth(function ($request) {
        //   if (env('APP_ENV','local') == 'local') {
        //       return true;
        //   } else {
        //       $clientIp = $request->getClientIp();
        //       $canIp = env('HORIZON_IP', '127.0.0.1');
        //       return $clientIp == $canIp;
        //   }
        //});

        //
    }
}
