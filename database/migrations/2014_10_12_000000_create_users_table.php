<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->integer('tall')->nullable();
            $table->integer('weight')->nullable();
            $table->string('bio')->default('null');
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('target',['lose_weight','build_muscle','keep_fit']);
            $table->enum('diseases',['heart','knee','breath','none','diabetes','blood_pressure'])->nullable()->default('none');
            $table->enum('activity',['Sedentary','Lightly_Active','Very_Active']);
            $table->time('preferred_time')->nullable();
            $table->unsignedBiginteger('notification_id')->unsigned()->nullable();
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade')->onUpdate('cascade');
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
