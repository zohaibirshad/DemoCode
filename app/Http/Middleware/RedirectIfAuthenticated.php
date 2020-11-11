<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $route = $request->route('username');
      switch ($guard) {
        case 'admin':
          if (Auth::guard($guard)->check()) {
            return redirect()->route('admin.dashboard',$route);
          }
          break;

        default:
          if (Auth::guard($guard)->check()) {
              return redirect()->route('user-dashboard',$route);
          }
          break;
      }

        return $next($request);
    }
}
