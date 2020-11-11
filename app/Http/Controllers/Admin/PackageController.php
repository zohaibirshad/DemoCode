<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Package;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Package::where('storename',$storename)->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('price', function(Package $data) use ($storename){
                                $sign = Currency::where('is_default','=',1)->first();
                                $price = $sign->sign.$data->price;
                                return  $price;
                            })
                            ->addColumn('action', function(Package $data) use ($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-package-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-package-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.package.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        $sign = Currency::where('storename',$storename)->where('is_default','=',1)->first();
        return view('admin.package.create',compact('sign','storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
            'title' => 'unique:packages'
        ]);
        //--- Validation Section
        // $rules = ['title' => 'unique:packages'];
        // $customs = ['title.unique' => 'This title has already been taken.'];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Package();
        $input = $request->all();
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
        $sign = Currency::where('is_default','=',1)->first();
        $data = Package::findOrFail($id);
        return view('admin.package.edit',compact('data','sign','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request,[
            'title' => 'unique:packages,title,'.$id
        ]);
        // //--- Validation Section
        // $rules = [];
        // $customs = ['title.unique' => 'This title has already been taken.'];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Package::findOrFail($id);
        $input = $request->all();
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
        $data = Package::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends     
    }
}
