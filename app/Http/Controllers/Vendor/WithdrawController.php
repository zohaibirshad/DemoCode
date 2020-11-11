<?php

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\Generalsetting;
use Auth;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  	public function index($storename)
    {
        $withdraws = Withdraw::where('user_id','=',Auth::guard('web')->user()->id)->where('type','=','vendor')->orderBy('id','desc')->get();
        $sign = Currency::where('is_default','=',1)->first();        
        return view('vendor.withdraw.index',compact('withdraws','sign','storename'));
    }


    public function create($storename)
    {
        $sign = Currency::where('is_default','=',1)->first();
        return view('vendor.withdraw.create' ,compact('sign','storename'));
    }


    public function store(Request $request,$storename)
    {

        $from = User::findOrFail(Auth::guard('web')->user()->id);
        $curr = Currency::where('is_default','=',1)->first(); 
        $withdrawcharge = Generalsetting::where('storename',$storename)->first();
        $charge = $withdrawcharge->withdraw_fee;

        if($request->amount > 0){

            $amount = $request->amount;
            $amount = round(($amount / $curr->value),2);
            if ($from->current_balance >= $amount){
                $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                $finalamount = $amount - $fee;
                $finalamount = number_format((float)$finalamount,2,'.','');

                $from->current_balance = $from->current_balance - $amount;
                $from->update();

                $newwithdraw = new Withdraw();
                $newwithdraw['user_id'] = Auth::user()->id;
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
                $newwithdraw['type'] = 'vendor';
                $newwithdraw->save();

                \Session::put('success','Withdraw Request Sent Successfully.');
                return redirect()->back();
                // return response()->json('Withdraw Request Sent Successfully.'); 

            }else{
                \Session::put('error','Insufficient Balance.');
                return redirect()->back();
                 // return response()->json(array('errors' => [ 0 => 'Insufficient Balance.' ])); 
            }
        }   
                \Session::put('error','Please enter a valid amount.');
                return redirect()->back();
            // return response()->json(array('errors' => [ 0 => 'Please enter a valid amount.' ])); 

    }
}
