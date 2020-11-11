<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Admin;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Admin::where('storename',$storename)->where('role_id','!=',0)->where('id','!=',Auth::guard('admin')->user()->id)->orderBy('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('role', function(Admin $data) use ($storename){
                                $role = $data->role_id == 0 ? 'No Role' : $data->role->name;
                                return $role;
                            }) 
                            ->addColumn('action', function(Admin $data) use ($storename){
                                $delete ='<a href="javascript:;" data-href="' . route('admin-staff-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                return '<div class="action-list"><a data-href="' . route('admin-staff-show',[$storename,$data->id]) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a><a data-href="' . route('admin-staff-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>'.$delete.'</div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
  	public function index($storename)
    {
        return view('admin.staff.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.staff.create',compact('storename'));
    }

    //*** POST Request
    
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
            'name' => 'required',
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ]);
        //--- Validation Section
        // $rules = [
        //        'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Admin();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admins',$name);           
            $input['photo'] = $name;
        } 
        $input['role'] = 'Staff';
        $input['password'] = bcrypt($request['password']);
        $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends    
    }


    public function edit($storename,$id)
    {
        $data = Admin::findOrFail($id);  
        return view('admin.staff.edit',compact('data','storename'));
    }

    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        if($id != Auth::guard('admin')->user()->id)
        {
            $this->validate($request,[
                'name' => 'required',
                'photo' => 'mimes:jpeg,jpg,png,svg',
                'email' => 'unique:admins,email,'.$id
            ]);
            // $rules =
            // [
            //     'photo' => 'mimes:jpeg,jpg,png,svg',
            //     'email' => 'unique:admins,email,'.$id
            // ];

            // $validator = Validator::make(Input::all(), $rules);
            
            // if ($validator->fails()) {
            // return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            // }
            //--- Validation Section Ends
            $input = $request->all();  
            $data = Admin::findOrFail($id);        
                if ($file = $request->file('photo')) 
                {              
                    $name = time().$file->getClientOriginalName();
                    $file->move('assets/images/admins/',$name);
                    if($data->photo != null)
                    {
                        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
                            // unlink(public_path().'/assets/images/admins/'.$data->photo);
                        }
                    }            
                $input['photo'] = $name;
                } 
            if($request->password == ''){
                $input['password'] = $data->password;
            }
            else{
                $input['password'] = Hash::make($request->password);
            }
            $data->update($input);
            $msg = 'Data Updated Successfully.';
            \Session::put('success',$msg);
            return redirect()->back();
        }
        else{
            $msg = 'You can not change your role.';
            \Session::put('success',$msg);
            return redirect()->back();          
        }
 
    }

    //*** GET Request
    public function show($storename,$id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.staff.show',compact('data'));
    }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
    	if($id == 1)
    	{
            \Session::put('error',"You don't have access to remove this admin");
            return redirect()->back();
        // return "You don't have access to remove this admin";
    	}
        $data = Admin::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section     
            $msg = 'Data Deleted Successfully.';
            \Session::put('success',$msg);
            return redirect()->back();     
            //--- Redirect Section Ends     
        }
        //If Photo Exist
        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
            // unlink(public_path().'/assets/images/admins/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends    
    }
}
