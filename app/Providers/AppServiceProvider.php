<?php

namespace App\Providers;

use App\Models\V1\ActionRequest;
use App\Policies\V1\ActionRequestPolicy;
use Illuminate\Support\ServiceProvider;

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
        //
    }

    protected $policies = [
        ActionRequest::class => ActionRequestPolicy::class,
    ];
}
