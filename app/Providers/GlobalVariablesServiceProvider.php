<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GlobalVariablesServiceProvider extends ServiceProvider
{

    protected const muscleArea = [
         'CHEST',
         'ARM',
         'LEG',
         'ABS',
         'SHOULDER&Back'
    ];
    public static function muscleArea(): array
    {
        return self::muscleArea;
    }







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
    public function boot(): void
    {
        //
    }
}
