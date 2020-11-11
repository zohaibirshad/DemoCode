<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Generalsetting;
use App\Models\Currency;
use Illuminate\Support\Facades\Session;
use Route;

class Product extends Model
{
    // public $storename = 'andy';

    protected $fillable = ['user_id','category_id','product_type','affiliate_link','sku', 'subcategory_id', 'childcategory_id', 'attributes', 'name', 'photo', 'size','size_qty','size_price', 'color', 'details','price','previous_price','stock','policy','status', 'views','tags','featured','best','top','hot','latest','big','trending','sale','features','colors','product_condition','ship','meta_tag','meta_description','youtube','type','file','license','license_qty','link','platform','region','licence_type','measure','discount_date','is_discount','whole_sell_qty','whole_sell_discount','catalog_id','slug'];

    public static function filterProducts($collection)
    {
        // dd($collection);
        foreach ($collection as $key => $data) {
            if($data->user_id != 0){
                if($data->user->is_vendor != 2){
                    unset($collection[$key]);
                }
            }
            if(isset($_GET['max'])){
                 if($data->vendorSizePrice($data->storename) >= $_GET['max']) {
                    unset($collection[$key]);
                }
            }
            $data->price = $data->vendorSizePrice($data->storename);
        }
        return $collection;
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Models\Childcategory')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report','user_id');
    }

    public function vendorPrice($storename) {
        // $gs = Generalsetting::findOrFail(1);
        // $gs = Generalsetting::where('storename',$this->storename)->first();
        $gs = Generalsetting::where('storename',$storename)->first();

        // dd($gs);
        // dd($gs);
        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }


        return $price;
    }

    public function vendorSizePrice($storename) {
        // $gs = Generalsetting::findOrFail(1);
        // $gs = Generalsetting::where('storename',$this->storename)->first();
        // dd($gs);
        $gs = Generalsetting::where('storename',$storename)->first();

        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }

      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }


    // Attribute Section Ends


        return $price;
    }


    public  function setCurrency($storename) {
        // $gs = Generalsetting::findOrFail(1);
        // $gs = Generalsetting::where('storename',$this->storename)->first();
        $gs = Generalsetting::where('storename',$storename)->first();

        // dd($gs);
        $price = $this->price;
        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }


    public function showPrice($storename) {
        // $gs = Generalsetting::findOrFail(1);
        // $gs = Generalsetting::where('storename',$this->storename)->first();
        $gs = Generalsetting::where('storename',$storename)->first();

        // dd($storename);
        // dd($gs);
        $price = $this->price;

        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }
      // dd($attrArr);
      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                    if($attrVal['prices'])
                      $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }


    // Attribute Section Ends


        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
 


        $price = round(($price) * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showPreviousPrice($storename) {
        // $gs = Generalsetting::findOrFail(1);
        $gs = Generalsetting::where('storename',$storename)->first();

        // $gs = Generalsetting::where('storename',$this->storename)->first();
        // dd($gs);
        $price = $this->previous_price;
        if(!$price){
            return '';
        }
        if($this->user_id != 0){
        $price = $this->previous_price + $gs->fixed_commission + ($this->previous_price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }
      // dd($attrArr);
      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }


    // Attribute Section Ends


        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function convertPrice($storename,$price) {
        // $gs = Generalsetting::findOrFail(1);
        $gs = Generalsetting::where('storename',$storename)->first();

        // $gs = Generalsetting::where('storename',$this->storename)->first();
        // dd($gs);
        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function vendorConvertPrice($storename,$price) {
        // $gs = Generalsetting::findOrFail(1);
        $gs = Generalsetting::where('storename',$storename)->first();

        // $gs = Generalsetting::where('storename',$this->storename)->first();
        // dd($gs);

        $curr = Currency::where('is_default','=',1)->first();
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function convertPreviousPrice($storename,$price) {
        // $gs = Generalsetting::findOrFail(1);
        $gs = Generalsetting::where('storename',$storename)->first();

        // $gs = Generalsetting::where('storename',$this->storename)->first();
        // dd($gs);
        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showName() {
        $name = mb_strlen($this->name,'utf-8') > 55 ? mb_substr($this->name,0,55,'utf-8').'...' : $this->name;
        return $name;
    }


    public function emptyStock() {
        $stck = (string)$this->stock;
        if($stck == "0"){
            return true;            
        }
    }

    public static function showTags() {
        $tags = null;
        $tagz = '';
        $name = Product::where('status','=',1)->pluck('tags')->toArray();
        foreach($name as $nm)
        {
            if(!empty($nm))
            {
                foreach($nm as $n)
                {
                    $tagz .= $n.',';
                }
            }
        }
        $tags = array_unique(explode(',',$tagz));
        return $tags;
    }


    public function getSizeAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizeQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }


}
