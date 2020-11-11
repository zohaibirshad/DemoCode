<?php

namespace App\Http\Controllers\User;

use App\Classes\ShopypallMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AdminUserConversation;
use App\Models\AdminUserMessage;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Pagesetting;
use App\Models\User;


class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function messages($storename)
    {
        $user = Auth::guard('web')->user();
        $convs = Conversation::where('storename',$storename)->where('sent_user','=',$user->id)->orWhere('recieved_user','=',$user->id)->get();
        return view('user.message.index',compact('user','convs','storename'));            
    }

    public function message($storename,$id)
    {
            $user = Auth::guard('web')->user();
            $conv = Conversation::where('storename',$storename)->findOrfail($id);
            return view('user.message.create',compact('user','conv','storename'));                 
    }

    public function messagedelete($storename,$id)
    {
            $conv = Conversation::where('storename',$storename)->findOrfail($id);
            if($conv->messages->count() > 0)
            {
                foreach ($conv->messages as $key) {
                    $key->delete();
                }
            }
            $conv->delete();
            return redirect()->back()->with('success','Message Deleted Successfully');                 
    }

    public function msgload($storename,$id)
    {
            $conv = Conversation::where('storename',$storename)->findOrfail($id);
            return view('load.usermsg',compact('conv','storename'));                 
    }  

    //Send email to user
    public function usercontact(Request $request,$storename)
    {
        $data = 1;
        $user = User::where('storename',$storename)->findOrFail($request->user_id);
        $vendor = User::where('storename',$storename)->where('email','=',$request->email)->first();
        if(empty($vendor))
        {
            $data = 0;
            return response()->json($data);   
        }

        $subject = $request->subject;
        $to = $vendor->email;
        $name = $request->name;
        $from = $request->email;
        $msg = "Name: ".$name."\nEmail: ".$from."\nMessage: ".$request->message;
        $gs = Generalsetting::where('storename',$storename)->first();
        if($gs->is_smtp == 1)
        {
        $data = [
            'to' => $vendor->email,
            'subject' => $request->subject,
            'body' => $msg,
        ];

        $mailer = new ShopypallMailer();
        $mailer->sendCustomMail($data);
        }
        else
        {
        $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
        mail($to,$subject,$msg,$headers);
        }

        $conv = Conversation::where('sent_user','=',$user->id)->where('subject','=',$subject)->first();
        if(isset($conv)){
            $msg = new Message();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->sent_user = $user->id;
            $msg->storename = $storename;
            $msg->save();
            return response()->json($data);   
        }
        else{
            $message = new Conversation();
            $message->subject = $subject;
            $message->sent_user= $request->user_id;
            $message->recieved_user = $vendor->id;
            $message->message = $request->message;
            $message->storename = $storename;
            $message->save();

            $msg = new Message();
            $msg->conversation_id = $message->id;
            $msg->message = $request->message;
            $msg->sent_user = $request->user_id;
            $msg->storename = $storename;
            $msg->save();
            return response()->json($data);   
        }
    }

    public function postmessage(Request $request,$storename)
    {
        $msg = new Message();
        $input = $request->all();
        $msg->storename = $storename;
        $msg->fill($input)->save();
        //--- Redirect Section     
        $msg = 'Message Sent!';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends  
    }

    public function adminmessages($storename)
    {
            $user = Auth::guard('web')->user();
            $convs = AdminUserConversation::where('storename',$storename)->where('type','=','Ticket')->where('user_id','=',$user->id)->get();
            return view('user.ticket.index',compact('convs','storename'));            
    }

    public function adminDiscordmessages($storename)
    {
            $user = Auth::guard('web')->user();
            $convs = AdminUserConversation::where('storename',$storename)->where('type','=','Dispute')->where('user_id','=',$user->id)->get();
            return view('user.dispute.index',compact('convs','storename'));            
    }

    public function messageload($storename,$id)
    {
            $conv = AdminUserConversation::where('storename',$storename)->findOrfail($id);
            return view('load.usermessage',compact('conv','storename'));                 
    }   

    public function adminmessage($storename,$id)
    {
            $conv = AdminUserConversation::where('storename',$storename)->findOrfail($id);
            return view('user.ticket.create',compact('conv','storename'));                 
    }   

    public function adminmessagedelete($storename,$id)
    {
            $conv = AdminUserConversation::where('storename',$storename)->findOrfail($id);
            if($conv->messages->count() > 0)
            {
                foreach ($conv->messages as $key) {
                    $key->delete();
                }
            }
            $conv->delete();
            return redirect()->back()->with('success','Message Deleted Successfully');                 
    }

    public function adminpostmessage(Request $request,$storename)
    {
        $msg = new AdminUserMessage();
        $input = $request->all();
        $msg->storename = $storename;
        $msg->fill($input)->save();
        $notification = new Notification;
        $notification->conversation_id = $msg->conversation->id;
        $notification->storename = $storename;
        $notification->save();
        //--- Redirect Section     
        $msg = 'Message Sent!';
        \Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends  
    }

    public function adminusercontact(Request $request,$storename)
    {
        $data = 1;
        $user = Auth::guard('web')->user();        
        $gs = Generalsetting::where('storename',$storename)->first();
        $subject = $request->subject;
        $to = Pagesetting::where('storename',$storename)->first()->contact_email;
        $from = $user->email;
        $msg = "Email: ".$from."\nMessage: ".$request->message;
        if($gs->is_smtp == 1)
        {
            $data = [
            'to' => $to,
            'subject' => $subject,
            'body' => $msg,
        ];

        $mailer = new ShopypallMailer();
        $mailer->sendCustomMail($data);
        }
        else
        {
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }
        if($request->type == 'Ticket'){
            $conv = AdminUserConversation::where('type','=','Ticket')->where('user_id','=',$user->id)->where('subject','=',$subject)->first(); 
        }
        else{
            $conv = AdminUserConversation::where('type','=','Dispute')->where('user_id','=',$user->id)->where('subject','=',$subject)->first(); 
        }
        if(isset($conv)){
            $msg = new AdminUserMessage();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->user_id = $user->id;
            $msg->storename =$storename;
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
            $notification = new Notification;
            $notification->conversation_id = $message->id;
            $notification->storename = $storename;
            $notification->save();
            $msg = new AdminUserMessage();
            $msg->conversation_id = $message->id;
            $msg->message = $request->message;
            $msg->user_id = $user->id;
            $msg->storename = $storename;
            $msg->save();
            return response()->json($data);   

        }
}
}
