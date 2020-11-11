<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ShopypallMailer;
use App\Models\Generalsetting;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Models\Store;


class LoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm($storename)
    {
	  $storeExist = Store::where('storename',$storename)->exists();
    
    if(!$storeExist)
    {
        return view('error');
    }

      return view('admin.login',compact('storename'));
    }

    public function login(Request $request,$storename)
    {
        //--- Validation Section
        $rules = [
                  'email'   => 'required|email',
                  'password' => 'required'
                ];

        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password,'storename'=> $storename], $request->remember)) {
        // if successful, then redirect to their intended location
        return response()->json(url($storename.'/admin/dashboard'));
      }

      // if unsuccessful, then redirect back to the login with the form data
          return response()->json(array('errors' => [ 0 => 'Credentials Doesn\'t Match !' ]));     
    }

    public function showForgotForm($storename)
    {
      return view('admin.forgot',compact('storename'));
    }

    public function forgot(Request $request,$storename)
    {
      $gs = Generalsetting::where('storename',$storename)->first();
      $input =  $request->all();
      if (Admin::where('email', '=', $request->email)->count() > 0) {
      // user found
      $admin = Admin::where('email', '=', $request->email)->firstOrFail();
      $autopass = str_random(8);
      $input['password'] = bcrypt($autopass);
      $admin->update($input);
      $subject = "Reset Password Request";
      $msg = "Your New Password is : ".$autopass;
      if($gs->is_smtp == 1)
      {
          $data = [
                  'to' => $request->email,
                  'subject' => $subject,
                  'body' => $msg,
          ];

          $mailer = new ShopypallMailer();
          $mailer->sendCustomMail($data);                
      }
      else
      {
          $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
          mail($request->email,$subject,$msg,$headers);            
      }
      return response()->json('Your Password Reseted Successfully. Please Check your email for new Password.');
      }
      else{
      // user not found
      return response()->json(array('errors' => [ 0 => 'No Account Found With This Email.' ]));    
      }  
    }

    public function logout($storename)
    {
        Auth::guard('admin')->logout();
        return redirect($storename.'/admin/login');
    }
}
