<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $method = match ($role) {
            'is-admin' => 'isAdmin',
            'is-teacher' => 'isTeacher',
            'is-student' => 'isStudent',
            'is-parent' => 'isParent',
            default => null,
        };

        if ($method && $user->$method()) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}