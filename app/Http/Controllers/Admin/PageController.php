<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;

class PageController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Page::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('header', function(Page $data) use($storename){
                                $class = $data->header == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->header == 1 ? 'selected' : '';
                                $ns = $data->header == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-page-header',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>Showed</option><option data-val="0" value="'. route('admin-page-header',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Not Showed</option></select></div>';
                            }) 
                            ->addColumn('footer', function(Page $data) use($storename){
                                $class = $data->footer == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->footer == 1 ? 'selected' : '';
                                $ns = $data->footer == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-page-footer',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>Showed</option><<option data-val="0" value="'. route('admin-page-footer',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Not Showed</option>/select></div>';
                            }) 
                            ->addColumn('action', function(Page $data) use($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-page-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-page-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['header','footer','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.page.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.page.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home','faq','contact','blog','cart','checkout');
        if (in_array($slug, $main)) {
            
            \Session::put('error','This slug has already been taken.');
            return redirect()->back();
        }
        $rules = ['slug' => 'unique:pages'];
        $customs = ['slug.unique' => 'This slug has already been taken.'];
        $validator = Validator::make(Input::all(), $rules, $customs);
        if ($validator->fails()) {
            \Session::put('error','This slug has already been taken.');
            return redirect()->back();
            
          // return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Page();
        $input = $request->all();
 
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
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
        \Session::put('success',$msg);
            return redirect()->back();      
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $data = Page::findOrFail($id);
        return view('admin.page.edit',compact('data','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home','faq','contact','blog','cart','checkout');
        if (in_array($slug, $main)) {

            \Session::put('error','This slug has already been taken.');
            return redirect()->back();
        // return response()->json(array('errors' => [ 0 => 'This slug has already been taken.' ]));          
        }
        $this->validate($request,[
            'slug' => 'unique:pages,slug,'.$id
        ]);
        // $rules = ['slug' => 'unique:pages,slug,'.$id];
        // $customs = ['slug.unique' => 'This slug has already been taken.'];
        // $validator = Validator::make(Input::all(), $rules, $customs);
        
        // if ($validator->fails()) {

        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Page::findOrFail($id);
        $input = $request->all();
        if (!empty($request->meta_tag)) 
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);       
         } 
         else {
            $input['meta_tag'] = null;
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
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends           
    }
      //*** GET Request Header
      public function header($storename,$id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->header = $id2;
            $data->update();
        }
      //*** GET Request Footer
      public function footer($storename,$id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->footer = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        $data = Page::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends   
    }
}