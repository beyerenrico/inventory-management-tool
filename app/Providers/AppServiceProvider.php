<?php

namespace App\Providers;

use App\Models\OrderItem;
use App\Observers\OrderItemObserver;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Money\Money;

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
        LanguageSwitch::configureUsing(static function (LanguageSwitch $switch) {
            $switch
                ->locales(['en','de']);
        });

        Lang::stringable(function (Money $money) {
            return $money->formatTo('de_DE');
        });

        OrderItem::observe(OrderItemObserver::class);
    }
}
