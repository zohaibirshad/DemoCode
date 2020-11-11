<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = BlogCategory::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(BlogCategory $data) use ($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-cblog-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-cblog-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->toJson();//--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.cblog.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.cblog.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section
        $this->validate($request,[
            'name' => 'required|unique:blog_categories',
            'slug' => 'required|unique:blog_categories'
        ]);

        //--- Validation Section Ends

        //--- Logic Section
        $data = new BlogCategory;
        $input = $request->all();
        $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section  
        $msg = 'New Data Added Successfully.';
        Session::put('success', $msg);
        
        return redirect()->back();
        //--- Redirect Section Ends  
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $data = BlogCategory::findOrFail($id);
        return view('admin.cblog.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        $this->validate($request,[
            'name' => 'required|unique:blog_categories,name,'.$id,
           'slug' => 'required|unique:blog_categories,slug,'.$id
        ]);
        
        //--- Validation Section Ends

        //--- Logic Section
        $data = BlogCategory::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section          
        $msg = 'Data Updated Successfully.';
        Session::put('success', $msg);
        
        return redirect()->back();   
        //--- Redirect Section Ends  

    }

    //*** GET Request
    public function destroy($storename,$id)
    {
        $data = BlogCategory::findOrFail($id);

        //--- Check If there any blogs available, If Available Then Delete it 
        if($data->blogs->count() > 0)
        {
            foreach ($data->blogs as $element) {
                $element->delete();
            }
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success', $msg);
        
        return redirect()->back();    
        //--- Redirect Section Ends   
    }
}
