<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Auth;
use Datatables;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use Validator;

class ServiceController extends Controller
{
    public $global_language;

    public function __construct()
    {
        $this->middleware('auth');

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
                
            } 

    }
    //*** JSON Request
    public function datatables($storename)
    {
         $user = Auth::user();
         $datas =  $user->services()->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Service $data) use ($storename){
                                $photo = $data->photo ? url('assets/images/services/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->editColumn('title', function(Service $data) use ($storename){
                                $title = mb_strlen(strip_tags($data->title),'utf-8') > 250 ? mb_substr(strip_tags($data->title),0,250,'utf-8').'...' : strip_tags($data->title);
                                return  $title;
                            })
                            ->addColumn('action', function(Service $data) use ($storename){
                                return '<div class="action-list"><a data-href="' . route('vendor-service-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.$this->vendor_language->lang717.'</a><a href="javascript:;" data-href="' . route('vendor-service-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('vendor.service.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('vendor.service.create','storename');
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
        $input['user_id'] = Auth::user()->id;    
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
        return view('vendor.service.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        $this->validate($request,[
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ]);
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
