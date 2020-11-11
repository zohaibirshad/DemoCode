<?php

namespace App\Http\Middleware;
use App\Models\Generalsetting;
use Closure;
use Auth;
use Route;

class MaintenanceMode
{
    public function handle($request, Closure $next)

    {
        // $storename = Route::current()->parameter('storename');
        // dd(Auth::user()->storename);
        // $gs = Generalsetting::find(1);
        // dd()
        $current_params = Route::current()->parameters();
        // dd($current_params);
        $storename = $current_params['storename'];
        // dd($storename);

        $gs = Generalsetting::where('storename', $storename)->first();

        // dd($gs);
        if($gs)
        {
          if($gs->is_maintain == 1) {

                    return redirect()->route('front-maintenance',$storename);

            }  
        }
            


            return $next($request);

    }
}
