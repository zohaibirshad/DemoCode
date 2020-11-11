<?php

namespace App\Http\Controllers\Admin;
use App\Models\Generalsetting;
use Artisan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class GeneralSettingController extends Controller
{

  protected $rules =
  [
    'logo'              => 'mimes:jpeg,jpg,png,svg',
    'favicon'           => 'mimes:jpeg,jpg,png,svg',
    'loader'            => 'mimes:gif',
    'admin_loader'      => 'mimes:gif',
    'affilate_banner'   => 'mimes:jpeg,jpg,png,svg',
    'error_banner'      => 'mimes:jpeg,jpg,png,svg',
    'popup_background'  => 'mimes:jpeg,jpg,png,svg',
    'invoice_logo'      => 'mimes:jpeg,jpg,png,svg',
    'user_image'        => 'mimes:jpeg,jpg,png,svg',
    'footer_logo'        => 'mimes:jpeg,jpg,png,svg',
  ];

  public function __construct()
  {
    $this->middleware('auth:admin');
  }


  private function setEnv($storename,$key, $value,$prev)
  {
    file_put_contents(app()->environmentFilePath(), str_replace(
      $key . '=' . $prev,
      $key . '=' . $value,
      file_get_contents(app()->environmentFilePath())
    ));
  }

    // Genereal Settings All post requests will be done in this method
  public function generalupdate(Request $request,$storename)
  {
    $this->validate($request,[
      'logo'              => 'mimes:jpeg,jpg,png,svg',
      'favicon'           => 'mimes:jpeg,jpg,png,svg',
      'loader'            => 'mimes:gif',
      'admin_loader'      => 'mimes:gif',
      'affilate_banner'   => 'mimes:jpeg,jpg,png,svg',
      'error_banner'      => 'mimes:jpeg,jpg,png,svg',
      'popup_background'  => 'mimes:jpeg,jpg,png,svg',
      'invoice_logo'      => 'mimes:jpeg,jpg,png,svg',
      'user_image'        => 'mimes:jpeg,jpg,png,svg',
      'footer_logo'        => 'mimes:jpeg,jpg,png,svg',  
    ]);
        //--- Validation Section
        // $validator = Validator::make(Input::all(), $this->rules);

        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        // else {
    $input = $request->all();
    $data = Generalsetting::where('storename',$storename)->first();
    if ($file = $request->file('logo'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->logo);
      $input['logo'] = $name;
    }
    if ($file = $request->file('favicon'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->favicon);
      $input['favicon'] = $name;
    }
    if ($file = $request->file('loader'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->loader);
      $input['loader'] = $name;
    }
    if ($file = $request->file('admin_loader'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->admin_loader);
      $input['admin_loader'] = $name;
    }
    if ($file = $request->file('affilate_banner'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->affilate_banner);
      $input['affilate_banner'] = $name;
    }
    if ($file = $request->file('error_banner'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->error_banner);
      $input['error_banner'] = $name;
    }
    if ($file = $request->file('popup_background'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->popup_background);
      $input['popup_background'] = $name;
    }
    if ($file = $request->file('invoice_logo'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->invoice_logo);
      $input['invoice_logo'] = $name;
    }
    if ($file = $request->file('user_image'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->user_image);
      $input['user_image'] = $name;
    }

    if ($file = $request->file('footer_logo'))
    {
      $name = time().$file->getClientOriginalName();
      $data->upload($name,$file,$data->footer_logo);
      $input['footer_logo'] = $name;
    }

    $data->update($input);
        //--- Logic Section Ends


    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

        //--- Redirect Section
    $msg = 'Data Updated Successfully.';
    Session::put('success',$msg);
    return redirect()->back();
        //--- Redirect Section Ends
        // }
  }

  public function generalupdatepayment(Request $request,$storename)
  {
        //--- Validation Section
    $this->validate($request,[
      'logo'              => 'mimes:jpeg,jpg,png,svg',
      'favicon'           => 'mimes:jpeg,jpg,png,svg',
      'loader'            => 'mimes:gif',
      'admin_loader'      => 'mimes:gif',
      'affilate_banner'   => 'mimes:jpeg,jpg,png,svg',
      'error_banner'      => 'mimes:jpeg,jpg,png,svg',
      'popup_background'  => 'mimes:jpeg,jpg,png,svg',
      'invoice_logo'      => 'mimes:jpeg,jpg,png,svg',
      'user_image'        => 'mimes:jpeg,jpg,png,svg',
      'footer_logo'        => 'mimes:jpeg,jpg,png,svg',    
    ]);

    if(isset($request->amazon_client_id))
    {
      $data1 = Generalsetting::where('storename',$storename)->first();
      $data1->amazon_client_id = $request->amazon_client_id;
      $data1->amazon_seller_id = $request->amazon_seller_id;
      $data1->save();
    }
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        // //--- Validation Section Ends

        // //--- Logic Section
        // else {
    $input = $request->all();
    $data = Generalsetting::where('storename',$storename)->first();
    $prev = $data->molly_key;  

    if ($request->vendor_ship_info == ""){
      $input['vendor_ship_info'] = 0;
    }

    if ($request->instamojo_sandbox == ""){
      $input['instamojo_sandbox'] = 0;
    }

    if ($request->paypal_mode == ""){
      $input['paypal_mode'] = 'live';
    }
    else {
      $input['paypal_mode'] = 'sandbox';
    }

    if ($request->paytm_mode == ""){
      $input['paytm_mode'] = 'live';
    }
    else {
      $input['paytm_mode'] = 'sandbox';
    }
    $data->update($input);


    // $this->setEnv('MOLLIE_KEY',$data->molly_key,$prev);
        // Set Molly ENV

        //--- Logic Section Ends

        //--- Redirect Section
    $msg = 'Data Updated Successfully.';
    \Session::put('success',$msg);
    return redirect()->back();
        //--- Redirect Section Ends
        // }
  }

  public function logo($storename)
  {
    $gs = Generalsetting::where('storename',$storename)->first();
    return view('admin.generalsetting.logo',compact('storename','gs'));
  }

  public function userimage($storename)
  {
    return view('admin.generalsetting.user_image',compact('storename'));
  }

  public function fav($storename)
  {
    return view('admin.generalsetting.favicon',compact('storename'));
  }

  public function load($storename)
  {
    return view('admin.generalsetting.loader',compact('storename'));
  }

  public function contents($storename)
  {
    return view('admin.generalsetting.websitecontent',compact('storename'));
  }

  public function header($storename)
  {
    return view('admin.generalsetting.header',compact('storename'));
  }

  public function footer($storename)
  {
    return view('admin.generalsetting.footer',compact('storename'));
  }

  public function paymentsinfo($storename)
  {
    return view('admin.generalsetting.paymentsinfo',compact('storename'));
  }

  public function affilate($storename)
  {
    return view('admin.generalsetting.affilate',compact('storename'));
  }

  public function errorbanner($storename)
  {
    return view('admin.generalsetting.error_banner',compact('storename'));
  }

  public function popup($storename)
  {
    return view('admin.generalsetting.popup',compact('storename'));
  }

  public function maintain($storename)
  {
    return view('admin.generalsetting.maintain',compact('storename'));
  }

  public function ispopup($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_popup = $status;
    $data->update();
  }


  public function mship($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->multiple_shipping = $status;
    $data->update();
  }


  public function mpackage($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->multiple_packaging = $status;
    $data->update();
  }


  public function paypal($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->paypal_check = $status;
    $data->update();
  }


  public function instamojo($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_instamojo = $status;
    $data->update();
  }


  public function paystack($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_paystack = $status;
    $data->update();
  }


  public function paytm($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_paytm = $status;
    $data->update();
  }



  public function molly($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_molly = $status;
    $data->update();
  }

  public function razor($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_razorpay = $status;
    $data->update();
  }



  public function stripe($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->stripe_check = $status;
    $data->update();
  }

  public function guest($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->guest_checkout = $status;
    $data->update();
  }

  public function isemailverify($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_verification_email = $status;
    $data->update();
  }


  public function cod($storename,$status)
  {

    $data = Generalsetting::where('storename',$storename)->first();
    $data->cod_check = $status;
    $data->update();
  }

  public function comment($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_comment = $status;
    $data->update();
  }
  public function isaffilate($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_affilate = $status;
    $data->update();
  }

  public function issmtp($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_smtp = $status;
    $data->update();
  }

  public function talkto($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_talkto = $status;
    $data->update();
  }

  public function issubscribe($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_subscribe = $status;
    $data->update();
  }

  public function isloader($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_loader = $status;
    $data->update();
  }

  public function stock($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->show_stock = $status;
    $data->update();
  }

  public function ishome($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_home = $status;
    $data->update();
  }

  public function isadminloader($storename)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_admin_loader = $status;
    $data->update();
  }

  public function isdisqus($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_disqus = $status;
    $data->update();
  }

  public function iscontact($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_contact = $status;
    $data->update();
  }
  public function isfaq($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_faq = $status;
    $data->update();
  }
  public function language($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_language = $status;
    $data->update();
  }
  public function currency($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_currency = $status;
    $data->update();
  }
  public function regvendor($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->reg_vendor = $status;
    $data->update();
  }

  public function iscapcha($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_capcha = $status;
    $data->update();
  }

  public function isreport($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_report = $status;
    $data->update();
  }

  public function issecure($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_secure = $status;
    $data->update();
  }

  public function ismaintain($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_maintain = $status;
    $data->update();
  }

  public function amazon($storename,$status)
  {
    $data = Generalsetting::where('storename',$storename)->first();
    $data->is_amazon = $status;
    $data->update();
  }
}
