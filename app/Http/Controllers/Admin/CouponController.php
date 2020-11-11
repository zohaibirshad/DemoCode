<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class CouponController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Coupon::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('type', function(Coupon $data) use ($storename){
                                $type = $data->type == 0 ? "Discount By Percentage" : "Discount By Amount";
                                return $type;
                            })
                            ->editColumn('price', function(Coupon $data) use ($storename){
                                $price = $data->type == 0 ? $data->price.'%' : $data->price.'$';
                                return $price;
                            })
                            ->addColumn('status', function(Coupon $data) use ($storename){
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-coupon-status',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><<option data-val="0" value="'. route('admin-coupon-status',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
                            }) 
                            ->addColumn('action', function(Coupon $data) use ($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-coupon-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-coupon-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.coupon.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.coupon.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section
        $rules = ['code' => 'unique:coupons'];
        $customs = ['code.unique' => 'This code has already been taken.'];
        $validator = Validator::make(Input::all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }   
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Coupon();
        $input = $request->all();
        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
        $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'New Data Added Successfully.';
        Session::put('success',$msg);
        return redirect()->back();    
        
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $data = Coupon::findOrFail($id);
        return view('admin.coupon.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section

        $this->validate($request,[
            'code'       => 'required',
            'type' => 'required',
            'quantity' => 'required',
            'price' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            ]);

        $rules = ['code' => 'unique:coupons,code,'.$id];
        $customs = ['code.unique' => 'This code has already been taken.'];
        $validator = Validator::make(Input::all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Coupon::findOrFail($id);
        $input = $request->all();
        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
        return redirect()->back();  
        //--- Redirect Section Ends           
    }
      //*** GET Request Status
      public function status($storename,$id1,$id2)
        {
            $data = Coupon::findOrFail($id1);
            $data->status = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        $data = Coupon::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
        return redirect()->back();      
        //--- Redirect Section Ends   
    }
}
