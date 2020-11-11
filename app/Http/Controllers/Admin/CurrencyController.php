<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class CurrencyController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Currency::where('storename',$storename)->orderBy('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(Currency $data) use($storename) {
                                $delete = $data->sign == '$' ? '':'<a href="javascript:;" data-href="' . route('admin-currency-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                $default = $data->is_default == 1 ? '<a><i class="fa fa-check"></i> Default</a>' : '<a class="status" data-href="' . route('admin-currency-status',[$storename,'id1'=>$data->id,'id2'=>1]) . '">Set Default</a>';
                                return '<div class="action-list"><a data-href="' . route('admin-currency-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>'.$delete.$default.'</div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.currency.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.currency.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
            'name' => 'required|unique:currencies',
            'sign' => 'required|unique:currencies',
        ]);
        //--- Validation Section
        // $rules = ['name' => 'unique:currencies','sign' => 'unique:currencies'];
        // $customs = ['name.unique' => 'This name has already been taken.','sign.unique' => 'This sign has already been taken.'];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Currency();
        $input = $request->all();
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
        $data = Currency::findOrFail($id);
        return view('admin.currency.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request,[
            'name' => 'required|unique:currencies,name,'.$id,
            'sign' => 'required|unique:currencies,sign,'.$id,
        ]);
        //--- Validation Section
        // $rules = ['name' => 'unique:currencies,name,'.$id,'sign' => 'unique:currencies,sign,'.$id];
        // $customs = ['name.unique' => 'This name has already been taken.','sign.unique' => 'This sign has already been taken.'];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }                
        //--- Validation Section Ends

        //--- Logic Section
        $data = Currency::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
        return redirect()->back();  
        //--- Redirect Section Ends            
    }

      public function status($storename,$id1,$id2)
        {
            $data = Currency::findOrFail($id1);
            $data->is_default = $id2;
            $data->update();
            $data = Currency::where('id','!=',$id1)->update(['is_default' => 0]);
            //--- Redirect Section     
            $msg = 'Data Updated Successfully.';
            Session::put('success',$msg);
            return redirect()->back();       
            //--- Redirect Section Ends  
        }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        if($id == 1)
        {
        return "You cant't remove the main currency.";
        }
        $data = Currency::findOrFail($id);
        if($data->is_default == 1) {
        Currency::where('id','=',1)->update(['is_default' => 1]);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
        return redirect()->back();     
        //--- Redirect Section Ends     
    }

}