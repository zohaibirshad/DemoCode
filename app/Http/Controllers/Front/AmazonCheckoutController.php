<?php



namespace App\Http\Controllers\Front;



use Illuminate\Http\Request;

use App\Classes\ShopypallMailer;

use App\Http\Controllers\Controller;

use App\Models\Notification;

use App\Models\Order;

use App\Models\OrderTrack;

use App\Models\Pagesetting;

use App\Models\UserNotification;

use App\Models\VendorOrder;

use Config;

use App\Models\Cart;

use App\Models\Product;

use App\Models\Currency;

use App\Models\Coupon;

use App\Models\Generalsetting;

use Session;

use AmazonPayment;

use Auth;

use App\Models\User;



class AmazonCheckoutController extends Controller

{

  public function __construct()

  {

        //Set Spripe Keys

    $gs = Generalsetting::where('storename',$storename);



    if (Session::has('currency')) 

    {

      $this->curr = Currency::where('storename',$storename)->find(Session::get('currency'));

    }

    else

    {

      $this->curr = Currency::where('storename',$storename)->where('is_default','=',1)->first();

    }



  }

  //

  public function index($storename){



    $accessToken = $_GET['access_token'];



    try {

          // get user details, use them if needed

      $amazonUser = AmazonPayment::getLoginDetails($accessToken);

      

    } catch (\Exception $e) {



          // Redirect back to cart page if error

      return Redirect::to('/cart')

      ->with('failure_message', 'Failed to connect to your Amazon account. Please try again.');

    }

      // dd($amazonUser['user_id']);

      // Laravel Auth example:

      // login user if their Amazon user_id is found in your users table

      // Obviously for this to work, you would have created the user entry at some other point in your app, maybe the account register page or something

      // $user = User::where('amazon_id', $amazonUser['user_id'])->first();

    // $user = User::where('id', 31)->first();



      // If user is found, log them in

    // if ($user) {

    //   Auth::loginUsingId(31);

    // }



    return view('front.amazon_checkout',compact('storename')); 

  }



  public function amazon_checkout(Request $request,$storename)

  {



    $success_url = action('Front\PaymentController@payreturn');

    $cancel_url = action('Front\PaymentController@paycancle');



    // get access token.

    parse_str($_POST['formData'], $searcharray);

    

    $settings = Generalsetting::where('storename',$storename)->first();

    $molly_data['item_name'] = $settings->title." Order";

    $molly_data['item_number'] = str_random(4).time();

    $molly_data['item_amount'] = $searcharray['total'];



    $accessToken = $_POST['access_token'];

    

    // get amazon order id

    $amazonReferenceId = $_POST['reference_id'];

    

    try {



        // get user details

      $amazonUser = AmazonPayment::getLoginDetails($accessToken);



    } catch (\Exception $e) {



        // Redirect back to cart page if error

      return Redirect::to('/cart')

      ->with('failure_message', 'Failed to connect to your Amazon account. Please try again.');



    }

    



    

    try {



      // set amazon order details

      AmazonPayment::setOrderDetails([

        'referenceId' => $amazonReferenceId,

        'amount' => floatval($searcharray['total']),

        'orderId' => 1

      ]);



      // comfirm the amazon order

      AmazonPayment::confirmOrder([

        'referenceId' => $amazonReferenceId,

      ]);



      // get amazon order details and

      // save the response to your customers order

      $amazon = AmazonPayment::getOrderDetails([

        'referenceId' => $amazonReferenceId,

      ]);





        // return $amazon['details']['Destination']['PhysicalDestination'];



      $address = $amazon['details']['Destination']['PhysicalDestination'];



        // // Update the order address, city, etc...

        // $order->shipping_city = $address['City'];

        // $order->shipping_state = $address['StateOrRegion'];

        // $order->save();



      $oldCart = Session::get('cart');

      $cart = new Cart($oldCart);



      foreach($cart->items as $key => $prod)

      {

        if(!empty($prod['item']['license']) && !empty($prod['item']['license_qty']))

        {

          foreach($prod['item']['license_qty']as $ttl => $dtl)

          {

            if($dtl != 0)

            {

              $dtl--;

              $produc = Product::findOrFail($prod['item']['id']);

              $temp = $produc->license_qty;

              $temp[$ttl] = $dtl;

              $final = implode(',', $temp);

              $produc->license_qty = $final;

              $produc->update();

              $temp =  $produc->license;

              $license = $temp[$ttl];

              $oldCart = Session::has('cart') ? Session::get('cart') : null;

              $cart = new Cart($oldCart);

              $cart->updateLicense($prod['item']['id'],$license);  

              Session::put('cart',$cart);

              break;

            }                    

          }

        }

      }





      $settings = Generalsetting::where('storename',$storename)->first();

      $order = new Order;



      $order['user_id'] = $searcharray['user_id'];

      $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));

      $order['totalQty'] = $searcharray['totalQty'];

      $order['pay_amount'] = $molly_data['item_amount'];

      $order['method'] = 'AmazonPay';

      $order['customer_email'] = $searcharray['email'];

      $order['customer_name'] = $searcharray['name'];

      $order['customer_phone'] = $searcharray['phone'];

      $order['order_number'] = $molly_data['item_number'];

      $order['shipping'] = $searcharray['shipping'];

      $order['pickup_location'] = $searcharray['pickup_location'];

      $order['customer_address'] = $searcharray['address'];

      $order['customer_country'] = $searcharray['customer_country'];

      $order['customer_city'] = $searcharray['city'];

      $order['customer_zip'] = $searcharray['zip'];

      $order['shipping_email'] = $searcharray['shipping_email'];

      $order['shipping_name'] = $searcharray['shipping_name'];

      $order['shipping_phone'] = $searcharray['shipping_phone'];

      $order['shipping_address'] = $searcharray['shipping_address'];

      $order['shipping_country'] = $searcharray['shipping_country'];

      $order['shipping_city'] = $searcharray['shipping_city'];

      $order['shipping_zip'] = $searcharray['shipping_zip'];

      $order['order_note'] = $searcharray['order_notes'];

      $order['coupon_code'] = $searcharray['coupon_code'];

      $order['coupon_discount'] = $searcharray['coupon_discount'];

      $order['payment_status'] = 'Completed';

      $order['currency_sign'] = $this->curr->sign;

      $order['currency_value'] = $this->curr->value;

      $order['shipping_cost'] = $searcharray['shipping_cost'];

      $order['packing_cost'] = $searcharray['packing_cost'];

      $order['tax'] = $searcharray['tax'];

      $order['dp'] = $searcharray['dp'];

      $order['txnid'] = $amazonReferenceId;

      $order['storename'] = $storename;



      $order['vendor_shipping_id'] = $searcharray['vendor_shipping_id'];

      $order['vendor_packing_id'] = $searcharray['vendor_packing_id'];



      if($order['dp'] == 1)

      {

        $order['status'] = 'completed';

      }



      if (Session::has('affilate')) 

      {

        $val = $molly_data['item_amount'] / $this->$curr->value;

        $val = $val / 100;

        $sub = $val * $settings->affilate_charge;

        $user = User::findOrFail(Session::get('affilate'));

        $user->affilate_income += $sub;

        $user->update();

        $order['affilate_user'] = $user->name;

        $order['affilate_charge'] = $sub;

      }

      $order->save();





      if($order->dp == 1){

        $track = new OrderTrack;

        $track->title = 'Completed';

        $track->text = 'Your order has completed successfully.';

        $track->order_id = $order->id;

        $track->save();

      }

      else {

        $track = new OrderTrack;

        $track->title = 'Pending';

        $track->text = 'You have successfully placed your order.';

        $track->order_id = $order->id;

        $track->save();

      }



      $notification = new Notification;

      $notification->order_id = $order->id;

      $notification->storename= $storename;

      $notification->save();



      if($searcharray['coupon_id'] != "")

      {

        $coupon = Coupon::findOrFail($searcharray['coupon_id']);

        $coupon->used++;

        if($coupon->times != null)

        {

          $i = (int)$coupon->times;

          $i--;

          $coupon->times = (string)$i;

        }

        $coupon->update();



      }





      foreach($cart->items as $prod)

      {

        $x = (string)$prod['stock'];

        if($x != null)

        {

          $product = Product::findOrFail($prod['item']['id']);

          $product->stock =  $prod['stock'];

          $product->update();                

        }

      }



      foreach($cart->items as $prod)

      {

        $x = (string)$prod['size_qty'];

        if(!empty($x))

        {

          $product = Product::findOrFail($prod['item']['id']);

          $x = (int)$x;

          $x = $x - $prod['qty'];

          $temp = $product->size_qty;

          $temp[$prod['size_key']] = $x;

          $temp1 = implode(',', $temp);

          $product->size_qty =  $temp1;

          $product->update();               

        }

      }





      foreach($cart->items as $prod)

      {

        $x = (string)$prod['stock'];

        if($x != null)

        {



          $product = Product::findOrFail($prod['item']['id']);

          $product->stock =  $prod['stock'];

          $product->update();  

          if($product->stock <= 5)

          {

            $notification = new Notification;

            $notification->product_id = $product->id;

            $notification->save();                    

          }              

        }

      }





      $notf = null;



      foreach($cart->items as $prod)

      {

        if($prod['item']['user_id'] != 0)

        {

          $vorder =  new VendorOrder;

          $vorder->order_id = $order->id;

          $vorder->user_id = $prod['item']['user_id'];

          $notf[] = $prod['item']['user_id'];

          $vorder->qty = $prod['qty'];

          $vorder->price = $prod['price'];

          $vorder->order_number = $order->order_number;             

          $vorder->save();

        }



      }



      if(!empty($notf))

      {

        $users = array_unique($notf);

        foreach ($users as $user) {

          $notification = new UserNotification;

          $notification->user_id = $user;

          $notification->order_number = $order->order_number;

          $notification->save();    

        }

      }





      $gs = Generalsetting::where('storename',$storename)->first();



            //Sending Email To Buyer



      if($gs->is_smtp == 1)

      {

        $data = [

          'to' => $searcharray['email'],

          'type' => "new_order",

          'cname' => $searcharray['name'],

          'oamount' => "",

          'aname' => "",

          'aemail' => "",

          'wtitle' => "",

          'onumber' => $molly_data['item_number']

        ];



        $mailer = new ShopypallMailer();

        $mailer->sendAutoOrderMail($storename,$data,$order->id);            

      }

      else

      {

         $to = $searcharray['email'];

         $subject = "Your Order Placed!!";

         $msg = "Hello ".$searcharray['name']."!\nYou have placed a new order.\nYour order number is ".$molly_data['item_number'].".Please wait for your delivery. \nThank you.";

         $headers = "From: ".$gs->from_name."<".$gs->from_email.">";

         mail($to,$subject,$msg,$headers);            

      }

              //Sending Email To Admin

       if($gs->is_smtp == 1)

       {

        $data = [

          'to' => Pagesetting::where('storename',$storename)->first()->contact_email,

          'subject' => "New Order Recieved!!",

          'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is ".$molly_data['item_number'].".Please login to your panel to check. <br>Thank you.",

        ];



        $mailer = new ShopypallMailer();

        $mailer->sendCustomMail($data);            

      }

      else

      {

       $to = Pagesetting::where('storename',$storename)->first()->contact_email;

       $subject = "New Order Recieved!!";

       $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is ".$molly_data['item_number'].".Please login to your panel to check. \nThank you.";

       $headers = "From: ".$gs->from_name."<".$gs->from_email.">";

       mail($to,$subject,$msg,$headers);

      }





      Session::put('temporder',$order);

      Session::put('tempcart',$cart);

      Session::forget('cart');

      Session::forget('already');

      Session::forget('coupon');

      Session::forget('coupon_total');

      Session::forget('coupon_total1');

      Session::forget('coupon_percentage');

      Session::forget('cart');

      Session::forget('paypal_data');

      Session::forget('molly_data');

      return redirect($success_url);

        // log error.

        // tell customer something went wrong.

        // maybe delete `$order->delete()` or rollback `DB::rollback();` your websites internal order in the database since it wasn't approved by Amazon



    } catch (\Tuurbo\AmazonPayment\Exceptions\OrderReferenceNotModifiableException $e) {





      

            // return Redirect::to('/secure/cart')->with('warning_message', 'Your order has already been placed and is not modifiable online. Please call '.config('site.company.phone').' to make changes.');

    } catch (\Exception $e) {

            // DB::rollback();

      return redirect($cancel_url);

            // return Redirect::to('/secure/cart')->with('warning_message', 'There was an error with your order. Please try again.');



    }

  }

}