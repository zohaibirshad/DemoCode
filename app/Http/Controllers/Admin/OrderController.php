<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ShopypallMailer;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\User;
use App\Models\VendorOrder;
use Datatables;
use Illuminate\Http\Request;
use Session;
use Auth;
use Route;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($status,$storename)
    {
        // dd($storename);
       
        if($status == 'pending'){
            $datas = Order::where('status','=','pending')->where('storename','=',$storename)->get();
        }
        elseif($status == 'processing') {
            $datas = Order::where('status','=','processing')->where('storename','=',$storename)->get();
        }
        elseif($status == 'completed') {
            $datas = Order::where('status','=','completed')->where('storename','=',$storename)->get();
        }
        elseif($status == 'declined') {
            $datas = Order::where('status','=','declined')->where('storename','=',$storename)->get();
        }
        else{
          $datas = Order::where('storename','=',$storename)->orderBy('id','desc')->get();  
        }
         
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('id', function(Order $data) use ($storename) {
                                $id = '<a href="'.route('admin-order-invoice',[$storename,$data->id]).'">'.$data->order_number.'</a>';
                                return $id;
                            })
                            ->editColumn('pay_amount', function(Order $data) use ($storename) {
                                return $data->currency_sign . round($data->pay_amount * $data->currency_value , 2);
                            })
                            ->addColumn('action', function(Order $data) use ($storename) {
                                $orders = '<a href="javascript:;" data-href="'. route('admin-order-edit',[$storename,$data->id]) .'" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show',[$storename,$data->id]) . '" > <i class="fas fa-eye"></i> Details</a><a href="javascript:;" class="send" data-email="'. $data->customer_email .'" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a><a href="javascript:;" data-href="'. route('admin-order-track',[$storename,$data->id]) .'" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> Track Order</a>'.$orders.'</div></div>';
                            }) 
                            ->rawColumns(['id','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index($storename)
    {
        return view('admin.order.index',compact('storename'));
    }

    public function edit($storename,$id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request, $storename,$id)
    {

        //--- Logic Section
        $data = Order::findOrFail($id);

        $input = $request->all();
        if ($data->status == "completed"){

        // Then Save Without Changing it.
            $input['status'] = "completed";
            $data->update($input);
            //--- Logic Section Ends
    

        //--- Redirect Section          
        $msg = 'Status Updated Successfully.';
        return response()->json($msg);    
        //--- Redirect Section Ends     
    
            }else{
            if ($input['status'] == "completed"){
    
                foreach($data->vendororders as $vorder)
                {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }
    
                $gs = Generalsetting::where('storename',$storename)->first();
                if($gs->is_smtp == 1)
                {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Confirmed!',
                        'body' => "Hello ".$data->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.",
                    ];
    
                    $mailer = new ShopypallMailer();
                    $mailer->sendCustomMail($maildata);                
                }
                else
                {
                   $to = $data->customer_email;
                   $subject = 'Your order '.$data->order_number.' is Confirmed!';
                   $msg = "Hello ".$data->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);                
                }
            }
            if ($input['status'] == "declined"){
                $gs = Generalsetting::where('storename',$storename)->first();
                if($gs->is_smtp == 1)
                {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Declined!',
                        'body' => "Hello ".$data->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                    ];
                $mailer = new ShopypallMailer();
                $mailer->sendCustomMail($maildata);
                }
                else
                {
                   $to = $data->customer_email;
                   $subject = 'Your order '.$data->order_number.' is Declined!';
                   $msg = "Hello ".$data->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.";
                   $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);
                }
    
            }

            $data->update($input);

            if($request->track_text)
            {
                    $title = ucwords($request->status);
                    $ck = OrderTrack::where('order_id','=',$id)->where('title','=',$title)->first();
                    if($ck){
                        $ck->order_id = $id;
                        $ck->title = $title;
                        $ck->text = $request->track_text;
                        $ck->update();  
                    }
                    else {
                        $data = new OrderTrack;
                        $data->order_id = $id;
                        $data->title = $title;
                        $data->text = $request->track_text;
                        $data->save();            
                    }
    
    
            } 


        $order = VendorOrder::where('order_id','=',$id)->update(['status' => $input['status']]);

         //--- Redirect Section 
         // return redirect()->back()->with('success','Status Updated Successfully.');  
         $msg = 'Status Updated Successfully.';
         Session::put('success',$msg);
            return redirect()->back();  
         //--- Redirect Section Ends    
    
            }

        //--- Redirect Section         
        // return redirect()->back()->with('success','Status Updated Successfully.'); 
        $msg = 'Status Updated Successfully.';
        Session::put('success',$msg);
            return redirect()->back();  
        //--- Redirect Section Ends  

    }

    public function pending($storename)
    {
        return view('admin.order.pending',compact('storename'));
    }
    public function processing($storename)
    {
        return view('admin.order.processing',compact('storename'));
    }
    public function completed($storename)
    {
        return view('admin.order.completed',compact('storename'));
    }
    public function declined($storename)
    {
        return view('admin.order.declined',compact('storename'));
    }
    public function show($storename,$id)
    {
        
        if(!Order::where('id',$id)->exists() || Order::where('id',$id)->first()->storename != $storename)
        {
            return redirect()->route('admin.dashboard',$storename)->with('unsuccess',__('Sorry the page does not exist.'));
        }
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('admin.order.details',compact('order','cart','storename'));
    }
    public function invoice($storename,$id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('admin.order.invoice',compact('order','cart','storename'));
    }
    
    public function emailsub(Request $request,$storename)
    {
        $gs = Generalsetting::where('storename',$storename)->first();
        if($gs->is_smtp == 1)
        {
            $data = 0;
            $datas = [
                    'to' => $request->to,
                    'subject' => $request->subject,
                    'body' => $request->message,
            ];

            $mailer = new ShopypallMailer();
            $mail = $mailer->sendCustomMail($datas);
            if($mail) {
                $data = 1;
            }
        }
        else
        {
            $data = 0;
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            $mail = mail($request->to,$request->subject,$request->message,$headers);
            if($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($storename,$id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('admin.order.print',compact('order','cart','storename'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(bzcompress(serialize($cart), 9));
        $order->update();       
        $msg = 'Successfully Changed The License Key.';
        Session::put('success',$msg);
            return redirect()->back();
    }
}