<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        $user_roles = $user->roles->pluck('name')->toArray();
        // dd($user_roles);
        if($user && count(array_intersect($roles, $user_roles)) > 0){
        return $next($request);
        }
        // return $next($request);
        return redirect()->back()->withError('Unauthorized');
    }
}
