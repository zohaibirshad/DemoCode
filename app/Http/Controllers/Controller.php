<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Store;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        
        $storename = \Route::current()->parameter('storename');
        $storeExist = Store::where('storename',$storename)->exists();

        if(!$storeExist)
        {
            echo "<h1 style='padding: 300px;margin-left: 306px;'>404 | Not Found</h1>";
            return;
        }
    }
}
