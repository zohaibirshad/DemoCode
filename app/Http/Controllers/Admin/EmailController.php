<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Classes\ShopypallMailer;
use App\Models\EmailTemplate;
use App\Models\Generalsetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use App\Models\Subscriber;
use App\Models\User;
use Session;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');

    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = EmailTemplate::where('storename',$storename)->orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(EmailTemplate $data) use ($storename){
                                return '<div class="action-list"><a data-href="' . route('admin-mail-edit',[$storename,$data->id]) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a></div>';
                            }) 
                            ->toJson();//--- Returning Json Data To Client Side
    }

    public function index($storename)
    {
        return view('admin.email.index',compact('storename'));
    }

    public function config($storename)
    {
        return view('admin.email.config',compact('storename'));
    }

    public function edit($storename,$id)
    {
        $data = EmailTemplate::findOrFail($id);
        return view('admin.email.edit',compact('data','storename') );
    }

    public function groupemail($storename)
    {
        $config = Generalsetting::where('storename',$storename)->first();
        return view('admin.email.group',compact('config','storename'));
    }

    public function groupemailpost(Request $request,$storename)
    {
        $config = Generalsetting::where('storename',$storename)->first();
        if($request->type == 0)
        {
        $users = User::all();
        //Sending Email To Users
        foreach($users as $user)
        {
            if($config->is_smtp == 1)
            {
                $data = [
                    'to' => $user->email,
                    'subject' => $request->subject,
                    'body' => $request->body,
                ];

                $mailer = new ShopypallMailer();
                $mailer->sendCustomMail($data);            
            }
            else
            {
               $to = $user->email;
               $subject = $request->subject;
               $msg = $request->body;
                $headers = "From: ".$config->from_name."<".$config->from_email.">";
               mail($to,$subject,$msg,$headers);
            }  
        } 
        //--- Redirect Section          
        $msg = 'Email Sent Successfully.';
        Session::put('success',$msg);
            return redirect()->back();   
        //--- Redirect Section Ends  
        }

        else if($request->type == 1)
        {
            $users = User::where('is_vendor','=','2')->get();
            //Sending Email To Vendors        
            foreach($users as $user)
            {
                if($config->is_smtp == 1)
                {
                    $data = [
                        'to' => $user->email,
                        'subject' => $request->subject,
                        'body' => $request->body,
                    ];

                    $mailer = new ShopypallMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                $to = $user->email;
                $subject = $request->subject;
                $msg = $request->body;
                    $headers = "From: ".$config->from_name."<".$config->from_email.">";
                mail($to,$subject,$msg,$headers);
                }  
            }
        } 
        else
        {
            $users = Subscriber::all();
            //Sending Email To Subscribers
            foreach($users as $user)
            {
                if($config->is_smtp == 1)
                {
                    $data = [
                        'to' => $user->email,
                        'subject' => $request->subject,
                        'body' => $request->body,
                    ];

                    $mailer = new ShopypallMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                $to = $user->email;
                $subject = $request->subject;
                $msg = $request->body;
                    $headers = "From: ".$config->from_name."<".$config->from_email.">";
                mail($to,$subject,$msg,$headers);
                }  
            }   
        }

        //--- Redirect Section          
        $msg = 'Email Sent Successfully.';
        Session::put('success',$msg);
            return redirect()->back();   
        //--- Redirect Section Ends  
    }


    

    public function update(Request $request,$storename, $id)
    {
        $data = EmailTemplate::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Redirect Section          
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
            return redirect()->back();     
        //--- Redirect Section Ends  
    }

}
