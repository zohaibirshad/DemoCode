<?php

namespace App\Http\Controllers\Admin;

use App\Models\Socialsetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class SocialSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request,$storename)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $input = $request->all(); 
        $data = Socialsetting::findOrFail(1);   
        $data->update($input);
        //--- Logic Section Ends
        
        //--- Redirect Section        
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends               

    }


    // Spcial Settings All post requests will be done in this method
    public function socialupdateall(Request $request,$storename)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $input = $request->all(); 
        $data = Socialsetting::findOrFail(1);   
        if ($request->f_status == ""){
            $input['f_status'] = 0;
        }
        if ($request->t_status == ""){
            $input['t_status'] = 0;
        }
        if ($request->g_status == ""){
            $input['g_status'] = 0;
        }
        if ($request->l_status == ""){
            $input['l_status'] = 0;
        }
        if ($request->d_status == ""){
            $input['d_status'] = 0;
        }
        $data->update($input);
        //--- Logic Section Ends
        
        //--- Redirect Section        
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends               

    }


    public function index($storename)
    {
    	$data = Socialsetting::where('storename',$storename)->first();
        return view('admin.socialsetting.index',compact('data','storename'));
    }

    public function facebook($storename)
    {
    	$data = Socialsetting::where('storename',$storename)->first();
        return view('admin.socialsetting.facebook',compact('data','storename'));
    }

    public function google($storename)
    {
    	$data = Socialsetting::where('storename',$storename)->first();
        return view('admin.socialsetting.google',compact('data','storename'));
    }


    public function facebookup($storename,$status)
    {
        $data = Socialsetting::where('storename',$storename)->first();
        $data->f_check = $status;
        $data->update();
    }

    public function googleup($storename,$status)
    {
        
        $data = Socialsetting::where('storename',$storename)->first();
        $data->g_check = $status;
        $data->update();
    }

}
