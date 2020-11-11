<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentGateway;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orders($storename)
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('storename',$storename)->where('user_id','=',$user->id)->orderBy('id','desc')->get();
        return view('user.order.index',compact('user','orders','storename'));
    }

    public function ordertrack($storename)
    {
        $user = Auth::guard('web')->user();
        return view('user.order-track',compact('user','storename'));
    }

    public function trackload($storename,$id)
    {
        $order = Order::where('storename',$storename)->where('order_number','=',$id)->first();
        $datas = array('Pending','Processing','On Delivery','Completed');
        return view('load.track-load',compact('order','datas','storename'));

    }


    public function order($storename,$id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('storename',$storename)->findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('user.order.details',compact('user','order','cart','storename'));
    }

    public function orderdownload($storename,$slug,$id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('storename',$storename)->where('order_number','=',$slug)->first();
        $prod = Product::where('storename',$storename)->findOrFail($id);
        if(!isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id)
        {
            return redirect()->back();
        }
        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($storename,$id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('storename',$storename)->findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('user.order.print',compact('user','order','cart','storename'));
    }

    public function trans($storename)
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;
        return response()->json($data);            
    }  

}
