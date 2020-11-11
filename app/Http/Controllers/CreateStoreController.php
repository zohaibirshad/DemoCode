<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Models\Admin;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Seotool;
use App\Models\BlogCategory;
use App\Models\Socialsetting;
use App\Models\Currency;
use App\Models\AdminLanguage;
use App\Models\Language;
use App\Models\Generalsetting;
use App\Models\Pagesetting;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Slider;
use Hash;
class CreateStoreController extends Controller
{
  public $password = '';
  public function index(){

  }

  public function CreateStore(Request $request){
    $data= file_get_contents("https://members.shopypall.com/license-key?license_key='".$request->l_key."'");
    
        if($data == "1"){
            $license_key = Store::where('license_key',$request->l_key)->where('key_verified',true)->first();
            
            if(count($license_key) == 0){
              $store = new Store;
              $store->storename = $request->storename;
              $store->email = $request->email;
              $store->phone = $request->phone;
              $store->password = $request->password;
              $store->license_key= $request->l_key;
              $store->key_verified=true;
              $store->save();
              $license_key = Store::where('license_key',$request->l_key)->where('key_verified',true)->first();
              $this->createAnAdmin($license_key);
              
              $this->sendStoreSuccessEmail($request->l_key,$request->email,$request->password);
                
              echo "Your Store has been created";
            }else{
                echo "Key Already in used";
            }
    }
    else{
        echo "invalid license key";
    }
    // dd("working");
    
 }

 public function VerifyKey(Request $request)
 {   
  $license_key = Store::where('license_key',$request->l_key)->where('key_verified',false)->first();

  if($license_key){
    $this->sendStoreSuccessEmail($license_key->storename,$license_key->email,$license_key->password);
    Store::where('license_key',$request->l_key)
    ->update(['key_verified' => true]);
    $this->createAnAdmin($license_key);

    return 'true';
  }else{
    $license_key = Store::where([['license_key',$request->l_key],['key_verified',0]])->first();
    if($license_key){
      $this->sendStoreSuccessEmail($license_key->storename,$license_key->email,$license_key->password);
      Store::where('license_key',$request->l_key)
      ->update(['key_verified' => true]);
      $this->createAnAdmin($license_key);

      return 'true';
    }else
    {
      return 'false';
    }

    return 'false';
  }
}


public function checkStoreName(Request $request){
  $duplicate = Store::where('storename',$request->storename)->count();
  if($duplicate > 0){
    return 'true';
  }else{
   return 'false';
 }
}


public function createAnAdmin($adminData){
  $admin = new Admin;
  $admin->storename=$adminData->storename;
  $admin->name= $adminData->storename;
  $admin->email=$adminData->email;
  $admin->phone=$adminData->phone;
  $admin->role_id=0;
  $admin->photo='1556780563user.png';
  $admin->password=Hash::make($adminData->password);
  $admin->status=1;
  $admin->save();
  $admin->shop_name=$adminData->storename;

  $this->createCategory($adminData->storename);
  $this->createCurrency($adminData->storename);
  $this->createAdminLanguage($adminData->storename);
  $this->createLanguage($adminData->storename);
  $this->genrealSettingCreate($adminData->storename);
  $this->pageSettingCreate($adminData->storename);
  $this->createEmailTemplate($adminData->storename);
  // $this->createProducts($adminData->storename);
  $this->createBanners($adminData->storename);
  $this->createSliders($adminData->storename);
  
  // $this->createsubcategory($adminData->storename);
  // $this->createchildcategory($adminData->storename);
  $this->createPage($adminData->storename);
  $this->createBlog($adminData->storename);
  $this->seoTool($adminData->storename);
  $this->socialSetting($adminData->storename);

}

public function createCurrency($storename){
  $currency = new  Currency;
  $currency->storename = $storename;
  $currency->name ='USD';
  $currency->sign ='$';
  $currency->value =1;
  $currency->is_default =1;
  $currency->save();
}

public function createAdminLanguage($storename){
  $adminlanguage = new AdminLanguage;
  $adminlanguage->storename = $storename;
  $adminlanguage->is_default = 1;
  $adminlanguage->language = 'English';
  $adminlanguage->file = '1567232745AoOcvCtY.json';
  $adminlanguage->name = '1567232745AoOcvCtY';
  $adminlanguage->rtl = '0';
  $adminlanguage->save();
}

public function createLanguage($storename){
  $language = new Language;
  $language->storename = $storename;
  $language->is_default = 1;
  $language->language = 'English';
  $language->file = '1579762052FstnupIm.json';
  $language->save();
}

public function genrealSettingCreate($storename){
  $gs = Generalsetting::find(1);
  $newgs = $gs->replicate();
  $newgs->storename = $storename;
  $newgs->save();
}

public function pageSettingCreate($storename){
  $gs = Pagesetting::find(1);
  $newgs = $gs->replicate();
  $newgs->storename = $storename;
  $newgs->save();
}

public function createEmailTemplate($storename){
  $etemplate = EmailTemplate::whereIn('id',[1,2,3,4,5])->get();
  foreach($etemplate as $etemp){
    $newgs = $etemp->replicate();
    $newgs->storename = $storename;
    $newgs->save();
  }

}

public function createBanners($storename){
  $banners = Banner::where('storename','adeel')->get();
  foreach($banners as $banner){
    $newbanner = $banner->replicate();
    $newbanner->storename = $storename;
    $newbanner->save();
  }

}


public function createSliders($storename){
  $banners = Slider::where('storename','adeel')->get();
  foreach($banners as $banner){
    $newbanner = $banner->replicate();
    $newbanner->storename = $storename;
    $newbanner->save();
  }

}

public function createProducts($storename){
  $products = Product::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }

}


public function createCategory($storename){
  $products = Category::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();
  }

  $sub_cates = Subcategory::where('storename','adeel')->get();
  foreach($sub_cates as $sub_cate){
    $newproduct = $sub_cate->replicate();
    $newproduct->storename = $storename;
    $newproduct->save();

  }

  $category = Category::where('storename',$storename)->where('slug','electric')->first();
  $sub_cates_4 = Subcategory::where('storename',$storename)->where('category_id',4)->get();

  foreach($sub_cates_4 as $sub_cate){
    $sub_cate->category_id = $category->id;
    $sub_cate->save();

  }

  $category = Category::where('storename',$storename)->where('slug','fashion-and-Beauty')->first();
  $sub_cates_5 = Subcategory::where('storename',$storename)->where('category_id',5)->get();

  foreach($sub_cates_5 as $sub_cate){
    $sub_cate->category_id = $category->id;
    $sub_cate->save();

  }

  $category = Category::where('storename',$storename)->where('slug','camera-and-photo')->first();
  $sub_cates_6 = Subcategory::where('storename',$storename)->where('category_id',6)->get();

  foreach($sub_cates_6 as $sub_cate){
    $sub_cate->category_id = $category->id;
    $sub_cate->save();

  }

  $category = Category::where('storename',$storename)->where('slug','smart-phone-and-table')->first();
  $sub_cates_7 = Subcategory::where('storename',$storename)->where('category_id',7)->get();

  foreach($sub_cates_7 as $sub_cate){
    $sub_cate->category_id = $category->id;
    $sub_cate->save();

  }

  //clone child categories
  $child_categories = Childcategory::where('storename','adeel')->get();
  foreach($child_categories as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }

  $sub_category = Subcategory::where('storename',$storename)->where('slug','television')->first();
  $child_cates_2 = Childcategory::where('storename',$storename)->where('subcategory_id',2)->get();

  foreach($child_cates_2 as $child_cate){
    $child_cate->subcategory_id = $sub_category->id;
    $child_cate->save();

  }

  $sub_category = Subcategory::where('storename',$storename)->where('slug','refrigerator')->first();
  $child_cates_3 = Childcategory::where('storename',$storename)->where('subcategory_id',3)->get();

  foreach($child_cates_3 as $child_cate){
    $child_cate->subcategory_id = $sub_category->id;
    $child_cate->save();

  }

  $sub_category = Subcategory::where('storename',$storename)->where('slug','washing-machine')->first();
  $child_cates_4 = Childcategory::where('storename',$storename)->where('subcategory_id',4)->get();

  foreach($child_cates_4 as $child_cate){
    $child_cate->subcategory_id = $sub_category->id;
    $child_cate->save();

  }

  $sub_category = Subcategory::where('storename',$storename)->where('slug','air-conditioners')->first();
  $child_cates_5 = Childcategory::where('storename',$storename)->where('subcategory_id',5)->get();

  foreach($child_cates_5 as $child_cate){
    $child_cate->subcategory_id = $sub_category->id;
    $child_cate->save();

  }


  $new_cat_4 = Category::where('storename',$storename)->where('slug','electric')->first();
  $new_cat_5 = Category::where('storename',$storename)->where('slug','fashion-and-Beauty')->first();
  $new_cat_8 = Category::where('storename',$storename)->where('slug','sport-and-Outdoor')->first();
  $new_cat_11 = Category::where('storename',$storename)->where('slug','books-and-office')->first();

  $new_sub_cat_2 = Subcategory::where('storename',$storename)->where('slug','television')->first();
  $new_sub_cat_6 = Subcategory::where('storename',$storename)->where('slug','accessories')->first();
  $new_sub_cat_7 = Subcategory::where('storename',$storename)->where('slug','bags')->first();
  $new_sub_cat_9 = Subcategory::where('storename',$storename)->where('slug','shoes')->first();

  $new_child_cat_1 = Childcategory::where('storename',$storename)->where('slug','lcd-tv')->first();
  //clone Products

  $products = Product::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();

    if($newproduct->category_id == 4)
    {
      $newproduct->category_id = $new_cat_4->id;
    }
    elseif($newproduct->category_id == 5)
    {
      $newproduct->category_id = $new_cat_5->id;
    }
    elseif($newproduct->category_id == 8)
    {
      $newproduct->category_id = $new_cat_8->id;
    }
    elseif($newproduct->category_id == 11)
    {
      $newproduct->category_id = $new_cat_11->id;
    }

    if($newproduct->subcategory_id == 2)
    {
      $newproduct->subcategory_id = $new_sub_cat_2->id;
    }
    elseif($newproduct->subcategory_id == 6)
    {
      $newproduct->subcategory_id = $new_sub_cat_6->id;
    }
    elseif($newproduct->subcategory_id == 7)
    {
      $newproduct->subcategory_id = $new_sub_cat_7->id;
    }
    elseif($newproduct->subcategory_id == 9)
    {
      $newproduct->subcategory_id = $new_sub_cat_9->id;
    }

    if($newproduct->childcategory_id == 1)
    {
      $newproduct->childcategory_id = $new_child_cat_1->id;
    }
    $newproduct->storename =$storename;
    $newproduct->save();

  }




}

public function seoTool($storename)
{
  $products = Seotool::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }
}
public function socialSetting($storename)
{
  $products = Socialsetting::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }
}
public function createsubcategory($storename)
{
  $products = Subcategory::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }
}

public function createchildcategory($storename){
  $products = Childcategory::where('storename','adeel')->get();
  foreach($products as $product){
    $newproduct = $product->replicate();
    $newproduct->storename =$storename;
    $newproduct->save();

  }

}

  public function createPage($storename){
    $pages = Page::where('storename','adeel')->get();
    foreach($pages as $page){
      $newproduct = $page->replicate();
      $newproduct->storename =$storename;
      $newproduct->save();
    }

  }

  public function createBlog($storename){
    $blog_categories = BlogCategory::where('storename','adeel')->get();
    foreach($blog_categories as $blog_category)
    {
      $newblogcat = $blog_category->replicate();
      $newblogcat->storename = $storename;
      $newblogcat->save();

      $blog_id = $newblogcat->id;
    }

    $blogs = Blog::where('storename','adeel')->get();
    foreach($blogs as $blog){
      $newproduct = $blog->replicate();
      $newproduct->category_id =$blog_id;
      $newproduct->storename =$storename;
      $newproduct->save();

  }

}

public function sendLicenseKeyEmail($licenseKey,$username,$email){
  $to = $email;
  $subject = "License Key";

  $message = "
  <b>Hi ".$username."</b>
  <p>You are are one step away to create a store with <a href='https://www.shopypall.com/'>https://www.shopypall.com/</a> </p>
  <p>Please find the License Key sent from https://www.shopypall.com/ and varify to start your store </p>
  <br />
  Use your personal License Key : ".$licenseKey."

  <br />
  <b> 
  <p> =========================== </p>
  Create your Shopypall Mega Store using the link below
  https://www.shopypall.com/store/create_store <br />

  Please, don't reply to this email as it is automated.
  </b>
  <br />
  To Your Success, <br />
  The Shopypall Team
  ";

        // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
  $headers .= 'From: noReply@shopypall.com' . "\r\n";

  mail($to,$subject,$message,$headers);
}


public function sendStoreSuccessEmail($username,$email,$password){
  $to = $email;
  $subject = "License Key";
  $message = "
  <b>Hi ".$username."</b>
  Congratulations! and thank you for being a part of <b> Shopypall.</b><br />

  Please whitelist our email by adding this to your address book. This makes sure you get all your important updates and you can contact the support team easily.

  <p><b>Your Login Details - </b></p>
  <p> Please keep these login details safe as they are your keys to the software and your member area: </p>
  <p><b>
  Admin Area - https://www.shopypall.com/store/".$username."/admin/login  <br />
  Email - ".$email." <br />
  Password - ".$password."
  </b></p>
  <br />
  <b> 
  <p> =========================== </p>
  Create your Shopypall Mega Store using the link below
  https://www.shopypall.com/store/create_store

  Please, don't reply to this email as it is automated.
  </b>
  <br />
  To Your Success, <br />
  The Shopypall Team
  ";

        // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
  $headers .= 'From: noReply@shopypall.com' . "\r\n";

  mail($to,$subject,$message,$headers);
}
}
