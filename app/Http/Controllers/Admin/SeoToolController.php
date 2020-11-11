<?php

namespace App\Http\Controllers\Admin;


use App\Models\Seotool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductClick;

class SeoToolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function analytics($storename)
    {
        $tool = Seotool::where('storename',$storename)->first();
        if(!$tool)
        {
            $tool = new Seotool;
            $tool->storename = $storename;
            $tool->google_analytics = '<script>//Google Analytics Script</script>';
            $tool->meta_keys = 'shopypall, shopypall store,multivendor';
            $tool->save();
        }
        return view('admin.seotool.googleanalytics',compact('tool','storename'));
    }

    public function analyticsupdate(Request $request,$storename)
    {
        $tool = Seotool::where('storename',$storename)->first();
        $tool->update($request->all());
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();
    }  


    public function pixel($storename)
    {
        
        $tool = Seotool::where('storename',$storename)->first();
        
        if(!$tool)
        {
            $tool = new Seotool;
            $tool->storename = $storename;
            $tool->google_analytics = '<script>//Google Analytics Script</script>';
            $tool->meta_keys = 'shopypall, shopypall store,multivendor';
            $tool->pixel = '<script>//Facebook Pixel Script</script>';
            $tool->save();
        }
       
        return view('admin.seotool.facebook-pixels',compact('tool','storename'));
    }

    public function pixelupdate(Request $request,$storename)
    {
        // dd("in");
        $tool = Seotool::where('storename',$storename)->first();
        $tool->update($request->all());
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();
    }  


    public function keywords($storename)
    {
        $tool = Seotool::where('storename',$storename)->first();
        return view('admin.seotool.meta-keywords',compact('tool','storename'));
    }

    public function keywordsupdate(Request $request,$storename)
    {
        $tool = Seotool::where('storename',$storename)->first();
        $tool->update($request->all());
        $msg = 'Data Updated Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();
    }
     
    public function popular($storename,$id)
    {
        // return $storename." ".$id;
        $expDate = Carbon::now()->subDays($id);
        $productss = ProductClick::where('storename',$storename)->whereDate('date', '>',$expDate)->get()->groupBy('product_id');
        $val = $id;
        return view('admin.seotool.popular',compact('val','productss','storename'));
    }  

}
