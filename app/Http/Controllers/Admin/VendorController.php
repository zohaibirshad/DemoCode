<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Classes\ShopypallMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\Withdraw;
use App\Models\Currency;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

	    //*** JSON Request
	    public function datatables($storename)
	    {
	        $datas = User::where('storename',$storename)->where('is_vendor','=',2)->orWhere('is_vendor','=',1)->orderBy('id','desc')->get();
	         //--- Integrating This Collection Into Datatables
	         return Datatables::of($datas)
                                ->addColumn('status', function(User $data) use ($storename){
                                    $class = $data->is_vendor == 2 ? 'drop-success' : 'drop-danger';
                                    $s = $data->is_vendor == 2 ? 'selected' : '';
                                    $ns = $data->is_vendor == 1 ? 'selected' : '';
                                    return '<div class="action-list"><select class="process select vendor-droplinks '.$class.'">'.
                '<option value="'. route('admin-vendor-st',[$storename,'id1' => $data->id, 'id2' => 2]).'" '.$s.'>Activated</option>'.
                '<option value="'. route('admin-vendor-st',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$ns.'>Deactivated</option></select></div>';
                                }) 
	                            ->addColumn('action', function(User $data) use ($storename) {
	                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-vendor-secret',[$storename,$data->id]) . '" > <i class="fas fa-user"></i> Secret Login</a><a href="javascript:;" data-href="' . route('admin-vendor-verify',[$storename,$data->id]) . '" class="verify" data-toggle="modal" data-target="#verify-modal"> <i class="fas fa-question"></i> Ask For Verification</a><a href="' . route('admin-vendor-show',[$storename,$data->id]) . '" > <i class="fas fa-eye"></i> Details</a><a data-href="' . route('admin-vendor-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> Edit</a><a href="javascript:;" class="send" data-email="'. $data->email .'" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send Email</a><a href="javascript:;" data-href="' . route('admin-vendor-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a></div></div>';
	                            }) 
	                            ->rawColumns(['status','action'])
	                            ->toJson(); //--- Returning Json Data To Client Side
	    }

	//*** GET Request
    public function index($storename)
    {
        return view('admin.vendor.index',compact('storename'));
    }

    //*** GET Request
    public function color($storename)
    {
        return view('admin.generalsetting.vendor_color',compact('storename'));
    }


    //*** GET Request
    public function subsdatatables($storename)
    {
         $datas = UserSubscription::where('storename',$storename)->where('status','=',1)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('name', function(UserSubscription $data) use ($storename) {
                                $name = isset($data->user->owner_name) ? $data->user->owner_name : 'Removed';
                                return  $name;
                            })

                            ->editColumn('txnid', function(UserSubscription $data) use ($storename) {
                                $txnid = $data->txnid == null ? 'Free' : $data->txnid;
                                return $txnid;
                            }) 
                            ->editColumn('created_at', function(UserSubscription $data) use ($storename) {
                                $date = $data->created_at->diffForHumans();
                                return $date;
                            }) 
                            ->addColumn('action', function(UserSubscription $data) use ($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-vendor-sub',[$storename,$data->id]) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side


    }


	//*** GET Request
    public function subs($storename)
    {

        return view('admin.vendor.subscriptions',compact('storename'));
    }

	//*** GET Request
    public function sub($storename,$id)
    {
        $subs = UserSubscription::findOrFail($id);
        return view('admin.vendor.subscription-details',compact('subs','storename'));
    }

	//*** GET Request
  	public function status($storename,$id1,$id2)
    {
        $user = User::findOrFail($id1);
        $user->is_vendor = $id2;
        $user->update();
        //--- Redirect Section        
        $msg = 'Status Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends    

    }

	//*** GET Request
    public function edit($storename,$id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.edit',compact('data','storename'));
    }



	//*** GET Request
    public function verify($storename,$id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.verification',compact('data','storename'));
    }

	//*** POST Request
    public function verifySubmit(Request $request,$storename,$id)
    {
        // $settings = Generalsetting::find(1);
        $settings = Generalsetting::where('storename',$storename)->first();
        $user = User::findOrFail($id);
        $user->verifies()->create(['admin_warning' => 1, 'warning_reason' => $request->details,'storename' => $storename]);

                    if($settings->is_smtp == 1)
                    {
                    $data = [
                        'to' => $user->email,
                        'type' => "vendor_verification",
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
                    mail($user->email,'Request for verification.','You are requested verify your account. Please send us photo of your passport.Thank You.',$headers);
                    }

        $msg = 'Verification Request Sent Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();   
    }


	//*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        
        $this->validate($request,[
            'shop_name' => 'required',
            'shop_details' => 'required',
            'owner_name' => 'required',
            'shop_number' => 'required',
            'shop_address' => 'required',

        ]);


	        $rules = [
                'shop_name'   => 'unique:users,shop_name,'.$id,
                 ];
                 
            $customs = [
                'shop_name.unique' => 'Shop Name "'.$request->shop_name.'" has already been taken. Please choose another name.'
            ];

         $validator = Validator::make(Input::all(), $rules,$customs);
         
         if ($validator->fails()) 
         {
           return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         //--- Validation Section Ends

        $user = User::findOrFail($id);
        $data = $request->all();
        $user->update($data);
        $msg = 'Vendor Information Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();   
    }

	//*** GET Request
    public function show($storename,$id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.show',compact('data','storename'));
    }
    

    //*** GET Request
    public function secret($storename,$id)
    {
        Auth::guard('web')->logout();
        $data = User::findOrFail($id);
        Auth::guard('web')->login($data); 
        return redirect()->route('vendor-dashboard',$storename);
    }
    

	//*** GET Request
    public function destroy($storename,$id)
    {
        $user = User::findOrFail($id);
        $user->is_vendor = 0;
            $user->is_vendor = 0;
            $user->shop_name = null;
            $user->shop_details= null;
            $user->owner_name = null;
            $user->shop_number = null;
            $user->shop_address = null;
            $user->reg_number = null;
            $user->shop_message = null;
        $user->update();
        if($user->notivications->count() > 0)
        {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }
            //--- Redirect Section     
            $msg = 'Vendor Deleted Successfully.';
            \Session::put('success',$msg);
            return redirect()->back();      
            //--- Redirect Section Ends    
    }

        //*** JSON Request
        public function withdrawdatatables($storename)
        {
             $datas = Withdraw::where('storename',$storename)->where('type','=','vendor')->orderBy('id','desc')->get();
             //--- Integrating This Collection Into Datatables
             return Datatables::of($datas)
                                ->addColumn('name', function(Withdraw $data) use ($storename) {
                                    $name = $data->user->name;
                                    return '<a href="' . route('admin-vendor-show',[$storename,$data->user->id]) . '" target="_blank">'. $name .'</a>';
                                }) 
                                ->addColumn('email', function(Withdraw $data) use ($storename) {
                                    $email = $data->user->email;
                                    return $email;
                                }) 
                                ->addColumn('phone', function(Withdraw $data) use ($storename) {
                                    $phone = $data->user->phone;
                                    return $phone;
                                }) 
                                ->editColumn('status', function(Withdraw $data) use ($storename) {
                                    $status = ucfirst($data->status);
                                    return $status;
                                }) 
                                ->editColumn('amount', function(Withdraw $data) use ($storename) {
                                    $sign = Currency::where('is_default','=',1)->first();
                                    $amount = $sign->sign.round($data->amount * $sign->value , 2);
                                    return $amount;
                                }) 
                                ->addColumn('action', function(Withdraw $data) use ($storename) {
                                    $action = '<div class="action-list"><a data-href="' . route('admin-vendor-withdraw-show',[$storename,$data->id]) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> Details</a>';
                                    if($data->status == "pending") {
                                    $action .= '<a data-href="' . route('admin-vendor-withdraw-accept',[$storename,$data->id] ) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="fas fa-check"></i> Accept</a><a data-href="' . route('admin-vendor-withdraw-reject',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete1"> <i class="fas fa-trash-alt"></i> Reject</a>';
                                    }
                                    $action .= '</div>';
                                    return $action;
                                }) 
                                ->rawColumns(['name','action'])
                                ->toJson(); //--- Returning Json Data To Client Side
        }

        //*** GET Request
        public function withdraws($storename)
        {
            return view('admin.vendor.withdraws',compact('storename'));
        }

        //*** GET Request       
        public function withdrawdetails($storename,$id)
        {
            $sign = Currency::where('is_default','=',1)->first();
            $withdraw = Withdraw::findOrFail($id);
            return view('admin.vendor.withdraw-details',compact('withdraw','sign','storename'));
        }

        //*** GET Request   
        public function accept($storename,$id)
        {
            $withdraw = Withdraw::findOrFail($id);
            $data['status'] = "completed";
            $withdraw->update($data);
            //--- Redirect Section     
            $msg = 'Withdraw Accepted Successfully.';
            \Session::put('success',$msg);
            return redirect()->back();      
            //--- Redirect Section Ends   
        }

        //*** GET Request   
        public function reject($storename,$id)
        {
            $withdraw = Withdraw::findOrFail($id);
            $account = User::findOrFail($withdraw->user->id);
            $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
            $account->update();
            $data['status'] = "rejected";
            $withdraw->update($data);
            //--- Redirect Section     
            $msg = 'Withdraw Rejected Successfully.';
            \Session::put('success',$msg);
            return redirect()->back();      
            //--- Redirect Section Ends   
        }

}
