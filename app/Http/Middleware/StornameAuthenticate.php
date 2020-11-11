<?php



namespace App\Http\Middleware;



use Closure;
use Auth;
use Route;

class StornameAuthenticate

{

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */

    public function handle($request, Closure $next)

    {

        $storename = Route::current()->parameter('storename');
        if(Auth::check())
            $loggedInStorename = Auth::user()->storename;
        else
            $loggedInStorename = '';


        if($storename !== $loggedInStorename)

        {
            Auth::logout();

            return redirect()->route('admin.login',$storename);
        }

        return $next($request);

    }

}

