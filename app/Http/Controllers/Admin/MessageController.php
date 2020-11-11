<?php

namespace App\Http\Controllers\Admin;

use App\Classes\ShopypallMailer;
use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Models\AdminUserConversation;
use App\Models\AdminUserMessage;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Generalsetting;
use Auth;
use Session;

class MessageController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
  }

    //*** JSON Request
  public function datatables($storename,$type)
  {
   $datas = AdminUserConversation::where('storename',$storename)->where('type','=',$type)->get();
         //--- Integrating This Collection Into Datatables
   return Datatables::of($datas)
   ->editColumn('created_at', function(AdminUserConversation $data) use ($storename) {
    $date = $data->created_at->diffForHumans();
    return  $date;
  })
   ->addColumn('name', function(AdminUserConversation $data) use ($storename){
    $name = $data->user->name;
    return  $name;
  })
   ->addColumn('action', function(AdminUserConversation $data) use ($storename){
    return '<div class="action-list"><a href="' . route('admin-message-show',[$storename,$data->id]) . '"> <i class="fas fa-eye"></i> Details</a><a href="javascript:;" data-href="' . route('admin-message-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
  }) 
   ->rawColumns(['action'])->toJson(); //--- Returning Json Data To Client Side
  }

//*** GET Request
  public function index($storename)
  {
    return view('admin.message.index',compact('storename'));            
  }

//*** GET Request
  public function disputes($storename)
  {
    return view('admin.message.dispute',compact('storename'));            
  }

//*** GET Request
  public function message($storename,$id)
  {
    if(!AdminUserConversation::where('id',$id)->exists())
    {
      return redirect()->route('admin.dashboard',$storename)->with('unsuccess',__('Sorry the page does not exist.'));
    }
    $conv = AdminUserConversation::findOrfail($id);
    return view('admin.message.create',compact('conv','storename'));                 
  }   

//*** GET Request
  public function messageshow($id)
  {
    $conv = AdminUserConversation::findOrfail($id);
    return view('load.message',compact('conv'));                 
  }   

//*** GET Request
  public function messagedelete($storename,$id)
  {
    $conv = AdminUserConversation::findOrfail($id);
    if($conv->messages->count() > 0)
    {
     foreach ($conv->messages as $key) {
      $key->delete();
    }
  }
  $conv->delete();
//--- Redirect Section     
  $msg = 'Data Deleted Successfully.';
  Session::put('success',$msg);
  return redirect()->back();       
//--- Redirect Section Ends               
}

//*** POST Request
public function postmessage(Request $request)
{
  $msg = new AdminUserMessage();
  $input = $request->all();
  $msg->storename = $storename;
  $msg->fill($input)->save();
//--- Redirect Section     
  $msg = 'Message Sent!';
  Session::put('success',$msg);
  return redirect()->back();      
//--- Redirect Section Ends    
}

//*** POST Request
public function usercontact(Request $request,$storename)
{
  $data = 1;
  $admin = Auth::guard('admin')->user();
  $user = User::where('email','=',$request->to)->first();
  if(empty($user))
  {
    $data = 0;
    return response()->json($data);   
  }
  $to = $request->to;
  $subject = $request->subject;
  $from = $admin->email;
  $msg = "Email: ".$from."<br>Message: ".$request->message;
  $gs = Generalsetting::where('storename',$storename)->first();
  if($gs->is_smtp == 1)
  {

    $datas = [
      'to' => $to,
      'subject' => $subject,
      'body' => $msg,
    ];
    $mailer = new ShopypallMailer();
    $mailer->sendCustomMail($datas);
  }
  else
  {
    $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
    mail($to,$subject,$msg,$headers);            
  }

  if($request->type == 'Ticket'){
    $conv = AdminUserConversation::where('storename',$storename)->where('type','=','Ticket')->where('user_id','=',$user->id)->where('subject','=',$subject)->first(); 
  }
  else{
    $conv = AdminUserConversation::where('storename',$storename)->where('type','=','Dispute')->where('user_id','=',$user->id)->where('subject','=',$subject)->first(); 
  }
  if(isset($conv)){
    $msg = new AdminUserMessage();
    $msg->conversation_id = $conv->id;
    $msg->message = $request->message;
    $msg->storename = $storename;
    $msg->save();
    return response()->json($data);   
  }
  else{
    $message = new AdminUserConversation();
    $message->subject = $subject;
    $message->user_id= $user->id;
    $message->message = $request->message;
    $message->order_number = $request->order;
    $message->type = $request->type;
    $message->storename = $storename;
    $message->save();
    $msg = new AdminUserMessage();
    $msg->conversation_id = $message->id;
    $msg->storename = $storename;
    $msg->message = $request->message;
    $msg->save();
    return response()->json($data);   
  }
}
}