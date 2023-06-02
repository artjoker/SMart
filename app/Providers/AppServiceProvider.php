<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionItem;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Cashier::ignoreMigrations();
        Cashier::useCustomerModel(Client::class);
        Cashier::useSubscriptionModel(Subscription::class);
        Cashier::useSubscriptionItemModel(SubscriptionItem::class);

        //stripe init
        $this->app->singleton(StripeClient::class, function () {
            return new StripeClient($this->app['config']['services.stripe.secret']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
