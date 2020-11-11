<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Validator;
use Session;

class AttributeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('storename.auth');
    }
    public function attrCreateForCategory($storename,$catid) {
      $data = Category::findOrFail($catid);
      $type = 'category';
      return view('admin.attribute.create', compact('data', 'type','storename'));
    }

    public function attrCreateForSubcategory($storename,$subcatid) {
      $data = Subcategory::findOrFail($subcatid);
      $type = 'subcategory';
      return view('admin.attribute.create', compact('data', 'type','storename'));
    }

    public function attrCreateForChildcategory($storename,$childcatid) {
      $data = Childcategory::findOrFail($childcatid);
      $type = 'childcategory';
      return view('admin.attribute.create', compact('data', 'type','storename'));
    }

    public function store(Request $request,$storename) {
      //--- Validation Section
      $this->validate($request,[
            'name' => 'required',
            'options' => 'required',
            'options.*' => 'required',
        ]);
      //--- Validation Section Ends

      //--- Logic Section
      $in = $request->all();
      $in['attributable_id'] = $request->category_id;
      if ($request->type == 'category') {
        $in['attributable_type'] = 'App\Models\Category';
      } elseif ($request->type == 'subcategory') {
        $in['attributable_type'] = 'App\Models\Subcategory';
      } elseif ($request->type == 'childcategory') {
        $in['attributable_type'] = 'App\Models\Childcategory';
      }
      $in['input_name'] = Str::slug($request->name, '_');

      if (request()->has('price_status')) {
        $in['price_status'] = 1;
      } else {
        $in['price_status'] = 0;
      }

      if (request()->has('details_status')) {
        $in['details_status'] = 1;
      } else {
        $in['details_status'] = 0;
      }
      $in['storename'] = $storename;
      $newAttr = Attribute::create($in);
      //--- Logic Section Ends


      $opts = $request->options;
      foreach ($opts as $key => $opt) {
        $attrOpt = new AttributeOption;
        $attrOpt->attribute_id = $newAttr->id;
        $attrOpt->name = $opt;
        $attrOpt->save();
      }


      //--- Redirect Section
      $msg = 'New Data Added Successfully.';
      Session::put('success',$msg);     
      return redirect()->back();
      //--- Redirect Section Ends
    }

    public function manage(Request $request,$storename,$id) {
      if ($request->type == 'category') {
        $data['data'] = Category::find($id);
      }
      if ($request->type == 'subcategory') {
        $data['data'] = Subcategory::find($id);
      }
      if ($request->type == 'childcategory') {
        $data['data'] = Childcategory::find($id);
      }
      $data['type'] = $request->type;
      return view('admin.attribute.manage', compact('storename','data'));
    }

    public function edit($storename,$id) {
      $data['attr'] = Attribute::find($id);
      return view('admin.attribute.edit', compact('storename','data'));
    }

    public function update(Request $request,$storename,$id) {
      $this->validate($request,[
          'name' => 'required',
          // 'options' => 'required',
          // 'options.*' => 'required',
      ]);
      //--- Validation Section
      // $rules = [
      //   'name' => [
      //       'required',
      //       function ($attribute, $value, $fail) {
      //           if (strtolower($value) == 'color' || strtolower($value) == 'size') {
      //               $fail('Attribute name cannot be color and size.');
      //           }
      //       },
      //   ],
      //   'options.*' => 'required',
      //   'options' => 'required'
      // ];
      // $validator = Validator::make($request->all(), $rules);

      // if ($validator->fails()) {
      //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
      // }
      //--- Validation Section Ends
      $attr = Attribute::find($id);
      $attr->name = $request->name;
      $attr->input_name = Str::slug($request->name, '_');

      if (request()->has('price_status')) {
        $attr->price_status = 1;
      } else {
        $attr->price_status = 0;
      }

      if (request()->has('details_status')) {
        $attr->details_status = 1;
      } else {
        $attr->details_status = 0;
      }

      $attr->save();

      $attrOpts = AttributeOption::where('attribute_id', $id);
      $attrOpts->delete();
      if($request->options):
        foreach ($request->options as $key => $option) {
          $newOpt = new AttributeOption;
          $newOpt->attribute_id = $id;
          $newOpt->name = $option;
          $newOpt->save();
        }
      endif;
      //--- Redirect Section
      $msg = 'New Data Added Successfully.';
      Session::put('success',$msg);     
        return redirect()->back();
      //--- Redirect Section Ends
    }

    public function options($storename,$id) {
      $options = AttributeOption::where('attribute_id', $id)->get();
      return response()->json($options);
    }

    public function destroy($storename,$id) {
      $attr = Attribute::find($id);
      $attr->attribute_options()->delete();
      $attr->delete();

      $msg = 'Data deleted successfully!';
      Session::put('success',$msg);     
      return redirect()->back();
        
      //--- Redirect Section
      // Session::flash('success', 'Data deleted successfully!');
      // return back();
      //--- Redirect Section Ends
    }
}
