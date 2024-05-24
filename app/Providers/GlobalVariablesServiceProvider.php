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
    protected const muscleArea_image_men=[
        'Muscle/man_chest2.jpg',
        'Muscle/man_arm3.jpg',
        'Muscle/man_leg2.jpg',
        'Muscle/man_abs1.jpg',
        'Muscle/man_back1.jpg',
    ];
    protected const muscleArea_image_women=[
        'Muscle/woman_chest4.jpg',
        'Muscle/woman_arm1.jpg',
        'Muscle/woman_leg1.jpg',
        'Muscle/woman_abs1.jpg',
        'Muscle/woman_back1.jpg',
    ];
    protected const category_image=[
        'MuscleCategory/warmup.png',
        'MuscleCategory/strength.png',
        'MuscleCategory/carb.png',
        'MuscleCategory/yoga.png',
        'MuscleCategory/strength.png',
    ];
    protected const main_category_image_men=[
        'MuscleCategory/warmUpMen.jpg',
        'MuscleCategory/strengthmen.jpg',
        'MuscleCategory/yogaMen.jpg',
        'MuscleCategory/yogaMen.jpg',
        'MuscleCategory/warmUpMen.jpg',

    ];
    protected const main_category_image_women=[
        'MuscleCategory/warmUpWomen.jpg',
        'MuscleCategory/strengthwomen.jpg',
        'MuscleCategory/yogaWomen.jpg',
        'MuscleCategory/yogaWomen.jpg',
        'MuscleCategory/warmUpWomen.jpg',
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
    public static function muscleArea_image_men(): array
    {
        return self::muscleArea_image_men;
    }
    public static function muscleArea_image_women(): array
    {
        return self::muscleArea_image_women;
    }

    public static function muscleCategory(): array
    {
        return self::muscleCategory;
    }
    public static function category_image(): array
    {
        return self::category_image;
    }
    public static function main_category_image_women(): array
    {
        return self::main_category_image_women;
    }
    public static function main_category_image_men(): array
    {
        return self::main_category_image_men;
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
