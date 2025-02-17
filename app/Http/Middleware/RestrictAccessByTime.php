<?php
namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictAccessByTime
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is authenticated
        if (Auth::guard('web')->check()) { 
            $user = Auth::user();
            
            // Use Spatie's role system instead of checking 'role' column directly
            if ($user && !$user->hasRole(['Admin', 'SuperAdmin','Permanence'])) {
                $now = Carbon::now();
                $hour = $now->hour;
                $minute = $now->minute;
                $dayOfWeek = $now->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

                // Restrict access on Sunday (day 0)
                if ($dayOfWeek == 0) {
                    abort(Response::HTTP_FORBIDDEN, 'Accès refusé : Dimanche non férié. Aucun accès autorisé.');
                }

                // Restrict access on Monday to Friday before 8:00 AM or after 6:45 PM
                if ($dayOfWeek >= 1 && $dayOfWeek <= 5 && ($hour < 8 || ($hour == 18 && $minute >= 45) || $hour >= 19)) {
                    abort(Response::HTTP_FORBIDDEN, 'Accès refusé du lundi au vendredi : Disponible uniquement de 8h à 18h45.');
                }

                // Restrict access on Saturday before 8:00 AM or after 3:45 PM
                if ($dayOfWeek == 6 && ($hour < 8 || ($hour == 15 && $minute >= 45) || $hour >= 16)) {
                    abort(Response::HTTP_FORBIDDEN, 'Accès refusé le samedi : Disponible uniquement de 8h à 15h45.');
                }
            }
        }

        return $next($request);
    }
}
