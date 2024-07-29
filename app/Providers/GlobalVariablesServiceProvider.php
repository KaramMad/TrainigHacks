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
    protected const muscleLevel = [
        'Beginner',
        'Intermediate',
        'Advanced',
    ];
    protected const muscleArea_image_men = [
        'Muscle/man_chest2.jpg',
        'Muscle/man_chest2.jpg',
        'Muscle/man_chest2.jpg',
        'Muscle/man_arm3.jpg',
        'Muscle/man_arm3.jpg',
        'Muscle/man_arm3.jpg',
        'Muscle/man_leg2.jpg',
        'Muscle/man_leg2.jpg',
        'Muscle/man_leg2.jpg',
        'Muscle/man_abs1.jpg',
        'Muscle/man_abs1.jpg',
        'Muscle/man_abs1.jpg',
        'Muscle/man_back1.jpg',
        'Muscle/man_back1.jpg',
        'Muscle/man_back1.jpg',
    ];
    protected const muscleArea_image_women = [
        'Muscle/woman_chest4.jpg',
        'Muscle/woman_chest4.jpg',
        'Muscle/woman_chest4.jpg',
        'Muscle/woman_arm1.jpg',
        'Muscle/woman_arm1.jpg',
        'Muscle/woman_arm1.jpg',
        'Muscle/woman_leg1.jpg',
        'Muscle/woman_leg1.jpg',
        'Muscle/woman_leg1.jpg',
        'Muscle/woman_abs1.jpg',
        'Muscle/woman_abs1.jpg',
        'Muscle/woman_abs1.jpg',
        'Muscle/woman_back1.jpg',
        'Muscle/woman_back1.jpg',
        'Muscle/woman_back1.jpg',
    ];
    protected const category_image = [
        'MuscleCategory/warmup.png',
        'MuscleCategory/strength.png',
        'MuscleCategory/carb.png',
        'MuscleCategory/yoga.png',
        'MuscleCategory/strength.png',
    ];
    protected const main_category_image_men = [
        'MuscleCategory/warmUpMen.jpg',
        'MuscleCategory/strengthmen.jpg',
        'MuscleCategory/yogaMen.jpg',
        'MuscleCategory/yogaMen.jpg',
        'MuscleCategory/warmUpMen.jpg',

    ];
    protected const main_category_image_women = [
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
    protected const FastWorkout = [
        'On The Bed',
        'ProLong Sleep',
        'Sun Salute',
    ];
    protected const FastWorkout_Description = [
        'Can Help Build Strength,Endurance,And balance In Different Parts Of Body',
        'Some Easy and Effective Exercises To Promote Better Sleep',
        'It Is A Yoga Exercise That Involves A Sequence Of Poses OutDoors',
    ];
    protected const FastWorkout_Images = [
        'ExerciseType/bed.jpg',
        'ExerciseType/sleep.jpg',
        'ExerciseType/sun.jpg',
    ];
    protected const product_category =[
        'Clothes',
        'Sports_equipment',
        'Food_Supplements',
    ];
    protected const product_color =[
        'white',
        'black',
        'red',
        'green',
        'pink',
        'blue',
        'brown',
        'yellow',
    ];
    protected const product_size =[
        'XS',
        'S',
        'L',
        'XL',
        'XXL',
    ];
    protected const category_porduct_image=[
        'ProductCategory/Clothes.png',
        'ProductCategory/Food.png',
        'ProductCategory/SportsEquipment.png'
    ];
    protected const product_measuring_unit=[
        'kg',
        'g',
        'pound',
        'meter',
        'km',
    ];
    protected const sub_Categories=[
        'shorts',
        'pants',
        'shirts',
        'T-shirt',
        'socks',
    ];
    public static function sub_Categories(){
        return self::sub_Categories;
    }
    public static function product_category():array{
        return self::product_category;
    }
    public static function product_color():array{
        return self::product_color;
    }
    public static function product_size():array{
        return self::product_size;
    }
    public static function category_porduct_image():array{
        return self::category_porduct_image;
    }
    public static function FastWorkout():array
    {
        return self::FastWorkout;
    }
    public static function FastWorkout_Description():array
    {
        return self::FastWorkout_Description;
    }
    public static function FastWorkout_Images():array
    {
        return self::FastWorkout_Images;
    }
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
    public static function muscleLevel(): array
    {
        return self::muscleLevel;
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
