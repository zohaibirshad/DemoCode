<?php



// ************************************ ADMIN SECTION **********************************************

Route::get('/', 'Front\FrontendController@home')->name('front.home');

Route::get('/', 'Admin\AdminController@error')->name('admin.error');


Route::get('/create_store',function(){
    return view('create_store');

});



Route::post('/create_store1','CreateStoreController@CreateStore');


Route::post('/checkstorename','CreateStoreController@checkStoreName');





Route::group([

    'prefix' => '{storename}'

], function() {

  View::composer(['*'],function($settings){
  
  $storename = \Route::current()->parameter('storename');
  
  $settings->with('gs', App\Models\Generalsetting::where('storename',$storename)->first());

  $settings->with('seo', DB::table('seotools')->where('storename',$storename)->first());
  $settings->with('categories', App\Models\Category::where([['status','=',1],['storename',$storename]])->get());   
  

  if (\Session::has('language')) 
  {
      $data = DB::table('languages')->where([['id',\Session::get('language')],['storename',$storename]])->first();
    
      if($data == null)
      {
        $data = DB::table('languages')->where([['is_default','=',1],['storename',$storename]])->first();
      }

      $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
      $lang = json_decode($data_results);
      $settings->with('langg', $lang);
  }
  else
  {
      $data = DB::table('languages')->where([['is_default','=',1],['storename',$storename]])->first();
      if($data)
      {
        $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
        $lang = json_decode($data_results);
        $settings->with('langg', $lang);
      }
      
  }  

  if (!\Session::has('popup')) 
  {
      $settings->with('visited', 1);
  }
  \Session::put('popup' , 1);
   
});

Route::prefix('admin')->group(function() {


 

  //------------ ADMIN LOGIN SECTION ------------



  Route::get('/login', 'Admin\LoginController@showLoginForm')->name('admin.login');

  Route::post('/login', 'Admin\LoginController@login')->name('admin.login.submit');

  Route::get('/forgot', 'Admin\LoginController@showForgotForm')->name('admin.forgot');

  Route::post('/forgot', 'Admin\LoginController@forgot')->name('admin.forgot.submit');

  Route::get('/logout', 'Admin\LoginController@logout')->name('admin.logout');



  //------------ ADMIN LOGIN SECTION ENDS ------------



  //------------ ADMIN NOTIFICATION SECTION ------------



  // User Notification

  Route::get('/user/notf/show', 'Admin\NotificationController@user_notf_show')->name('user-notf-show');

  Route::get('/user/notf/count','Admin\NotificationController@user_notf_count')->name('user-notf-count');

  Route::get('/user/notf/clear','Admin\NotificationController@user_notf_clear')->name('user-notf-clear');

  // User Notification Ends



  // Order Notification

  Route::get('/order/notf/show', 'Admin\NotificationController@order_notf_show')->name('order-notf-show');

  Route::get('/order/notf/count','Admin\NotificationController@order_notf_count')->name('order-notf-count');

  Route::get('/order/notf/clear','Admin\NotificationController@order_notf_clear')->name('order-notf-clear');

  // Order Notification Ends



  // Product Notification

  Route::get('/product/notf/show', 'Admin\NotificationController@product_notf_show')->name('product-notf-show');

  Route::get('/product/notf/count','Admin\NotificationController@product_notf_count')->name('product-notf-count');

  Route::get('/product/notf/clear','Admin\NotificationController@product_notf_clear')->name('product-notf-clear');

  // Product Notification Ends




  //------------ ADMIN NOTIFICATION SECTION ENDS ------------



  //------------ ADMIN DASHBOARD & PROFILE SECTION ------------

  Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');

  Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
  
  Route::get('/data', 'Admin\DashboardController@getdata')->name('admin.data');
  

  Route::get('/profile', 'Admin\DashboardController@profile')->name('admin.profile');

  Route::post('/profile/update', 'Admin\DashboardController@profileupdate')->name('admin.profile.update');

  Route::get('/password', 'Admin\DashboardController@passwordreset')->name('admin.password');

  Route::post('/password/update', 'Admin\DashboardController@changepass')->name('admin.password.update');

  

  Route::get('/analytics', 'Admin\DashboardController@analytics')->name('admin-analytics');

  Route::post('/get-analytics', 'Admin\DashboardController@get_analytics')->name('admin-get-analytics');

  //------------ ADMIN DASHBOARD & PROFILE SECTION ENDS ------------





  //------------ ADMIN ORDER SECTION ------------

  Route::group(['middleware'=> 'permissions:orders'],function(){

    Route::get('/orders/datatables/{slug}', 'Admin\OrderController@datatables')->name('admin-order-datatables');

  });

  

  //------------ ADMIN ORDER SECTION ENDS------------





  //------------ ADMIN PRODUCT SECTION ------------

Route::group(['middleware'=>'permissions:products'],function(){
  Route::get('/products/datatables', 'Admin\ProductController@datatables')->name('admin-prod-datatables');
  Route::get('/products/deactive/datatables', 'Admin\ProductController@deactivedatatables')->name('admin-prod-deactive-datatables');
  Route::get('/products/catalogs/datatables', 'Admin\ProductController@catalogdatatables')->name('admin-prod-catalog-datatables');
});



 //------------ ADMIN PRODUCT DISCUSSION SECTION ENDS ------------





  //------------ ADMIN COUPON SECTION ------------




  //------------ ADMIN PAGE SETTINGS SECTION ------------



 
  // PAGE SECTION ENDS



// ************************************ FRONT SECTION ENDS**********************************************









  });

});



