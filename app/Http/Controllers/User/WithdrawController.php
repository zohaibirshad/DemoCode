<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Input;
use Validator;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

  	public function index($storename)
    {
        $withdraws = Withdraw::where('storename',$storename)->where('user_id','=',Auth::guard('web')->user()->id)->where('type','=','user')->orderBy('id','desc')->get();
        $sign = Currency::where('storename',$storename)->where('is_default','=',1)->first();        
        return view('user.withdraw.index',compact('withdraws','sign','storename'));
    }

    public function affilate_code($storename)
    {
        $user = Auth::guard('web')->user();
        return view('user.withdraw.affilate_code',compact('user','storename'));
    }


    public function create($storename)
    {
        $sign = Currency::where('storename',$storename)->where('is_default','=',1)->first();
        return view('user.withdraw.withdraw' ,compact('sign','storename'));
    }


    public function store(Request $request,$storename)
    {

        $from = User::where('storename',$storename)->findOrFail(Auth::guard('web')->user()->id);
        $curr = Currency::where('storename',$storename)->where('is_default','=',1)->first(); 
        $withdrawcharge = Generalsetting::where('storename',$storename)->first();
        $charge = $withdrawcharge->withdraw_fee;

        if($request->amount > 0){

            $amount = $request->amount;
            $amount = round(($amount / $curr->value),2);
            
            if ($from->affilate_income >= $amount){
                $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                $finalamount = $amount - $fee;
                if ($from->affilate_income >= $finalamount){
                $finalamount = number_format((float)$finalamount,2,'.','');

                $from->affilate_income = $from->affilate_income - $amount;
                $from->update();

                $newwithdraw = new Withdraw();
                $newwithdraw['user_id'] = Auth::guard('web')->user()->id;
                $newwithdraw['method'] = $request->methods;
                $newwithdraw['acc_email'] = $request->acc_email;
                $newwithdraw['iban'] = $request->iban;
                $newwithdraw['country'] = $request->acc_country;
                $newwithdraw['acc_name'] = $request->acc_name;
                $newwithdraw['address'] = $request->address;
                $newwithdraw['swift'] = $request->swift;
                $newwithdraw['reference'] = $request->reference;
                $newwithdraw['amount'] = $finalamount;
                $newwithdraw['fee'] = $fee;
                $newwithdraw['type'] = 'user';
                $newwithdraw['storename'] = $storename;
                $newwithdraw->save();

                // return response()->json('Withdraw Request Sent Successfully.'); 
                \Session::put('success','Withdraw Request Sent Successfully.');
                return redirect()->back();
            }else{
                \Session::put('error','Insufficient Balance.');
                return redirect()->back();
                // return response()->json(array('errors' => [ 0 => 'Insufficient Balance.' ])); 

            }
            }else{
                
                \Session::put('error','Insufficient Balance.');
                return redirect()->back();
                // return response()->json(array('errors' => [ 0 => 'Insufficient Balance.' ])); 

            }
        }   \Session::put('error','Please enter a valid amount.');
                return redirect()->back();
            // return response()->json(array('errors' => [ 0 => 'Please enter a valid amount.' ])); 

    }
}