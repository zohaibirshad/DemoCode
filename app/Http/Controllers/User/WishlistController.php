<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function wishlists(Request $request,$storename)
    {
        $sort = '';
        $user = Auth::guard('web')->user();

        // Search By Sort

        if(!empty($request->sort))
        {
        $sort = $request->sort;
        $wishes = Wishlist::where('storename',$storename)->where('user_id','=',$user->id)->pluck('product_id');
        if($sort == "date_desc")
        {
        $wishlists = Product::where('storename',$storename)->where('status','=',1)->whereIn('id',$wishes)->orderBy('id','desc')->paginate(8);
        }
        else if($sort == "date_asc")
        {
        $wishlists = Product::where('storename',$storename)->where('status','=',1)->whereIn('id',$wishes)->paginate(8);
        }
        else if($sort == "price_asc")
        {
        $wishlists = Product::where('storename',$storename)->where('status','=',1)->whereIn('id',$wishes)->orderBy('price','asc')->paginate(8);
        }
        else if($sort == "price_desc")
        {
        $wishlists = Product::where('storename',$storename)->where('status','=',1)->whereIn('id',$wishes)->orderBy('price','desc')->paginate(8);
        }
        if($request->ajax())
        {
            return view('front.pagination.wishlist',compact('user','wishlists','sort'));
        }
        return view('user.wishlist',compact('user','wishlists','sort'));
        }


        $wishlists = Wishlist::where('storename',$storename)->where('user_id','=',$user->id)->paginate(8);
        if($request->ajax())
        {
            return view('front.pagination.wishlist',compact('user','wishlists','sort'));
        }
        return view('user.wishlist',compact('user','wishlists','sort','storename'));
    }

    public function addwish($storename,$id)
    {
        $user = Auth::guard('web')->user();
        $data[0] = 0;
        $ck = Wishlist::where('storename',$storename)->where('user_id','=',$user->id)->where('product_id','=',$id)->get()->count();
        if($ck > 0)
        {
            return response()->json($data);
        }
        $wish = new Wishlist();
        $wish->user_id = $user->id;
        $wish->product_id = $id;
        $wish->storename = $storename;
        $wish->save();
        $data[0] = 1;
        $data[1] = count($user->wishlists);
        return response()->json($data);
    }

    public function removewish($storename,$id)
    {
        $user = Auth::guard('web')->user();
        $wish = Wishlist::findOrFail($id);
        $wish->delete();
        $data[0] = 1;
        $data[1] = count($user->wishlists);
        return response()->json($data);
    }

}
