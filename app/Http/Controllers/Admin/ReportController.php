<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
	public function __construct()
	    {
	        $this->middleware('auth:admin');

	    }

	    //*** JSON Request
	    public function datatables($storename)
	    {
	         $datas = Report::where('storename',$storename)->orderBy('id')->get();
	         //--- Integrating This Collection Into Datatables
	         return Datatables::of($datas)
	                            ->addColumn('product', function(Report $data) use ($storename) {
	                                $name = mb_strlen(strip_tags($data->product->name),'utf-8') > 50 ? mb_substr(strip_tags($data->product->name),0,50,'utf-8').'...' : strip_tags($data->product->name);
	                                $product = '<a href="'.route('front.product', [$storename,$data->product->slug]).'" target="_blank">'.$name.'</a>';
	                                return $product;
	                            })
	                            ->addColumn('reporter', function(Report $data) use ($storename){
	                                $name = $data->user->name;
	                                return $name;
	                            })
	                            ->addColumn('title', function(Report $data) use ($storename){
	                                $text = mb_strlen(strip_tags($data->title),'utf-8') > 250 ? mb_substr(strip_tags($data->title),0,250,'utf-8').'...' : strip_tags($data->title);
	                                return $text;
	                            })
	                            ->addColumn('action', function(Report $data) use ($storename){
	                                return '<div class="action-list"><a data-href="' . route('admin-report-show',[$storename,$data->id]) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a><a href="javascript:;" data-href="' . $storename('admin-report-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
	                            }) 
	                            ->rawColumns(['product','action'])
	                            ->toJson(); //--- Returning Json Data To Client Side
	    }
	    //*** GET Request
	    public function index($storename)
	    {
	        return view('admin.report.index',compact('storename'));
	    }

	    //*** GET Request
	    public function show($id)
	    {
	        $data = Report::findOrFail($storename,$id);
	        return view('admin.report.show',compact('data','storename'));
	    }


	    //*** GET Request Delete
		public function destroy($storename,$id)
		{
		    $data = Report::findOrFail($id);
		    $data->delete();
		    //--- Redirect Section     
		    $msg = 'Data Deleted Successfully.';
		    \Session::put('success',$msg);
            return redirect()->back();   
		    //--- Redirect Section Ends    
		}
}
