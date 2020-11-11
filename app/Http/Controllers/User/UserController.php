<?php



namespace App\Http\Controllers\User;



use App\Classes\ShopypallMailer;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;

use Illuminate\Support\Facades\Input;

use Validator;

use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;

use App\Models\Subscription;

use App\Models\Generalsetting;

use App\Models\UserSubscription;

use App\Models\FavoriteSeller;



class UserController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth');

    }



    public function index($storename)

    {

        $user = Auth::user();  

        return view('user.dashboard',compact('user','storename'));

    }



    public function profile($storename)

    {

        $user = Auth::user();  

        return view('user.profile',compact('user','storename'));

    }



    public function profileupdate(Request $request,$storename)

    {

        //--- Validation Section

        $this->validate($request,[

            'photo' => 'mimes:jpeg,jpg,png,svg',

            'email' => 'unique:users,email,'.Auth::user()->id

        ]);

        // $rules =

        // [

        //     'photo' => 'mimes:jpeg,jpg,png,svg',

        //     'email' => 'unique:users,email,'.Auth::user()->id

        // ];





        // $validator = Validator::make(Input::all(), $rules);

        

        // if ($validator->fails()) {

        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));

        // }

        //--- Validation Section Ends

        $input = $request->all();  

        $data = Auth::user();        

            if ($file = $request->file('photo')) 

            {              

                $name = time().$file->getClientOriginalName();

                $file->move('assets/images/users/',$name);

                if($data->photo != null)

                {

                    if (file_exists(public_path().'/assets/images/users/'.$data->photo)) {

                        unlink(public_path().'/assets/images/users/'.$data->photo);

                    }

                }            

            $input['photo'] = $name;

            } 

        $data->update($input);

        $msg = 'Successfully updated your profile';

        \Session::put('success',$msg);

            return redirect()->back();

    }



    public function resetform($storename)

    {

        return view('user.reset',compact('storename'));

    }



    public function reset(Request $request,$storename)

    {

        $user = Auth::user();

        if ($request->cpass){

            if (Hash::check($request->cpass, $user->password)){

                if ($request->newpass == $request->renewpass){

                    $input['password'] = Hash::make($request->newpass);

                }else{

                    \Session::put('error','Confirm password does not match.');

                    return redirect()->back();

                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));     

                }

            }else{

                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));   

            }

        }

        $user->update($input);

        $msg = 'Successfully change your password';

        \Session::put('success',$msg);

            return redirect()->back();

    }





    public function package($storename)

    {

        $user = Auth::user();

        $subs = Subscription::where('storename',$storename)->get();

        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();

        return view('user.package.index',compact('user','subs','package','storename'));

    }





    public function vendorrequest($storename,$id)

    {

        $subs = Subscription::where('storename',$storename)->findOrFail($id);

        $gs = Generalsetting::where('storename',$storename)->first();

        $user = Auth::user();

        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();

        if($gs->reg_vendor != 1)

        {

            return redirect()->back();

        }

        return view('user.package.details',compact('user','subs','package','storename'));

    }



    public function vendorrequestsub(Request $request,$storename)

    {

        $this->validate($request, [

            'shop_name'   => 'unique:users',

           ],[ 

               'shop_name.unique' => 'This shop name has already been taken.'

            ]);

        $user = Auth::user();

        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();

        $subs = Subscription::where('storename',$storename)->findOrFail($request->subs_id);

        $settings = Generalsetting::where('storename',$storename)->first();

                    $today = Carbon::now()->format('Y-m-d');

                    $input = $request->all();  

                    $user->is_vendor = 2;

                    $user->date = date('Y-m-d', strtotime($today.' + '.$subs->days.' days'));

                    $user->mail_sent = 1;     

                    $user->update($input);

                    $sub = new UserSubscription;

                    $sub->user_id = $user->id;

                    $sub->subscription_id = $subs->id;

                    $sub->title = $subs->title;

                    $sub->currency = $subs->currency;

                    $sub->currency_code = $subs->currency_code;

                    $sub->price = $subs->price;

                    $sub->days = $subs->days;

                    $sub->allowed_products = $subs->allowed_products;

                    $sub->details = $subs->details;

                    $sub->method = 'Free';

                    $sub->status = 1;

                    $sub->storename = $storename;

                    $sub->save();

                    if($settings->is_smtp == 1)

                    {

                    $data = [

                        'to' => $user->email,

                        'type' => "vendor_accept",

                        'cname' => $user->name,

                        'oamount' => "",

                        'aname' => "",

                        'aemail' => "",

                        'onumber' => "",

                    ];    

                    $mailer = new ShopypallMailer();

                    $mailer->sendAutoMail($data);        

                    }

                    else

                    {

                    $headers = "From: ".$settings->from_name."<".$settings->from_email.">";

                    mail($user->email,'Your Vendor Account Activated','Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.',$headers);

                    }



                    return redirect()->route('user-dashboard',$storename)->with('success','Vendor Account Activated Successfully');



    }





    public function favorite($storename,$id1,$id2)

    {

        $fav = new FavoriteSeller();

        $fav->user_id = $id1;

        $fav->vendor_id = $id2;

        $fav->storename = $storename;

        $fav->save();

    }



    public function favorites($storename)

    {

        $user = Auth::guard('web')->user();

        $favorites = FavoriteSeller::where('user_id','=',$user->id)->get();

        return view('user.favorite',compact('user','favorites','storename'));

    }





    public function favdelete($storename,$id)

    {

        $wish = FavoriteSeller::findOrFail($id);

        $wish->delete();

        return redirect()->route('user-favorites',$storename)->with('success','Successfully Removed The Seller.');

    }





}

