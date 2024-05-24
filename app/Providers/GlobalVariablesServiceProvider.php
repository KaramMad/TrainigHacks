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

    protected const muscleCategory = [
        'warm_up',
        'strength',
        'cardio',
        'yoga',
        'fast_workout'

    ];

    protected const focusArea = [
        'abs',
        'chest',
        'shoulder',
        'biceps',
        'triceps',
        'quadriceps',
        'hamstring',
        'glutes',
        'calves',
        'back',
    ];
    public static function muscleArea(): array
    {
        return self::muscleArea;
    }

    public static function muscleCategory(): array
    {
        return self::muscleCategory;
    }

    public static function focusArea(): array
    {
        return self::focusArea;
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
