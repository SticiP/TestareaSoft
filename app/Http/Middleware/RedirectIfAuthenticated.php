<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check())
        {
            /** @var User $user */
            $user = Auth::user();
            return match ($user->role)
            {
                'admin', 'user' => redirect()->route('home'),
                default => abort(403, 'Invalid Role')
            };
        }
        return $next($request);
    }
}
