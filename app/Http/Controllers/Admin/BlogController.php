<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Blog::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('photo', function(Blog $data) use($storename) {
                                $photo = $data->photo ? url('assets/images/blogs/'.$data->photo):url('assets/images/noimage.png');
                                return '<img src="' . $photo . '" alt="Image">';
                            })
                            ->addColumn('action', function(Blog $data) use($storename) {
                                return '<div class="action-list"><a data-href="' . route('admin-blog-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-blog-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['photo', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.blog.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        $cats = BlogCategory::where('storename',$storename)->get();
        return view('admin.blog.create',compact('cats','storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section~
        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
                ];
        $this->validate($request,[
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
            'title' => 'required',
            'details' => 'required',
            'source' => 'required',
            'tags' => 'required',
            'category_id' => 'required',
        ]);
        

        //--- Validation Section Ends

        //--- Logic Section
        $data = new Blog();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/blogs',$name);           
            $input['photo'] = $name;
        } 
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
         }  
        if (!empty($request->tags)) 
         {
            $input['tags'] = implode(',', $request->tags);       
         }
        if ($request->secheck == "") 
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;         
         } 
         $data->storename = $storename;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        Session::put('success',$msg);
        return redirect()->back();
        // return response()->json($msg);      
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $cats = BlogCategory::where('storename',$storename)->get();
        $data = Blog::findOrFail($id);
        return view('admin.blog.edit',compact('data','cats','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        $this->validate($request,[
            'photo'      => 'mimes:jpeg,jpg,png,svg',
            'title' => 'required',
            'details' => 'required',
            'source' => 'required',
            'tags' => 'required',
            'category_id' => 'required',
        ]);
        //--- Validation Section Ends

        //--- Logic Section
        $data = Blog::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/blogs',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/blogs/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/blogs/'.$data->photo);
                    }
                }            
            $input['photo'] = $name;
            } 
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
         } 
        else {
            $input['meta_tag'] = null;
         }
        if (!empty($request->tags)) 
         {
            $input['tags'] = implode(',', $request->tags);       
         }
        else {
            $input['tags'] = null;
         } 
        if ($request->secheck == "") 
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;         
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
        $data = Blog::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section     
            $msg = 'Data Deleted Successfully.';
            Session::put('success',$msg);     
            return redirect()->back();
                 
            //--- Redirect Section Ends     
        }
        if($data->photo != '159618649311.jpg')
        {
            if (file_exists(public_path().'/assets/images/blogs/'.$data->photo)) {
                // unlink(public_path().'/assets/images/blogs/'.$data->photo);
            }
        }
        //If Photo Exist
        
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);     
        return redirect()->back();    
        //--- Redirect Section Ends     
    }
}
