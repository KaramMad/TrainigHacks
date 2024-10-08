<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @param bool $status
     * @param string $message
     * @param string $datakey
     * @param mixed $data
     * @param mixed $user
     * @param int $error
     * @return jsonResponse
     */

    public static function apiResponse(
        string $message = "success",
        $data = null,
        string $dataKey = 'data',
        bool $status = true,
        int $error = 200,
       mixed $user = null
    ): JsonResponse {
        return response()->json([
            'status' => $status,
            'message' => $message,
            $dataKey => $data,
            'user' => $user
        ], $error);
    }
}
