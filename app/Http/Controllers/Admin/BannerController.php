<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename,$type)
    {
         $datas = Banner::where('storename',$storename)->where('type','=',$type)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Banner $data) use ($storename){
                                $photo = $data->photo ? url('assets/images/banners/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->addColumn('action', function(Banner $data) use ($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-sb-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-sb-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.banner.index',compact('storename'));
    }

    //*** GET Request
    public function large($storename)
    {
        return view('admin.banner.large',compact('storename'));
    }

    //*** GET Request
    public function bottom($storename)
    {
        return view('admin.banner.bottom',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.banner.create',compact('storename'));
    }

    //*** GET Request
    public function largecreate($storename)
    {
        return view('admin.banner.largecreate',compact('storename'));
    }

    //*** GET Request
    public function bottomcreate($storename)
    {
        return view('admin.banner.bottomcreate',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section
        $this->validate($request,[
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ]);

        $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Banner();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/banners',$name);           
            $input['photo'] = $name;
        }
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
        $data = Banner::findOrFail($id);
        return view('admin.banner.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request, $storename,$id)
    {
        //--- Validation Section
        $this->validate($request,[
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ]);
        
        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Banner::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/banners',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/banners/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/banners/'.$data->photo);
                    }
                }            
            $input['photo'] = $name;
            } 
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
        return redirect()->back();      
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        $data = Banner::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section     
            $msg = 'Data Deleted Successfully.';
            Session::put('success',$msg);
            return redirect()->back();      
            //--- Redirect Section Ends     
        }
        //If Photo Exist
        if (file_exists(public_path().'/assets/images/banners/'.$data->photo)) {
            // unlink(public_path().'/assets/images/banners/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
        return redirect()->back();      
        //--- Redirect Section Ends     
    }
}
