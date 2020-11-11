<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;

class CategoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
  }

    //*** JSON Request
  public function datatables($storename)
  {
   $datas = Category::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
   return Datatables::of($datas)
   ->addColumn('status', function(Category $data) use ($storename) {
    $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
    $s = $data->status == 1 ? 'selected' : '';
    $ns = $data->status == 0 ? 'selected' : '';
    return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-cat-status',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin-cat-status',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
  })
   ->addColumn('attributes', function(Category $data) use ($storename) {
    $buttons = '<div class="action-list"><a data-href="' . route('admin-attr-createForCategory',[$storename,$data->id]) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
    if ($data->attributes()->count() > 0) {
      $buttons .= '<a href="' . route('admin-attr-manage',[$storename,$data->id]) .'?type=category' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
    }
    $buttons .= '</div>';

    return $buttons;
  })
   ->addColumn('action', function(Category $data) use ($storename){
    return '<div class="action-list"><a data-href="' . route('admin-cat-edit',[$storename , $data->id ]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin-cat-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
  })
   ->rawColumns(['status','attributes','action'])
    ->toJson(); //--- Returning Json Data To Client Side
  }

//*** GET Request
  public function index($storename)
  {
    return view('admin.category.index',compact('storename'));
  }

//*** GET Request
  public function create($storename)
  {
    return view('admin.category.create',compact('storename'));
  }

//*** POST Request
  public function store(Request $request,$storename)
  {
//--- Validation Section
    $this->validate($request,[
        'name' => 'required',
        'photo'  => 'mimes:jpeg,jpg,png,svg',
        'slug'  =>  'required|unique:categories|regex:/^[a-zA-Z0-9\s-]+$/',
    ]);
//--- Validation Section Ends

//--- Logic Section
    $data = new Category();
    $input = $request->all();
    if ($file = $request->file('photo'))
    {
      $name = time().$file->getClientOriginalName();
      $file->move('assets/images/categories',$name);
      $input['photo'] = $name;
    }
    if ($request->is_featured == ""){
      $input['is_featured'] = 0;
    }
    else {
      $input['is_featured'] = 1;
//--- Validation Section
      $this->validate($request,[
          'image'  => 'required|mimes:jpeg,jpg,png,svg',
          
      ]);
//--- Validation Section Ends
      if ($file = $request->file('image'))
      {
       $name = time().$file->getClientOriginalName();
       $file->move('assets/images/categories',$name);
       $input['image'] = $name;
     }
   }
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
  $data = Category::findOrFail($id);
  return view('admin.category.edit',compact('data','storename'));
}

//*** POST Request
public function update(Request $request,$storename,$id)
{
 //--- Validation Section
        $this->validate($request,[
            'photo'  => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'required',
            'name' => 'required',
        ]);

  

        // $rules = [
        //  'photo' => 'mimes:jpeg,jpg,png,svg',
        //  'slug' => 'unique:categories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
        //     ];
        // $customs = [
        //  'photo.mimes' => 'Icon Type is Invalid.',
        //  'slug.unique' => 'This slug has already been taken.',
        //     'slug.regex' => 'Slug Must Not Have Any Special Characters.'
        //       ];
        // $validator = Validator::make(Input::all(), $rules, $customs);

        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Category::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/categories',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/categories/'.$data->photo);
                    }
                }
            $input['photo'] = $name;
            }

            if ($request->is_featured == ""){
                $input['is_featured'] = 0;
            }
            else {
                    $input['is_featured'] = 1;
                    //--- Validation Section
                    $rules = [
                        'image' => 'mimes:jpeg,jpg,png,svg'
                            ];
                    $this->validate($request,[
                        'image' => 'mimes:jpeg,jpg,png,svg'
                    ]);
                    //--- Validation Section Ends
                    if ($file = $request->file('image'))
                    {
                       $name = time().$file->getClientOriginalName();
                       $file->move('assets/images/categories',$name);
                       $input['image'] = $name;
                    }
            }

        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        Session::put('success', $msg);
        
        return redirect()->back();
//--- Redirect Section Ends
}

//*** GET Request Status
public function status($storename,$id1,$id2)
{
$data = Category::findOrFail($id1);
$data->status = $id2;
$data->update();
}


//*** GET Request Delete
public function destroy($storename,$id)
{
$data = Category::findOrFail($id);

if($data->attributes->count() > 0)
{
//--- Redirect Section
$msg = 'Remove the Attributes first !';
Session::put('error', $msg);
        
return redirect()->back();
//--- Redirect Section Ends
}

if($data->subs->count()>0)
{
//--- Redirect Section
$msg = 'Remove the subcategories first !';
Session::put('error', $msg);
        
return redirect()->back();
//--- Redirect Section Ends
}
if($data->products->count()>0)
{
//--- Redirect Section
$msg = 'Remove the products first !';
Session::put('error', $msg);
        
return redirect()->back();
//--- Redirect Section Ends
}


//If Photo Doesn't Exist
if($data->photo == null){
$data->delete();
//--- Redirect Section
$msg = 'Data Deleted Successfully.';
Session::put('success', $msg);
        
return redirect()->back();
//--- Redirect Section Ends
}
//If Photo Exist
if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
// unlink(public_path().'/'.$storename.'/assets/images/categories/'.$data->photo);
}
if (file_exists(public_path().'/'.$storename.'/assets/images/categories/'.$data->image)) {
// unlink(public_path().'/assets/images/categories/'.$data->image);
}
$data->delete();
//--- Redirect Section
$msg = 'Data Deleted Successfully.';
Session::put('success', $msg);
return redirect()->back();
//--- Redirect Section Ends
}
}
