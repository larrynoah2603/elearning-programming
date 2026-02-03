<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Please login.'], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $user = auth()->user();

        if (!$user->isSubscribed()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Subscription required.'], 403);
            }
            
            if ($user->isSubscriptionExpired()) {
                return redirect()->route('subscription.expired')
                    ->with('warning', 'Votre abonnement a expiré. Veuillez le renouveler pour continuer.');
            }
            
            return redirect()->route('subscription.plans')
                ->with('info', 'Cette fonctionnalité nécessite un abonnement. Découvrez nos offres !');
        }

        return $next($request);
    }
}
