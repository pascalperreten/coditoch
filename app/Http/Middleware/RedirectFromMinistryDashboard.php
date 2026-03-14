<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectFromMinistryDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $ministry = $request->ministry;

        if($user->role === 'follow_up') {
            return redirect()->route('events.show', [$user->ministry, $user->events->first()]);
        }
        if(in_array($user->role, ['pastor', 'ambassador', 'church_member'])) {
            return redirect()->route('churches.show', [$ministry, $user->church->events()->first(), $user->church]);
        }
        return $next($request);
    }
}
