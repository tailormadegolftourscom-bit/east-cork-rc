<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Venue;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        // The activity list appears on several otherwise static pages, so it
        // fetches its own data rather than every route passing it in.
        View::composer('partials.activity-list', function ($view) {
            $view->with('listedActivities', Activity::with('venue')->listed()->get());
        });

        View::composer(['public.activities', 'public.resources'], function ($view) {
            $view->with('venues', Venue::active()->ordered()->get());
        });
    }
}
