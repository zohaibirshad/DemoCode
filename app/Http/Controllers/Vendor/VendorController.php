<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Generalsetting;
use App\Models\Subcategory;
use App\Models\VendorOrder;
use App\Models\Verification;
use Auth;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use Validator;

class VendorController extends Controller
{

    public $lang;
    public function __construct()
    {

        $this->middleware('auth');

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->lang = json_decode($data_results);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->lang = json_decode($data_results);
                
            } 
    }

    //*** GET Request
    public function index($storename)
    {
        $user = Auth::user();  
        $pending = VendorOrder::where('user_id','=',$user->id)->where('status','=','pending')->get(); 
        $processing = VendorOrder::where('user_id','=',$user->id)->where('status','=','processing')->get(); 
        $completed = VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->get(); 
        return view('vendor.index',compact('user','pending','processing','completed','storename'));
    }

    public function profileupdate(Request $request,$storename)
    {
        $this->validate($request,[
            'shop_image'  => 'mimes:jpeg,jpg,png,svg',
        ]);
        //--- Validation Section
        // $rules = [
        //        'shop_image'  => 'mimes:jpeg,jpg,png,svg',
        //         ];

        // $validator = Validator::make(Input::all(), $rules);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        $input = $request->all();  
        $data = Auth::user();    

        if ($file = $request->file('shop_image')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/vendorbanner',$name);           
            $input['shop_image'] = $name;
        }

        $data->update($input);
        $msg = 'Successfully updated your profile';
        \Session::put('success',$msg);
            return redirect()->back(); 
    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request,$storename)
    {
        //--- Logic Section
        $input = $request->all(); 
        $data = Auth::user();   
        if ($request->f_check == ""){
            $input['f_check'] = 0;
        }
        if ($request->t_check == ""){
            $input['t_check'] = 0;
        }

        if ($request->g_check == ""){
            $input['g_check'] = 0;
        }

        if ($request->l_check == ""){
            $input['l_check'] = 0;
        }
        $data->update($input);
        //--- Logic Section Ends
        //--- Redirect Section        
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends                

    }

    //*** GET Request
    public function profile($storename)
    {
        $data = Auth::user();  
        return view('vendor.profile',compact('data','storename'));
    }

    //*** GET Request
    public function ship($storename)
    {
        $gs = Generalsetting::where('storename',$storename)->first();
        if($gs->vendor_ship_info == 0) {
            return redirect()->back();
        }
        $data = Auth::user();  
        return view('vendor.ship',compact('data','storename'));
    }

    //*** GET Request
    public function banner($storename)
    {
        $data = Auth::user();  
        return view('vendor.banner',compact('data','storename'));
    }

    //*** GET Request
    public function social($storename)
    {
        $data = Auth::user();  
        return view('vendor.social',compact('data','storename'));
    }

    //*** GET Request
    public function subcatload($storename,$id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat','storename'));
    }

    //*** GET Request
    public function childcatload($storename,$id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat','storename'));
    }

    //*** GET Request
    public function verify($storename)
    {
        $data = Auth::user();  
        if($data->checkStatus())
        {
            return redirect()->back();
        }
        return view('vendor.verify',compact('data','storename'));
    }

    //*** GET Request
    public function warningVerify($storename,$id)
    {
        $verify = Verification::findOrFail($id);
        $data = Auth::user();  
        return view('vendor.verify',compact('data','verify','storename'));
    }

    //*** POST Request
    public function verifysubmit(Request $request,$storename)
    {
        $this->validate($request,[
            'attachments.*'  => 'mimes:jpeg,jpg,png,svg|max:10000'
        ]);
        //--- Validation Section
        // $rules = [
        //   'attachments.*'  => 'mimes:jpeg,jpg,png,svg|max:10000'
        //    ];
        // $customs = [
        //     'attachments.*.mimes' => 'Only jpeg, jpg, png and svg images are allowed',
        //     'attachments.*.max' => 'Sorry! Maximum allowed size for an image is 10MB',
        //            ];

        // $validator = Validator::make(Input::all(), $rules,$customs);
        
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        $data = new Verification();
        $input = $request->all();

        $input['attachments'] = '';
        $i = 0;
                if ($files = $request->file('attachments')){
                    foreach ($files as  $key => $file){
                        $name = time().$file->getClientOriginalName();
                        if($i == count($files) - 1){
                            $input['attachments'] .= $name;
                        }
                        else {
                            $input['attachments'] .= $name.',';
                        }
                        $file->move('assets/images/attachments',$name);

                    $i++;
                    }
                }
        $input['status'] = 'Pending';        
        $input['user_id'] = Auth::user()->id;
        if($request->verify_id != '0')
        {
            $verify = Verification::findOrFail($request->verify_id);
            $input['admin_warning'] = 0;
            $verify->update($input);
        }
        else{

            $data->fill($input)->save();
        }

        //--- Redirect Section        
        $msg = '<div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br><h3>'.$this->lang->lang804.'</h3></div>';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends     
    }

}
