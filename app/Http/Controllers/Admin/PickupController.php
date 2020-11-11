<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Pickup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class PickupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Pickup::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(Pickup $data) use ($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-pick-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-pick-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->toJson();//--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.pickup.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.pickup.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
            'location' => 'required|unique:pickups',
        ]);
        //--- Validation Section
        // $rules = [
        //        'location' => 'unique:pickups',
        //         ];
        // $customs = [
        //        'location.unique' => 'This location has already been taken.',
        //            ];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Pickup;
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
        $data = Pickup::findOrFail($id);
        return view('admin.pickup.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request,[
            'location' => 'required|unique:pickups,location,'.$id
        ]);
        //--- Validation Section
        // $rules = [
        //        'location' => 'unique:pickups,location,'.$id
        //         ];
        // $customs = [
        //        'location.unique' => 'This location has already been taken.',
        //            ];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Pickup::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section          
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends  

    }

    //*** GET Request
    public function destroy($storename,$id)
    {
        $data = Pickup::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends   
    }
}
