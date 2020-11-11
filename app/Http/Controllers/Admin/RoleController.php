<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Role::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('section', function(Role $data) use($storename){
                                $details =  str_replace('_',' ',$data->section);
                                $details =  ucwords($details);
                                return  '<div>'.$details.'</div>';
                            })
                            ->addColumn('action', function(Role $data) use($storename){
                                return '<div class="action-list"><a href="' . route('admin-role-edit',[$storename,$data->id]) . '"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-role-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['section','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.role.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.role.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section
        // $rules = [
        //        'photo'      => '',
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Role();
        $input = $request->all();
        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }
        $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends
      
        //--- Redirect Section
        $msg = 'New Data Added Successfully.<a href="'.route('admin-role-index',$storename).'">View Role Lists.</a>';
        \Session::put('success',$msg);
            return redirect()->back();
        //--- Redirect Section Ends    


    }

    //*** GET Request
    public function edit($storename,$id)
    {
        if(!Role::where('id',$id)->exists() || Role::where('id',$id)->first()->storename != $storename)
        {
            return redirect()->route('admin.dashboard',$storename)->with('unsuccess',__('Sorry the page does not exist.'));
        }

        $data = Role::findOrFail($id);
        return view('admin.role.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        // $rules = [
        //        'photo'      => '',
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Role::findOrFail($id);
        $input = $request->all();
        if(!empty($request->section))
        {
            $input['section'] = implode(" , ",$request->section);
        }
        else{
            $input['section'] = '';
        }
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.<a href="'.route('admin-role-index',$storename).'">View Role Lists.</a>';
        \Session::put('success',$msg);
            return redirect()->back();
        //--- Redirect Section Ends    

    }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends     
    }
}
