<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Currency;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Auth;
use DB;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Session;
use Validator;

class ProductController extends Controller
{
  public $global_language;

  public function __construct()
  {
    $this->middleware('auth');

    if (Session::has('language'))
    {
      $data = DB::table('languages')->where([['id',Session::get('language')],['storename',$storename]])->first();
      $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
      $this->vendor_language = json_decode($data_results);
    }
    else
    {
      $data = DB::table('languages')->where('is_default','=',1)->where('storename',$storename)->first();
      $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
      $this->vendor_language = json_decode($data_results);

    }

  }

    //*** JSON Request
  public function datatables($storename)
  {
    $user = Auth::user();
    $datas = $user->products()->where('product_type','normal')->where('storename',$storename)->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
    return Datatables::of($datas)
    ->editColumn('name', function(Product $data) use ($storename) {
      $name = strlen(strip_tags($data->name)) > 50 ? substr(strip_tags($data->name),0,50).'...' : strip_tags($data->name);
      $id = '<small>Product ID: <a href="'.route('front.product', [$storename,$data->slug]).'" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';
      return  $name.'<br>'.$id;
    })
    ->editColumn('price', function(Product $data) use ($storename) {
      $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
      $price = round($data->price * $sign->value , 2);
      $price = $sign->sign.$price ;
      return  $price;
    })
    ->addColumn('status', function(Product $data) use ($storename) {
      $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
      $s = $data->status == 1 ? 'selected' : '';
      $ns = $data->status == 0 ? 'selected' : '';
      return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('vendor-prod-status',[$storename,'id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.$this->vendor_language->lang713.'</option><<option data-val="0" value="'. route('vendor-prod-status',[$storename,'id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.$this->vendor_language->lang714.'</option>/select></div>';
    })
    ->addColumn('action', function(Product $data) use ($storename) {
      return '<div class="action-list"><a href="' . route('vendor-prod-edit',[$storename,$data->id]) . '"> <i class="fas fa-edit"></i>'.$this->vendor_language->lang715.'</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> '.$this->vendor_language->lang716.'</a><a href="javascript:;" data-href="' . route('vendor-prod-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
    })
    ->rawColumns(['name', 'status', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
                          }


    //*** JSON Request
                          public function catalogdatatables($storename)
                          {
                           $user = Auth::user();
                           $datas =  Product::where('product_type','normal')->where('status','=',1)->where('storename',$storename)->where('is_catalog','=',1)->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
                           return Datatables::of($datas)
                           ->editColumn('name', function(Product $data) use ($storename) {
                            $name = strlen(strip_tags($data->name)) > 50 ? substr(strip_tags($data->name),0,50).'...' : strip_tags($data->name);
                            $id = '<small>Product ID: <a href="'.route('front.product', [$storename,$data->slug]).'" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';
                            return  $name.'<br>'.$id;
                          })
                           ->editColumn('price', function(Product $data) use ($storename) {
                            $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                            $price = $sign->sign.$data->price;
                            return  $price;
                          })
                           ->addColumn('action', function(Product $data) use ($storename) {
                            $user = Auth::user();
                            $ck = $user->products()->where('catalog_id','=',$data->id)->where('storename',$storename)->count() > 0;
                            $catalog = $ck ? '<a href="javascript:;"> Added To Catalog</a>' : '<a href="' . route('vendor-prod-catalog-edit',[$storename,$data->id]) . '"><i class="fas fa-plus"></i> Add To Catalog</a>';
                            return '<div class="action-list">'. $catalog .'</div>';
                          })
                           ->rawColumns(['name', 'status', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
                          }

    //*** GET Request
                          public function index($storename)
                          {
                            return view('vendor.product.index',compact('storename'));
                          }


    //*** GET Request
                          public function catalogs($storename)
                          {
                            return view('vendor.product.catalogs',compact('storename'));
                          }

    //*** GET Request
                          public function types($storename)
                          {
                            return view('vendor.product.types',compact('storename'));
                          }

    //*** GET Request
                          public function createPhysical($storename)
                          {
                            $cats = Category::all();
                            $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                            return view('vendor.product.create.physical',compact('cats','sign','storename'));
                          }

    //*** GET Request
                          public function createDigital($storename)
                          {
                            $cats = Category::all();
                            $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                            return view('vendor.product.create.digital',compact('cats','sign','storename'));
                          }

    //*** GET Request
                          public function createLicense($storename)
                          {
                            $cats = Category::all();
                            $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                            return view('vendor.product.create.license',compact('cats','sign','storename'));
                          }

    //*** GET Request
                          public function status($storename,$id1,$id2)
                          {
                            $data = Product::findOrFail($id1);
                            $data->status = $id2;
                            $data->update();
                          }

    //*** POST Request
                          public function uploadUpdate(Request $request,$storename,$id)
                          {
                            $this->validate($request,[
                              'image' => 'required',
                            ]);
        //--- Validation Section
        // $rules = [
        //   'image' => 'required',
        // ];
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }

                            $data = Product::findOrFail($id);

        //--- Validation Section Ends
                            $image = $request->image;
                            list($type, $image) = explode(';', $image);
                            list(, $image)      = explode(',', $image);
                            $image = base64_decode($image);
                            $image_name = time().str_random(8).'.png';
                            $path = 'assets/images/products/'.$image_name;
                            file_put_contents($path, $image);
                            if($data->photo != null)
                            {
                              if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                        // unlink(public_path().'/assets/images/products/'.$data->photo);
                              }
                            }
                            $input['photo'] = $image_name;
                            $data->update($input);
                            if($data->thumbnail != null)
                            {
                              if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                        // unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
                              }
                            }

                            $img = Image::make(public_path().'/assets/images/products/'.$data->photo)->resize(285, 285);
                            $thumbnail = time().str_random(8).'.jpg';
                            $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                            $data->thumbnail  = $thumbnail;
                            $data->update();
                            return response()->json(['status'=>true,'file_name' => $image_name]);
                          }


    //*** POST Request
                          public function import($storename){

                            $cats = Category::all();
                            $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                            return view('vendor.product.productcsv',compact('cats','sign','storename'));
                          }

                          public function importSubmit(Request $request,$storename)
                          {

                            $user = Auth::user();
                            $package = $user->subscribes()->orderBy('id','desc')->first();
                            $prods = $user->products()->orderBy('id','desc')->get()->count();
                            if($prods < $package->allowed_products || $package->allowed_products == 0) {
                              $log = "";
        //--- Validation Section
                              $this->validate($request,[
                                'csvfile'      => 'required|mimes:csv,txt',
                              ]);
        // $rules = [
        //     'csvfile'      => 'required|mimes:csv,txt',
        // ];

        // $validator = Validator::make(Input::all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }

                              $filename = '';
                              if ($file = $request->file('csvfile'))
                              {
                                $filename = time().'-'.$file->getClientOriginalName();
                                $file->move('assets/temp_files',$filename);
                              }

        //$filename = $request->file('csvfile')->getClientOriginalName();
        //return response()->json($filename);
                              $datas = "";

                              $file = fopen(public_path('assets/temp_files/'.$filename),"r");
                              $i = 1;
                              while (($line = fgetcsv($file)) !== FALSE) {

                                if($i != 1)
                                {

                                  if (!Product::where([['product_code',$line[0]],['storename',$storename]])->exists()){

                //--- Validation Section Ends

                //--- Logic Section
                                    $data = new Product;
                                    $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();

                                    $input['type'] = 'Physical';
                                    $input['product_code'] = $line[0];

                                    $input['category_id'] = "";
                                    $input['subcategory_id'] = "";
                                    $input['childcategory_id'] = "";

                                    $mcat = Category::where('storename',$storename)->where(DB::raw('lower(name)'), strtolower($line[1]));
                //$mcat = Category::where("name", $line[1]);

                                    if($mcat->exists()){
                                      $input['category_id'] = $mcat->first()->id;

                                      if($line[2] != ""){
                                        $scat = Subcategory::where('storename',$storename)->where(DB::raw('lower(name)'), strtolower($line[2]));

                                        if($scat->exists()) {
                                          $input['subcategory_id'] = $scat->first()->id;
                                        }
                                      }
                                      if($line[3] != ""){
                                        $chcat = Childcategory::where('storename',$storename)->where(DB::raw('lower(name)'), strtolower($line[3]));

                                        if($chcat->exists()) {
                                          $input['childcategory_id'] = $chcat->first()->id;
                                        }
                                      }



                                      $input['photo'] = $line[5];
                                      $input['name'] = $line[4];
                                      $input['details'] = $line[6];
//                $input['category_id'] = $request->category_id;
//                $input['subcategory_id'] = $request->subcategory_id;
//                $input['childcategory_id'] = $request->childcategory_id;
                                      $input['color'] = $line[13];
                                      $input['price'] = $line[7];
                                      $input['previous_price'] = $line[8];
                                      $input['stock'] = $line[9];
                                      $input['size'] = $line[10];
                                      $input['size_qty'] = $line[11];
                                      $input['size_price'] = $line[12];
                                      $input['youtube'] = $line[15];
                                      $input['policy'] = $line[16];
                                      $input['meta_tag'] = $line[17];
                                      $input['meta_description'] = $line[18];
                                      $input['tags'] = $line[14];
                                      $input['product_type'] = $line[19];
                                      $input['affiliate_link'] = $line[20];



                // Conert Price According to Currency
                                      $input['price'] = ($input['price'] / $sign->value);
                                      $input['previous_price'] = ($input['previous_price'] / $sign->value);

                // Save Data
                                      $data->fill($input)->save();

                // Set SLug
                                      $prod = Product::find($data->id);
                                      $prod->slug = str_slug($data->name,'-').'-'.strtolower($data->sku);
                // Set Thumbnail


                                      $img = Image::make($line[5])->resize(285, 285);
                                      $thumbnail = time().str_random(8).'.jpg';
                                      $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                                      $prod->thumbnail  = $thumbnail;
                                      $prod->update();


                                    }else{
                                      $log .= "<br>Row No: ".$i." - No Category Found!<br>";
                                    }

                                  }else{
                                    $log .= "<br>Row No: ".$i." - Duplicate Product Code!<br>";
                                  }

                                }

                                $i++;

                              }
                              fclose($file);


        //--- Redirect Section
                              $msg = 'Bulk Product File Imported Successfully.<a href="'.route('admin-prod-index',$storename).'">View Product Lists.</a>'.$log;
                              \Session::put('success',$msg);
                              return redirect()->back();



                            }
                            else
                            {
            //--- Redirect Section
                              \Session::put('error','You Can\'t Add More Products.');
                              return redirect()->back();
            // return response()->json(array('errors' => [ 0 => 'You Can\'t Add More Products.']));

            //--- Redirect Section Ends
                            }
                          }



    //*** POST Request
                          public function store(Request $request,$storename)
                          {

                            $user = Auth::user();
                            $package = $user->subscribes()->orderBy('id','desc')->first();
                            $prods = $user->products()->orderBy('id','desc')->get()->count();

                            if($prods < $package->allowed_products || $package->allowed_products == 0)
                            {
                              $this->validate($request,[
                                'photo'      => 'required|mimes:jpeg,jpg,png,svg',
                                'file'       => 'mimes:zip'
                              ]);
        //--- Validation Section
        // $rules = [
        //        'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        //        'file'       => 'mimes:zip'
        //         ];

        // $validator = Validator::make(Input::all(), $rules);

        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends

        //--- Logic Section
                              $data = new Product;
                              $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                              $input = $request->all();
            // Check File
                              if ($file = $request->file('file'))
                              {
                                $name = time().$file->getClientOriginalName();
                                $file->move('assets/files',$name);
                                $input['file'] = $name;
                              }

                              if ($file = $request->file('photo')) 
                              {      
                               $name = time().$file->getClientOriginalName();
                               $file->move('assets/images/products',$name);           
                               $input['photo'] = $name;
                             } 

            // Check Physical
                             if($request->type == "Physical")
                             {
                              $this->validate($request,[
                                'sku'      => 'min:8|unique:products'
                              ]);
                    // //--- Validation Section
                    // $rules = ['sku'      => 'min:8|unique:products'];

                    // $validator = Validator::make(Input::all(), $rules);

                    // if ($validator->fails()) {
                    //     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    // }
                    //--- Validation Section Ends


            // Check Condition
                              if ($request->product_condition_check == ""){
                                $input['product_condition'] = 0;
                              }

            // Check Shipping Time
                              if ($request->shipping_time_check == ""){
                                $input['ship'] = null;
                              }

            // Check Size
                              if(empty($request->size_check ))
                              {
                                $input['size'] = null;
                                $input['size_qty'] = null;
                                $input['size_price'] = null;
                              }
                              else{
                                if(in_array(null, $request->size) || in_array(null, $request->size_qty))
                                {
                                  $input['size'] = null;
                                  $input['size_qty'] = null;
                                  $input['size_price'] = null;
                                }
                                else
                                {
                                  $input['size'] = implode(',', $request->size);
                                  $input['size_qty'] = implode(',', $request->size_qty);
                                  $input['size_price'] = implode(',', $request->size_price);
                                }
                              }

            // Check Whole Sale
                              if(empty($request->whole_check ))
                              {
                                $input['whole_sell_qty'] = null;
                                $input['whole_sell_discount'] = null;
                              }
                              else{
                                if(in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount))
                                {
                                  $input['whole_sell_qty'] = null;
                                  $input['whole_sell_discount'] = null;
                                }
                                else
                                {
                                  $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                                  $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                                }
                              }


            // Check Color
                              if(empty($request->color_check))
                              {
                                $input['color'] = null;
                              }
                              else{
                                $input['color'] = implode(',', $request->color);
                              }

            // Check Measurement
                              if ($request->mesasure_check == "")
                              {
                                $input['measure'] = null;
                              }

                            }

            // Check Seo
                            if (empty($request->seo_check))
                            {
                              $input['meta_tag'] = null;
                              $input['meta_description'] = null;
                            }
                            else {
                              if (!empty($request->meta_tag))
                              {
                                $input['meta_tag'] = implode(',', $request->meta_tag);
                              }
                            }

             // Check License

                            if($request->type == "License")
                            {

                              if(in_array(null, $request->license) || in_array(null, $request->license_qty))
                              {
                                $input['license'] = null;
                                $input['license_qty'] = null;
                              }
                              else
                              {
                                $input['license'] = implode(',,', $request->license);
                                $input['license_qty'] = implode(',', $request->license_qty);
                              }

                            }

             // Check Features
                            if(in_array(null, $request->features) || in_array(null, $request->colors))
                            {
                              $input['features'] = null;
                              $input['colors'] = null;
                            }
                            else
                            {
                              $input['features'] = implode(',', str_replace(',',' ',$request->features));
                              $input['colors'] = implode(',', str_replace(',',' ',$request->colors));
                            }

            //tags
                            if (!empty($request->tags))
                            {
                              $input['tags'] = implode(',', $request->tags);
                            }

            // Conert Price According to Currency
                            $input['price'] = ($input['price'] / $sign->value);
                            $input['previous_price'] = ($input['previous_price'] / $sign->value);
                            $input['user_id'] = Auth::user()->id;

           // store filtering attributes for physical product
                            $attrArr = [];
                            if (!empty($request->category_id)) {
                             $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('storename',$storename)->where('attributable_type', 'App\Models\Category')->get();
                             if (!empty($catAttrs)) {
                               foreach ($catAttrs as $key => $catAttr) {
                                 $in_name = $catAttr->input_name;
                                 if ($request->has("$in_name")) {
                                   $attrArr["$in_name"]["values"] = $request["$in_name"];
                                   $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                                   if ($catAttr->details_status) {
                                     $attrArr["$in_name"]["details_status"] = 1;
                                   } else {
                                     $attrArr["$in_name"]["details_status"] = 0;
                                   }
                                 }
                               }
                             }
                           }

                           if (!empty($request->subcategory_id)) {
                             $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->where('storename',$storename)->get();
                             if (!empty($subAttrs)) {
                               foreach ($subAttrs as $key => $subAttr) {
                                 $in_name = $subAttr->input_name;
                                 if ($request->has("$in_name")) {
                                   $attrArr["$in_name"]["values"] = $request["$in_name"];
                                   $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                                   if ($subAttr->details_status) {
                                     $attrArr["$in_name"]["details_status"] = 1;
                                   } else {
                                     $attrArr["$in_name"]["details_status"] = 0;
                                   }
                                 }
                               }
                             }
                           }
                           if (!empty($request->childcategory_id)) {
                             $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->where('storename',$storename)->get();
                             if (!empty($childAttrs)) {
                               foreach ($childAttrs as $key => $childAttr) {
                                 $in_name = $childAttr->input_name;
                                 if ($request->has("$in_name")) {
                                   $attrArr["$in_name"]["values"] = $request["$in_name"];
                                   $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                                   if ($childAttr->details_status) {
                                     $attrArr["$in_name"]["details_status"] = 1;
                                   } else {
                                     $attrArr["$in_name"]["details_status"] = 0;
                                   }
                                 }
                               }
                             }
                           }



                           if (empty($attrArr)) {
                             $input['attributes'] = NULL;
                           } else {
                             $jsonAttr = json_encode($attrArr);
                             $input['attributes'] = $jsonAttr;
                           }

            // Save Data
                           $data->fill($input)->save();

            // Set SLug

                           $prod = Product::find($data->id);
                           if($prod->type != 'Physical'){
                            $prod->slug = str_slug($data->name,'-').'-'.strtolower(str_random(3).$data->id.str_random(3));
                          }
                          else {
                            $prod->slug = str_slug($data->name,'-').'-'.strtolower($data->sku);
                          }
                // Set Photo
                          $newimg = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, 800);
                          $photo = time().str_random(8).'.jpg';
                          $newimg->save(public_path().'/assets/images/products/'.$photo);


                // Set Thumbnail
                          $img = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(285, 285);
                          $thumbnail = time().str_random(8).'.jpg';
                          $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                          $prod->thumbnail  = $thumbnail;
                          $prod->photo  = $photo;
                          $prod->update();

            // Add To Gallery If any
                          $lastid = $data->id;
                          if ($files = $request->file('gallery')){
                            foreach ($files as  $key => $file){
                              if(in_array($key, $request->galval))
                              {
                                $gallery = new Gallery;
                                $name = time().$file->getClientOriginalName();
                                $img = Image::make($file->getRealPath())->resize(800, 800);
                                $thumbnail = time().str_random(8).'.jpg';
                                $img->save(public_path().'/assets/images/galleries/'.$name);
                                $gallery['photo'] = $name;
                                $gallery['product_id'] = $lastid;
                                $gallery->save();
                              }
                            }
                          }
        //logic Section Ends

        //--- Redirect Section
                          $msg = 'New Product Added Successfully.<a href="'.route('vendor-prod-index',$storename).'">View Product Lists.</a>';
                          \Session::put('success',$msg);
                          return redirect()->back();
        //--- Redirect Section Ends
                        }
                        else
                        {
                          \Session::put('error','You Can\'t Add More Product.');
                          return redirect()->back();
        //--- Redirect Section
        // return response()->json(array('errors' => [ 0 => 'You Can\'t Add More Product.']));

        //--- Redirect Section Ends
                        }

                      }

    //*** GET Request
                      public function edit($id,$storename)
                      {
                        $cats = Category::where('storename',$storename)->get();
                        $data = Product::findOrFail($id);
                        $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();


                        if($data->type == 'Digital')
                          return view('vendor.product.edit.digital',compact('cats','data','sign'));
                        elseif($data->type == 'License')
                          return view('vendor.product.edit.license',compact('cats','data','sign'));
                        else
                          return view('vendor.product.edit.physical',compact('cats','data','sign'));
                      }


    //*** GET Request CATALOG
                      public function catalogedit($storename,$id)
                      {
                        $cats = Category::where('storename',$storename)->get();
                        $data = Product::findOrFail($id);
                        $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();


                        if($data->type == 'Digital')
                          return view('vendor.product.edit.catalog.digital',compact('cats','data','sign','storename'));
                        elseif($data->type == 'License')
                          return view('vendor.product.edit.catalog.license',compact('cats','data','sign','storename'));
                        else
                          return view('vendor.product.edit.catalog.physical',compact('cats','data','sign','storename'));
                      }

    //*** POST Request
                      public function update(Request $request,$storename,$id)
                      {
                        $this->validate($request,[
                          'photo'      => 'mimes:jpeg,jpg,png,svg',
                          'file'       => 'mimes:zip'
                        ]);
        //--- Validation Section
        // $rules = [
        //        'photo'      => 'mimes:jpeg,jpg,png,svg',
        //        'file'       => 'mimes:zip'
        //         ];

        // $validator = Validator::make(Input::all(), $rules);

        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends




        //-- Logic Section
                        $data = Product::findOrFail($id);
                        $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                        $input = $request->all();
                        if ($file = $request->file('photo')) 
                        {              
                          $name = time().$file->getClientOriginalName();
                          $file->move('assets/images/products',$name);
                          if($data->photo != null)
                          {
                            if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                    // unlink(public_path().'/assets/images/products/'.$data->photo);
                            }
                          }            
                          $input['photo'] = $name;
                        } 
            //Check Types
                        if($request->type_check == 1)
                        {
                          $input['link'] = null;
                        }
                        else
                        {
                          if($data->file!=null){
                            if (file_exists(public_path().'/assets/files/'.$data->file)) {
                        // unlink(public_path().'/assets/files/'.$data->file);
                            }
                          }
                          $input['file'] = null;
                        }


            // Check Physical
                        if($data->type == "Physical")
                        {
                          // $this->validate($request,[
                          //   'sku' => 'unique:products,sku,'.$id
                          // ]);
                    //--- Validation Section
                    // $rules = ['sku' => 'min:8|unique:products,sku,'.$id];

                    // $validator = Validator::make(Input::all(), $rules);

                    // if ($validator->fails()) {
                    //     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    // }
                    //--- Validation Section Ends

                        // Check Condition
                          if ($request->product_condition_check == ""){
                            $input['product_condition'] = 0;
                          }

                        // Check Shipping Time
                          if ($request->shipping_time_check == ""){
                            $input['ship'] = null;
                          }

                        // Check Size

                          if(empty($request->size_check ))
                          {
                            $input['size'] = null;
                            $input['size_qty'] = null;
                            $input['size_price'] = null;
                          }
                          else{
                            if(in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price))
                            {
                              $input['size'] = null;
                              $input['size_qty'] = null;
                              $input['size_price'] = null;
                            }
                            else
                            {
                              $input['size'] = implode(',', $request->size);
                              $input['size_qty'] = implode(',', $request->size_qty);
                              $input['size_price'] = implode(',', $request->size_price);
                            }
                          }

                        // Check Whole Sale
                          if(empty($request->whole_check ))
                          {
                            $input['whole_sell_qty'] = null;
                            $input['whole_sell_discount'] = null;
                          }
                          else{
                            if(in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount))
                            {
                              $input['whole_sell_qty'] = null;
                              $input['whole_sell_discount'] = null;
                            }
                            else
                            {
                              $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                              $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                            }
                          }

                        // Check Color
                          if(empty($request->color_check ))
                          {
                            $input['color'] = null;
                          }
                          else{
                            if (!empty($request->color))
                            {
                              $input['color'] = implode(',', $request->color);
                            }
                            if (empty($request->color))
                            {
                              $input['color'] = null;
                            }
                          }

                        // Check Measure
                          if ($request->measure_check == "")
                          {
                            $input['measure'] = null;
                          }
                        }


            // Check Seo
                        if (empty($request->seo_check))
                        {
                          $input['meta_tag'] = null;
                          $input['meta_description'] = null;
                        }
                        else {
                          if (!empty($request->meta_tag))
                          {
                            $input['meta_tag'] = implode(',', $request->meta_tag);
                          }
                        }

        // Check License
                        if($data->type == "License")
                        {

                          if(!in_array(null, $request->license) && !in_array(null, $request->license_qty))
                          {
                            $input['license'] = implode(',,', $request->license);
                            $input['license_qty'] = implode(',', $request->license_qty);
                          }
                          else
                          {
                            if(in_array(null, $request->license) || in_array(null, $request->license_qty))
                            {
                              $input['license'] = null;
                              $input['license_qty'] = null;
                            }
                            else
                            {
                              $license = explode(',,', $prod->license);
                              $license_qty = explode(',', $prod->license_qty);
                              $input['license'] = implode(',,', $license);
                              $input['license_qty'] = implode(',', $license_qty);
                            }
                          }

                        }
            // Check Features
                        if(!in_array(null, $request->features) && !in_array(null, $request->colors))
                        {
                          $input['features'] = implode(',', str_replace(',',' ',$request->features));
                          $input['colors'] = implode(',', str_replace(',',' ',$request->colors));
                        }
                        else
                        {
                          if(in_array(null, $request->features) || in_array(null, $request->colors))
                          {
                            $input['features'] = null;
                            $input['colors'] = null;
                          }
                          else
                          {
                            $features = explode(',', $data->features);
                            $colors = explode(',', $data->colors);
                            $input['features'] = implode(',', $features);
                            $input['colors'] = implode(',', $colors);
                          }
                        }

        //Product Tags
                        if (!empty($request->tags))
                        {
                          $input['tags'] = implode(',', $request->tags);
                        }
                        if (empty($request->tags))
                        {
                          $input['tags'] = null;
                        }

                        $input['price'] = $input['price'] / $sign->value;
                        $input['previous_price'] = $input['previous_price'] / $sign->value;

         // store filtering attributes for physical product
                        $attrArr = [];
                        if (!empty($request->category_id)) {
                         $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('storename',$storename)->where('attributable_type', 'App\Models\Category')->get();
                         if (!empty($catAttrs)) {
                           foreach ($catAttrs as $key => $catAttr) {
                             $in_name = $catAttr->input_name;
                             if ($request->has("$in_name")) {
                               $attrArr["$in_name"]["values"] = $request["$in_name"];
                               $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                               if ($catAttr->details_status) {
                                 $attrArr["$in_name"]["details_status"] = 1;
                               } else {
                                 $attrArr["$in_name"]["details_status"] = 0;
                               }
                             }
                           }
                         }
                       }

                       if (!empty($request->subcategory_id)) {
                         $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->where('storename',$storename)->get();
                         if (!empty($subAttrs)) {
                           foreach ($subAttrs as $key => $subAttr) {
                             $in_name = $subAttr->input_name;
                             if ($request->has("$in_name")) {
                               $attrArr["$in_name"]["values"] = $request["$in_name"];
                               $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                               if ($subAttr->details_status) {
                                 $attrArr["$in_name"]["details_status"] = 1;
                               } else {
                                 $attrArr["$in_name"]["details_status"] = 0;
                               }
                             }
                           }
                         }
                       }
                       if (!empty($request->childcategory_id)) {
                         $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->where('storename',$storename)->get();
                         if (!empty($childAttrs)) {
                           foreach ($childAttrs as $key => $childAttr) {
                             $in_name = $childAttr->input_name;
                             if ($request->has("$in_name")) {
                               $attrArr["$in_name"]["values"] = $request["$in_name"];
                               $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                               if ($childAttr->details_status) {
                                 $attrArr["$in_name"]["details_status"] = 1;
                               } else {
                                 $attrArr["$in_name"]["details_status"] = 0;
                               }
                             }
                           }
                         }
                       }



                       if (empty($attrArr)) {
                         $input['attributes'] = NULL;
                       } else {
                         $jsonAttr = json_encode($attrArr);
                         $input['attributes'] = $jsonAttr;
                       }

                       if($data->type != 'Physical'){
                        $data->slug = str_slug($data->name,'-').'-'.strtolower(str_random(3).$data->id.str_random(3));
                      }
                      else {
                        $data->slug = str_slug($data->name,'-').'-'.strtolower($data->sku);
                      }

                      $data->update($input);
        //-- Logic Section Ends

                      $prod = Product::find($data->id);
                // Set Photo
                      $newimg = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, 800);
                      $photo = time().str_random(8).'.jpg';
                      $newimg->save(public_path().'/assets/images/products/'.$photo);


                // Set Thumbnail
                      $img = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(285, 285);
                      $thumbnail = time().str_random(8).'.jpg';
                      $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                      $prod->thumbnail  = $thumbnail;
                      $prod->photo  = $photo;
                      $prod->update();





        //--- Redirect Section
                      $msg = 'Product Updated Successfully.<a href="'.route('vendor-prod-index',$storename).'">View Product Lists.</a>';
                      \Session::put('success',$msg);
                      return redirect()->back();
        //--- Redirect Section Ends
                    }

    //*** POST Request CATALOG
                    public function catalogupdate(Request $request,$storename,$id)
                    {

                      $user = Auth::user();
                      $package = $user->subscribes()->where('storename',$storename)->orderBy('id','desc')->first();
                      $prods = $user->products()->where('storename',$storename)->orderBy('id','desc')->get()->count();

                      if($prods < $package->allowed_products || $package->allowed_products == 0)
                      {
                        $this->validate($request,[
                          'photo'      => 'mimes:jpeg,jpg,png,svg',
                          'file'       => 'mimes:zip'
                        ]);
        //---    Validation Section
        // $rules = [
        //     'photo'      => 'mimes:jpeg,jpg,png,svg',
        //     'file'       => 'mimes:zip'
        //      ];

        // $validator = Validator::make(Input::all(), $rules);

        // if ($validator->fails()) {
        //   return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends




        //--- Logic Section
                        $data = new Product;
                        $sign = Currency::where([['is_default','=',1],['storename',$storename]])->first();
                        $input = $request->all();
            // Check File

                        if ($file = $request->file('file'))
                        {
                          $name = time().$file->getClientOriginalName();
                          $file->move('assets/files',$name);
                          $input['file'] = $name;
                        }

                        $image_name = '';
                        if($request->is_photo == '1')
                        {
                          if ($file = $request->file('photo')) 
                          {      
                           $name = time().$file->getClientOriginalName();
                           $file->move('assets/images/products',$name);           
                           $image_name = $name;
                         } 

                       }
                       else {
                         $image_name = $request->image_name;
                       }

                       $input['photo'] = $image_name;

            // Check Physical
                       if($request->type == "Physical")
                       {
                        $this->validate($request,[
                          // 'sku'      => 'min:8|unique:products'
                        ]);
                    //--- Validation Section
                    // $rules = ['sku'      => 'min:8|unique:products'];

                    // $validator = Validator::make(Input::all(), $rules);

                    // if ($validator->fails()) {
                    //     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    // }
                    //--- Validation Section Ends


            // Check Condition
                        if ($request->product_condition_check == ""){
                          $input['product_condition'] = 0;
                        }

            // Check Shipping Time
                        if ($request->shipping_time_check == ""){
                          $input['ship'] = null;
                        }

            // Check Size
                        if(empty($request->size_check ))
                        {
                          $input['size'] = null;
                          $input['size_qty'] = null;
                          $input['size_price'] = null;
                        }
                        else{
                          if(in_array(null, $request->size) || in_array(null, $request->size_qty))
                          {
                            $input['size'] = null;
                            $input['size_qty'] = null;
                            $input['size_price'] = null;
                          }
                          else
                          {
                            $input['size'] = implode(',', $request->size);
                            $input['size_qty'] = implode(',', $request->size_qty);
                            $input['size_price'] = implode(',', $request->size_price);
                          }
                        }

            // Check Whole Sale
                        if(empty($request->whole_check ))
                        {
                          $input['whole_sell_qty'] = null;
                          $input['whole_sell_discount'] = null;
                        }
                        else{
                          if(in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount))
                          {
                            $input['whole_sell_qty'] = null;
                            $input['whole_sell_discount'] = null;
                          }
                          else
                          {
                            $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                            $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                          }
                        }


            // Check Color
                        if(empty($request->color_check))
                        {
                          $input['color'] = null;
                        }
                        else{
                          $input['color'] = implode(',', $request->color);
                        }

            // Check Measurement
                        if ($request->mesasure_check == "")
                        {
                          $input['measure'] = null;
                        }

                      }

            // Check Seo
                      if (empty($request->seo_check))
                      {
                        $input['meta_tag'] = null;
                        $input['meta_description'] = null;
                      }
                      else {
                        if (!empty($request->meta_tag))
                        {
                          $input['meta_tag'] = implode(',', $request->meta_tag);
                        }
                      }

             // Check License

                      if($request->type == "License")
                      {

                        if(in_array(null, $request->license) || in_array(null, $request->license_qty))
                        {
                          $input['license'] = null;
                          $input['license_qty'] = null;
                        }
                        else
                        {
                          $input['license'] = implode(',,', $request->license);
                          $input['license_qty'] = implode(',', $request->license_qty);
                        }

                      }

             // Check Features
                      if(in_array(null, $request->features) || in_array(null, $request->colors))
                      {
                        $input['features'] = null;
                        $input['colors'] = null;
                      }
                      else
                      {
                        $input['features'] = implode(',', str_replace(',',' ',$request->features));
                        $input['colors'] = implode(',', str_replace(',',' ',$request->colors));
                      }

            //tags
                      if (!empty($request->tags))
                      {
                        $input['tags'] = implode(',', $request->tags);
                      }

            // Conert Price According to Currency
                      $input['price'] = ($input['price'] / $sign->value);
                      $input['previous_price'] = ($input['previous_price'] / $sign->value);
                      $input['user_id'] = Auth::user()->id;

             // store filtering attributes for physical product
                      $attrArr = [];
                      if (!empty($request->category_id)) {
                       $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('storename',$storename)->where('attributable_type', 'App\Models\Category')->get();
                       if (!empty($catAttrs)) {
                         foreach ($catAttrs as $key => $catAttr) {
                           $in_name = $catAttr->input_name;
                           if ($request->has("$in_name")) {
                             $attrArr["$in_name"]["values"] = $request["$in_name"];
                             $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                             if ($catAttr->details_status) {
                               $attrArr["$in_name"]["details_status"] = 1;
                             } else {
                               $attrArr["$in_name"]["details_status"] = 0;
                             }
                           }
                         }
                       }
                     }

                     if (!empty($request->subcategory_id)) {
                       $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('storename',$storename)->where('attributable_type', 'App\Models\Subcategory')->get();
                       if (!empty($subAttrs)) {
                         foreach ($subAttrs as $key => $subAttr) {
                           $in_name = $subAttr->input_name;
                           if ($request->has("$in_name")) {
                             $attrArr["$in_name"]["values"] = $request["$in_name"];
                             $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                             if ($subAttr->details_status) {
                               $attrArr["$in_name"]["details_status"] = 1;
                             } else {
                               $attrArr["$in_name"]["details_status"] = 0;
                             }
                           }
                         }
                       }
                     }
                     if (!empty($request->childcategory_id)) {
                       $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('storename',$storename)->where('attributable_type', 'App\Models\Childcategory')->get();
                       if (!empty($childAttrs)) {
                         foreach ($childAttrs as $key => $childAttr) {
                           $in_name = $childAttr->input_name;
                           if ($request->has("$in_name")) {
                             $attrArr["$in_name"]["values"] = $request["$in_name"];
                             $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                             if ($childAttr->details_status) {
                               $attrArr["$in_name"]["details_status"] = 1;
                             } else {
                               $attrArr["$in_name"]["details_status"] = 0;
                             }
                           }
                         }
                       }
                     }



                     if (empty($attrArr)) {
                       $input['attributes'] = NULL;
                     } else {
                       $jsonAttr = json_encode($attrArr);
                       $input['attributes'] = $jsonAttr;
                     }

            // Save Data
                     $data->fill($input)->save();

            // Set SLug

                     $prod = Product::find($data->id);
                     if($prod->type != 'Physical'){
                      $prod->slug = str_slug($data->name,'-').'-'.strtolower(str_random(3).$data->id.str_random(3));
                    }
                    else {
                      $prod->slug = str_slug($data->name,'-').'-'.strtolower($data->sku);
                    }
                    $photo = $prod->photo;

                // Set Photo
                    $newimg = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, 800);
                    $photo = time().str_random(8).'.jpg';
                    $newimg->save(public_path().'/assets/images/products/'.$photo);


                // Set Thumbnail
                    $img = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(285, 285);
                    $thumbnail = time().str_random(8).'.jpg';
                    $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                    $prod->thumbnail  = $thumbnail;
                    $prod->photo  = $photo;
                    $prod->update();

            // Add To Gallery If any
                    $lastid = $data->id;
                    if ($files = $request->file('gallery')){
                      foreach ($files as  $key => $file){
                        if(in_array($key, $request->galval))
                        {
                          $gallery = new Gallery;
                          $name = time().$file->getClientOriginalName();
                          $img = Image::make($file->getRealPath())->resize(800, 800);
                          $thumbnail = time().str_random(8).'.jpg';
                          $img->save(public_path().'/assets/images/galleries/'.$name);
                          $gallery['photo'] = $name;
                          $gallery['product_id'] = $lastid;
                          $gallery->save();
                        }
                      }
                    }
        //logic Section Ends

        //--- Redirect Section
                    $msg = 'New Product Added Successfully.<a href="'.route('vendor-prod-index',$storename).'">View Product Lists.</a>';
                    \Session::put('success',$msg);
                    return redirect()->back();
        //--- Redirect Section Ends
                  }
                  else
                  {
          //--- Redirect Section
                    \Session::put('error','You Can\'t Add More Product.');
                    return redirect()->back();
          // return response()->json(array('errors' => [ 0 => 'You Can\'t Add More Product.']));

          //--- Redirect Section Ends
                  }
                }


    //*** GET Request
                public function destroy($storename,$id)
                {

                  $data = Product::findOrFail($id);
                  if($data->galleries->count() > 0)
                  {
                    foreach ($data->galleries as $gal) {
                      if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
                        // unlink(public_path().'/assets/images/galleries/'.$gal->photo);
                      }
                      $gal->delete();
                    }

                  }

                  if($data->ratings->count() > 0)
                  {
                    foreach ($data->ratings  as $gal) {
                      $gal->delete();
                    }
                  }
                  if($data->wishlists->count() > 0)
                  {
                    foreach ($data->wishlists as $gal) {
                      $gal->delete();
                    }
                  }
                  if($data->clicks->count() > 0)
                  {
                    foreach ($data->clicks as $gal) {
                      $gal->delete();
                    }
                  }
                  if($data->comments->count() > 0)
                  {
                    foreach ($data->comments as $gal) {
                      if($gal->replies->count() > 0)
                      {
                        foreach ($gal->replies as $key) {
                          $key->delete();
                        }
                      }
                      $gal->delete();
                    }
                  }

                  if (!filter_var($data->photo,FILTER_VALIDATE_URL)){
                    if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                // unlink(public_path().'/assets/images/products/'.$data->photo);
                    }
                  }

                  if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail) && $data->thumbnail != "") {
            // unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
                  }
                  if($data->file != null){
                    if (file_exists(public_path().'/assets/files/'.$data->file)) {
                // unlink(public_path().'/assets/files/'.$data->file);
                    }
                  }
                  $data->delete();
        //--- Redirect Section
                  $msg = 'Product Deleted Successfully.';
                  \Session::put('success',$msg);
                  return redirect()->back();
        //--- Redirect Section Ends

// PRODUCT DELETE ENDS
                }

                public function getAttributes(Request $request,$storename) {
                  $model = '';
                  if ($request->type == 'category') {
                    $model = 'App\Models\Category';
                  } elseif ($request->type == 'subcategory') {
                    $model = 'App\Models\Subcategory';
                  } elseif ($request->type == 'childcategory') {
                    $model = 'App\Models\Childcategory';
                  }

                  $attributes = Attribute::where('attributable_id', $request->id)->where('storename',$storename)->where('attributable_type', $model)->get();
                  $attrOptions = [];
                  foreach ($attributes as $key => $attribute) {
                    $options = AttributeOption::where('attribute_id', $attribute->id)->where('storename',$storename)->get();
                    $attrOptions[] = ['attribute' => $attribute, 'options' => $options];
                  }
                  return response()->json($attrOptions);
                }
              }