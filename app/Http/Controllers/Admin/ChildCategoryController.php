<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class ChildCategoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
  }

    //*** JSON Request
  public function datatables($storename)
  {
   $datas = Childcategory::where('storename',$storename)->orderBy('id','desc')->get();
           //--- Integrating This Collection Into Datatables
   return Datatables::of($datas)
   ->addColumn('category', function(Childcategory $data) use ($storename) {
    return $data->subcategory->category->name;
  })
   ->addColumn('subcategory', function(Childcategory $data) use ($storename){
    return $data->subcategory->name;
  })
   ->addColumn('status', function(Childcategory $data) use ($storename){
    $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
    $s = $data->status == 1 ? 'selected' : '';
    $ns = $data->status == 0 ? 'selected' : '';
    return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-childcat-status',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin-childcat-status',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
  })
   ->addColumn('attributes', function(Childcategory $data) use ($storename){
    $buttons = '<div class="action-list"><a data-href="' . route('admin-attr-createForChildcategory',[$storename,$data->id]) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
    if ($data->attributes()->count() > 0) {
      $buttons .= '<a href="' . route('admin-attr-manage', [$storename,$data->id]) .'?type=childcategory' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
    }
    $buttons .= '</div>';

    return $buttons;
  })
   ->addColumn('action', function(Childcategory $data) use ($storename){
    return '<div class="action-list"><a data-href="' . route('admin-childcat-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-childcat-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
  })
   ->rawColumns(['status', 'attributes','action'])->toJson(); //--- Returning Json Data To Client Side
  }



      //*** GET Request
  public function index($storename)
  {
    return view('admin.childcategory.index',compact('storename'));
  }

      //*** GET Request
  public function create($storename)
  {
    $cats = Category::where('storename',$storename)->get();
    return view('admin.childcategory.create',compact('cats','storename'));
  }

      //*** POST Request
  public function store(Request $request,$storename)
  {
     $this->validate($request,[
      'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/',
      'name' => 'required',
      'category_id' => 'required',  
    ]);
          //--- Validation Section
          // $rules = [
          //     'slug' => 'unique:childcategories|regex:/^[a-zA-Z0-9\s-]+$/'
          //          ];
          // $customs = [
          //     'slug.unique' => 'This slug has already been taken.',
          //     'slug.regex' => 'Slug Must Not Have Any Special Characters.'
          //            ];
          // $validator = Validator::make(Input::all(), $rules, $customs);

          // if ($validator->fails()) {
          //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
          // }
          //--- Validation Section Ends

          //--- Logic Section
    $data = new Childcategory();
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
   $cats = Category::where('storename',$storename)->get();
   $subcats = Subcategory::where('storename',$storename)->get();
   $data = Childcategory::findOrFail($id);
   return view('admin.childcategory.edit',compact('data','cats','subcats','storename'));
  }

      //*** POST Request
  public function update(Request $request,$storename,$id)
  {
    $this->validate($request,[
      'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/',
      'name' => 'required',
      'category_id' => 'required',  
    ]);
          //--- Validation Section
          // $rules = [
          //     'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
          //          ];
          // $customs = [
          //     'slug.unique' => 'This slug has already been taken.',
          //     'slug.regex' => 'Slug Must Not Have Any Special Characters.'
          //            ];
          // $validator = Validator::make(Input::all(), $rules, $customs);

          // if ($validator->fails()) {
          //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
          // }
          //--- Validation Section Ends

          //--- Logic Section
    $data = Childcategory::findOrFail($id);
    $input = $request->all();
    $data->update($input);
          //--- Logic Section Ends

          //--- Redirect Section
    $msg = 'Data Updated Successfully.';
    Session::put('success',$msg);
    return redirect()->back();
          //--- Redirect Section Ends
  }

        //*** GET Request Status
  public function status($storename,$id1,$id2)
  {
    $data = Childcategory::findOrFail($id1);
    $data->status = $id2;
    $data->update();
  }

      //*** GET Request
  public function load($storename,$id)
  {
    $subcat = Subcategory::findOrFail($id);
    return view('load.childcategory',compact('subcat','storename'));
  }


      //*** GET Request Delete
  public function destroy($storename,$id)
  {
    $data = Childcategory::findOrFail($id);

    if($data->attributes->count()>0)
    {
          //--- Redirect Section
      $msg = 'Remove the Attributes first !';
      Session::put('error',$msg);
      return redirect()->back();
          //--- Redirect Section Ends
    }

    if($data->products->count()>0)
    {
          //--- Redirect Section
      $msg = 'Remove the products first !';
      Session::put('error',$msg);
      return redirect()->back();
          //--- Redirect Section Ends
    }

    $data->delete();
          //--- Redirect Section
    $msg = 'Data Deleted Successfully.';
    Session::put('success',$msg);
    return redirect()->back();
          //--- Redirect Section Ends
  }
}
