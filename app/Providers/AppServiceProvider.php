<?php

namespace App\Providers;

use App\Models\Passport\Client;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::hashClientSecrets();
        Passport::useClientModel(Client::class);
        Passport::enablePasswordGrant();
        Passport::authorizationView(
            fn($parameters) => Inertia::render('auth/oauth/Authorize', [
                'request' => $parameters['request'],
                'authToken' => $parameters['authToken'],
                'client' => $parameters['client'],
                'user' => $parameters['user'],
                'scopes' => $parameters['scopes'],
            ])
        );
        Passport::tokensExpireIn(now()->addMinutes(config('services.oauth.token_expiration')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('services.oauth.refresh_token_expiration')));
        Passport::tokensCan(config('openid.passport.tokens_can'));
    }
}
