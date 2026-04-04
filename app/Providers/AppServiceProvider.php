<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policy with Customer as the authorizable model
        Gate::policy(Order::class, OrderPolicy::class);

        // Tell Gate to use the customer guard for storefront authorization checks
        Gate::guessPolicyNamesUsing(function (string $modelClass): ?string {
            return match ($modelClass) {
                Order::class => OrderPolicy::class,
                default      => null,
            };
        });
    }
}
