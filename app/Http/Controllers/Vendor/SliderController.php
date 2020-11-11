<?php

namespace App\Http\Controllers\Vendor;

use Datatables;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $user = Auth::user();
         $datas =  $user->sliders()->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Slider $data) use($storename) {
                                $photo = $data->photo ? url('assets/images/sliders/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->editColumn('title', function(Slider $data) use($storename) {
                                $title = mb_strlen(strip_tags($data->title),'utf-8') > 250 ? mb_substr(strip_tags($data->title),0,250,'utf-8').'...' : strip_tags($data->title);
                                return  $title;
                            })
                            ->addColumn('action', function(Slider $data) use($storename) {
                                return '<div class="action-list"><a href="' . route('vendor-sl-edit',[$storename,$data->id]) . '"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('vendor-sl-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('vendor.slider.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('vendor.slider.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        $this->validate($request,[
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ]);
        //--- Validation Section
        // $rules = [
               
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Slider();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/sliders',$name);           
            $input['photo'] = $name;
        } 

        $input['user_id'] = Auth::user()->id;    
        // Save Data 
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
        $data = Slider::findOrFail($id);
        return view('vendor.slider.edit',compact('data'));
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
        $data = Slider::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/sliders',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/sliders/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/sliders/'.$data->photo);
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
        $data = Slider::findOrFail($id);
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
        if (file_exists(public_path().'/assets/images/sliders/'.$data->photo)) {
            // unlink(public_path().'/assets/images/sliders/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends     
    }
}
