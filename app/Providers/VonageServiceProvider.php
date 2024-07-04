<?php

namespace App\Providers;

use App\Services\VonageCallService;
use App\Services\VonageSMSService;
use Illuminate\Support\ServiceProvider;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\Client\Credentials\Keypair;

class VonageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Using Key Pair for call services
        $this->app->bind(Client::class . '.keypair', function ($app) {
            $private_key = file_get_contents(base_path('private.key'));
            $vonage_app_id = config('vonage.app_id'); // see developer.vonage.com for guide on how to generate application id
            $keypair = new Keypair($private_key, $vonage_app_id);
            return new Client($keypair);
        });

        // Using API Key and Secret for sms
        $this->app->bind(Client::class . '.basic', function ($app) {
            $basic = new Basic(config('vonage.api_key'), config('vonage.api_secret'));
            return new Client($basic);
        });

        $this->app->singleton(VonageSMSService::class, function ($app) {
            return new VonageSMSService($app->make(Client::class . '.basic'));
        });

        $this->app->singleton(VonageCallService::class, function ($app) {
            return new VonageCallService($app->make(Client::class . '.keypair'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
