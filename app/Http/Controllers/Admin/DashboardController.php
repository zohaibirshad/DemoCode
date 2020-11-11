<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use InvalidArgumentException;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Blog;
use App\Models\User;
use App\Models\Product;
use App\Models\Counter;
use Session;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:admin');
  }

  public function index($storename)
  {
      
    $pending = Order::where('storename',$storename)->where('status','=','pending')->get();
    $processing = Order::where('storename',$storename)->where('status','=','processing')->get();
    $completed = Order::where('storename',$storename)->where('status','=','completed')->get();
    $days = "";
    $sales = "";
    for($i = 0; $i < 7; $i++) {
      $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";

      $sales .=  "'".Order::where('storename',$storename)->where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
    }
    $users = User::where('storename',$storename)->get();
    $products = Product::where('storename',$storename)->get();
    $blogs = Blog::where('storename',$storename)->get();
    $pproducts = Product::where('storename',$storename)->orderBy('id','desc')->take(5)->get();
    $rorders = Order::where('storename',$storename)->orderBy('id','desc')->take(5)->get();
    $poproducts = Product::where('storename',$storename)->orderBy('views','desc')->take(5)->get();
    $rusers = User::where('storename',$storename)->orderBy('id','desc')->take(5)->get();
    $referrals = Counter::where('storename',$storename)->where('type','referral')->orderBy('total_count','desc')->take(5)->get();
    $browsers = Counter::where('storename',$storename)->where('type','browser')->orderBy('total_count','desc')->take(5)->get();

    $activation_notify = "";
    if (file_exists(public_path().'/rooted.txt')){
      $rooted = file_get_contents(public_path().'/rooted.txt');
      if ($rooted < date('Y-m-d', strtotime("+10 days"))){
        $activation_notify = "<i class='icofont-warning-alt icofont-4x'></i><br>Please activate your system.<br> If you do not activate your system now, it will be inactive on ".$rooted."!!<br><a href='".url('/admin/activation')."' class='btn btn-success'>Activate Now</a>";
      }
    }


    return view('admin.dashboard',compact('pending','activation_notify','processing','completed','products','users','blogs','days','sales','pproducts','rorders','poproducts','rusers','referrals','browsers','storename'));
  }

  public function getdata(Request $request)
  {

    
    
    $storename = $request->storename;

    
    $days = "";
    $sales = "";


    if($request->has('time'))
    {
    if($request->time == 30){
      $start_date = new Carbon('first day of this month');
    
      $from_date = \Carbon\Carbon::createFromFormat('d/m/yy', date("d/m/yy", strtotime($start_date)));
    
      $to_date = \Carbon\Carbon::createFromFormat('d/m/yy', date("d/m/yy"));
    
      $difference_in_days = $to_date->diffInDays($from_date);
    
      $request->time = $difference_in_days + 1;  
  }

      for($i = 0; $i <= $request->time; $i++) 
      {
        $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";
  
        $sales .=  Order::where('storename',$storename)->where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count().",";
        
      }

      // dd();
      
      $date = date('Y-m-d H:i:s', strtotime('-'.$request->time." days" ));
      $totalSales = Order::where('storename',$storename)->select(DB::raw('(pay_amount*currency_value) AS total_sales'))->where('status','=','completed');

      $totalOrders =  Order::where('storename',$storename);

      if(date('Y-m-d') == date('Y-m-d', strtotime('-'.$request->time." days" )))
      {
         $totalSales->whereDate('created_at', '=', date('Y-m-d'));
         $totalOrders->whereDate('created_at', '=' ,date('Y-m-d'));
      }else{
        $totalSales->whereDate('created_at', '>=' ,$date)->whereDate('created_at' , '<=', date('Y-m-d H:i:s'));
        $totalOrders->whereDate('created_at',">=", $date)->whereDate('created_at' , '<=', date('Y-m-d H:i:s'));
      }



      $totalSales = $totalSales->get()->sum('total_sales');

      $totalOrders =  $totalOrders->where('status','=','completed')->get()->count();

      return response()->json(['days' => $days, 'sales' => $sales, 'totalSales' => $totalSales, 'totalOrders' => $totalOrders]);

    }
    
    $dates = explode("-", $request->date);

    $from = \Carbon\Carbon::createFromFormat('d/m/yy', str_replace(" ","",$dates[0]));
    $to = \Carbon\Carbon::createFromFormat('d/m/yy', str_replace(" ","",$dates[1]));
    $diff_in_days = $to->diffInDays($from);
    $dateFrom = str_replace(" ", "",$dates[0]);

    $dateFrom = str_replace("/", "-", $dateFrom);
  
    $dateTo = str_replace(" ", "",$dates[1]);

    $dateTo = str_replace("/", "-", $dateTo);

    for($i = 0; $i < $diff_in_days; $i++) {
      $days .= "'".date("d M", strtotime($dateFrom. '+'. $i .' days'))."',";

      $sales .=  Order::where('storename',$storename)->where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime($dateFrom. '+'. $i .' days')))->count().",";
    
    }
    
    $dateFrom = date("yy-m-d", strtotime($dateFrom));
    $dateTo = date("yy-m-d", strtotime($dateTo));

   $totalSales = Order::where('storename',$storename)->select(DB::raw('(pay_amount*currency_value) AS total_sales'))->where('status','=','completed')->whereBetween('created_at', [$dateFrom, $dateTo])->get()->sum('total_sales');

   $totalOrders =  Order::where('storename',$storename)->whereBetween('created_at', [$dateFrom, $dateTo])->where('status','=','completed')->get()->count();


    return response()->json(['days' => $days, 'sales' => $sales, 'totalSales' => $totalSales, 'totalOrders' => $totalOrders]);
  }
 
  public function profile($storename)
  {
    $data = Auth::guard('admin')->user();
    return view('admin.profile',compact('data','storename'));
  }

  public function profileupdate(Request $request,$storename)
  {
        //--- Validation Section
    $this->validate($request,[
      'name' => 'required',
      'photo' => 'mimes:jpeg,jpg,png,svg',
      'email' => 'required|unique:admins,email,'.Auth::guard('admin')->user()->id,
      'phone' => 'required',

    ]);
    // $rules =
    // [
    //   'photo' => 'mimes:jpeg,jpg,png,svg',
    //   'email' => 'unique:admins,email,'.Auth::guard('admin')->user()->id
    // ];


    // $validator = Validator::make(Input::all(), $rules);

    // if ($validator->fails()) {
    //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    // }
        //--- Validation Section Ends
    $input = $request->all();
    $data = Auth::guard('admin')->user();
    if ($file = $request->file('photo'))
    {
      $name = time().$file->getClientOriginalName();
      $file->move('assets/images/admins/',$name);
      if($data->photo != null)
      {
        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
          // unlink(public_path().'/assets/images/admins/'.$data->photo);
        }
      }
      $input['photo'] = $name;
    }
    $data->update($input);
    $msg = 'Successfully updated your profile';
    Session::put('success',$msg);
    return redirect()->back();  
  }

  public function passwordreset($storename)
  {
    $data = Auth::guard('admin')->user();
    return view('admin.password',compact('data','storename'));
  }

  public function changepass(Request $request,$storename)
  {
    $this->validate($request,[
      'cpass' => 'required',
      'newpass' => 'required',
      'renewpass' => 'required',

    ]);
    $admin = Auth::guard('admin')->user();
    if ($request->cpass){
      if (Hash::check($request->cpass, $admin->password)){
        if ($request->newpass == $request->renewpass){
          $input['password'] = Hash::make($request->newpass);
        }else{
          return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));
        }
      }else{
        return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));
      }
    }
    $admin->update($input);
    $msg = 'Successfully change your passwprd';
    Session::put('success',$msg);
        return redirect()->back();  
  }



  public function generate_bkup($storename)
  {
    $bkuplink = "";
    $chk = file_get_contents('backup.txt');
    if ($chk != ""){
      $bkuplink = url($chk);
    }
    return view('admin.movetoserver',compact('bkuplink','chk','storename'));
  }


  public function clear_bkup($storename)
  {
    $destination  = public_path().'/install';
    $bkuplink = "";
    $chk = file_get_contents('backup.txt');
    if ($chk != ""){
      // unlink(public_path($chk));
    }

    if (is_dir($destination)) {
      $this->deleteDir($destination);
    }
    $handle = fopen('backup.txt','w+');
    fwrite($handle,"");
    fclose($handle);
        //return "No Backup File Generated.";
    return redirect()->back()->with('success','Backup file Deleted Successfully!');
  }


  public function activation($storename)
  {
    $activation_data = "";
    if (file_exists(public_path().'/project/license.txt')){
      $license = file_get_contents(public_path().'/project/license.txt');
      if ($license != ""){
        $activation_data = "<i style='color:darkgreen;' class='icofont-check-circled icofont-4x'></i><br><h3 style='color:darkgreen;'>Your System is Activated!</h3><br> Your License Key:  <b>".$license."</b>";
      }
    }
    return view('admin.activation',compact('activation_data','storename'));
  }


  public function activation_submit(Request $request,$storename)
  {

    $purchase_code =  $request->pcode;

    $chk = array();
    $chk['status'] = 'success';
    $chk['p2'] = '\/project\/vendor\/markury\/src\/Adapter\/marcuryBase.txt';
    $chk['lData'] = 'VALID';


    if($chk['status'] != "success")
    {

      $msg = $chk['message'];
      Session::put('success',$msg);
        return redirect()->back();  
            //return redirect()->back()->with('unsuccess',$chk['message']);

    }else{
            // $this->setUp($chk['p2'],$chk['lData']);

      if (file_exists(public_path().'/rooted.txt')){
        // unlink(public_path().'/rooted.txt');
      }

      $fpbt = fopen(public_path().'/project/license.txt', 'w');
            // dd($fpbt);
      fwrite($fpbt, $purchase_code);
      fclose($fpbt);

      $msg = 'Congratulation!! Your System is successfully Activated.';
      Session::put('success',$msg);
        return redirect()->back();  
            //return redirect('admin/dashboard')->with('success','Congratulation!! Your System is successfully Activated.');
    }
  }

  function setUp($mtFile,$goFileData,$storename){

    $fpa = fopen(public_path().$mtFile, 'w');

    $get = fwrite($fpa, $goFileData);

    fclose($fpa);
  }



  public function movescript($storename){
    ini_set('max_execution_time', 3000);
    $destination  = public_path().'/install';
    $chk = file_get_contents('backup.txt');
    if ($chk != ""){
      // unlink(public_path($chk));
    }

    if (is_dir($destination)) {
      $this->deleteDir($destination);
    }

    $src = base_path().'/vendor/update';
    $this->recurse_copy($src,$destination);
    $files = public_path();
    $bkupname = 'Shopypall-'.date('Y-m-d').'.zip';

    $zipper = new \Chumper\Zipper\Zipper;

    $zipper->make($bkupname)->add($files);

    $zipper->remove($bkupname);

    $zipper->close();

    $handle = fopen('backup.txt','w+');
    fwrite($handle,$bkupname);
    fclose($handle);

    if (is_dir($destination)) {
      $this->deleteDir($destination);
    }
    return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname],200);
  }

  public function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
        }
        else {
          copy($src . '/' . $file,$dst . '/' . $file);
        }
      }
    }
    closedir($dir);
  }

  public function deleteDir($dirPath) {
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
        // unlink($file);
      }
    }
    rmdir($dirPath);
  }

  public function analytics($storename)
  {
    $days = "";
    $sales = "";
    for($i = 0; $i < 7; $i++) {
      $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";

      $sales .=  "'".Order::where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
    }
    return view('admin.analytics',compact('days','sales','storename'));
  }

  public function get_analytics(Request $request,$storename)
  {
    $submit_days = $request->days;

    $days = "";
    $sales = "";
    for($i = 0; $i < $submit_days; $i++) {
      $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";

      $sales .=  "'".Order::where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
    }

    return view('admin.analytics',compact('days','sales','storename')); 
  }
}
