<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect admin users who land on the storefront to the Filament admin panel.
 * Prevents admins from accidentally going through the customer checkout flow.
 */
class RedirectAdminToPanel
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === UserRole::Admin) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
