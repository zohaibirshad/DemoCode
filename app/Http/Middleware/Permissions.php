<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Route;
class Permissions
{

    public function handle($request, Closure $next,$data)
    {
        $storename = Route::current()->parameter('storename');
        if (Auth::guard('admin')->check()) {
            if(Auth::guard('admin')->user()->role_id == 0){
                return $next($request);
            }
            // if(Auth::guard('admin')->user()->role_id == 0){
            //     return redirect()->route('admin.dashboard')->with('unsuccess',"You don't have access to that section"); 
            // }
            if (Auth::guard('admin')->user()->sectionCheck($data)){
                return $next($request);
            }
        }
        return redirect()->route('admin.dashboard',$storename)->with('unsuccess',"You don't have access to that section"); 
    }
}
