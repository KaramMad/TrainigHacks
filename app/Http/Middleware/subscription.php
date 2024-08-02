<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class subscription
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $coachId = $request->route('coach_id');
        $subscription = $user->subscribedCoaches()->where('status',true)->where('coach_id', $coachId)->first();
        if (!$subscription) {
            return response()->json(['error' => 'You have to pay first to access this plan'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
