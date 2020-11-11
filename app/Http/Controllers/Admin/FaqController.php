<?php

namespace App\Http\Controllers\Admin;
use Datatables;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class FaqController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Faq::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('details', function(Faq $data) use($storename) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(Faq $data) use($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-faq-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-faq-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.faq.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.faq.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        $this->validate($request,[
            'title' => 'required',
        ]);
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $data = new Faq();
        $input = $request->all();
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
        $data = Faq::findOrFail($id);
        return view('admin.faq.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request, [
            'title' => 'required'
            ]);
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $data = Faq::findOrFail($id);
        $input = $request->all();
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
        $data = Faq::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends   
    }
}
