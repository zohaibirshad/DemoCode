<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Service::where('storename',$storename)->where('user_id','=',0)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Service $data) use($storename) {
                                $photo = $data->photo ? url('assets/images/services/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->editColumn('title', function(Service $data) use($storename) {
                                $title = mb_strlen(strip_tags($data->title),'utf-8') > 250 ? mb_substr(strip_tags($data->title),0,250,'utf-8').'...' : strip_tags($data->title);
                                return  $title;
                            })
                            ->editColumn('details', function(Service $data) use($storename) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(Service $data) use($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-service-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-service-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.service.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.service.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
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
        $data = new Service();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/services',$name);           
            $input['photo'] = $name;
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
        $data = Service::findOrFail($id);
        return view('admin.service.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request,[
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ]);
        //--- Validation Section
        // $rules = [
        //        'photo'      => 'mimes:jpeg,jpg,png,svg',
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Service::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/services',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/services/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/services/'.$data->photo);
                    }
                }            
            $input['photo'] = $name;
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
        $data = Service::findOrFail($id);
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
        if (file_exists(public_path().'/assets/images/services/'.$data->photo)) {
            // unlink(public_path().'/assets/images/services/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends     
    }
}