<?php

namespace App\Providers;

use App\Classes\ShopypallMailer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Category;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App;
use Illuminate\Support\Facades\Route;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        
        $admin_lang = DB::table('admin_languages')->where('is_default','=',1)->first();
        App::setlocale($admin_lang->name);
    
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

    }

}
