<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Subscription::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('price', function(Subscription $data) use($storename) {
                                $price = round($data->price,2);
                                return $price;
                            })
                            ->editColumn('allowed_products', function(Subscription $data) use($storename) {
                                $allowed_products = $data->allowed_products == 0 ? "Unlimited": $data->allowed_products;
                                return $allowed_products;
                            })
                            ->addColumn('action', function(Subscription $data) use($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-subscription-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-subscription-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.subscription.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.subscription.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {

        //--- Logic Section
        $data = new Subscription();
        $input = $request->all();

        if($input['limit'] == 0)
         {
            $input['allowed_products'] = 0;
         }

        $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $data = Subscription::findOrFail($id);
        return view('admin.subscription.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {

        $this->validate($request,[
            'title' => 'required',
            'currency' => 'required',
            'currency_code' => 'required',
            'price' => 'required',
            'days' => 'required',
            'limit' => 'required',
            'details' => 'required',
        ]);

        //--- Logic Section
        $data = Subscription::findOrFail($id);
        $input = $request->all();
        if($input['limit'] == 0)
         {
            $input['allowed_products'] = 0;
         }
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        $data = Subscription::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends     
    }
}
