<?php

namespace App\Providers;

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

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin()) {
                $view->with('pending_requests', \App\Models\InventoryRequest::where('status', 'Pending')->with(['user', 'item'])->latest()->get());
                $view->with('low_stock_items', \App\Models\Item::where('stock_quantity', '<=', 5)->get());
            }
        });
    }
}
