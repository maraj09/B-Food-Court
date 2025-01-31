<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\Customer\AppController as CustomerAppController;

class DynamicRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            return;
        }
        $settings = Setting::first();

        Route::middleware(['web', 'customRoleCheck:customer'])
            ->group(function () use ($settings) {
                if ($settings->items_status) {
                    Route::get('/food-items', [CustomerAppController::class, 'foodItemIndex']);
                }
                if ($settings->play_area_status) {
                    Route::get('/play-areas', [CustomerAppController::class, 'playAreaIndex']);
                }
                if ($settings->event_status) {
                    Route::get('/events', [CustomerAppController::class, 'eventIndex']);
                }
            });
    }
}
