<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (!$user->isTeacher() && !$user->isAdmin())) {
            abort(403, 'Accès réservé aux enseignants.');
        }

        return $next($request);
    }
}
