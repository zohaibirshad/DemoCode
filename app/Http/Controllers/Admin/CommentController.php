<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Session;

class CommentController extends Controller
{
	public function __construct()
	    {
	        $this->middleware('auth:admin');
	    }

	    //*** JSON Request
	    public function datatables($storename)
	    {
	         $datas = Comment::where('storename',$storename)->orderBy('id')->get();
	         //--- Integrating This Collection Into Datatables
	         return Datatables::of($datas)
	                            ->addColumn('product', function(Comment $data) use ($storename){
	                                $name = mb_strlen(strip_tags($data->product->name),'utf-8') > 50 ? mb_substr(strip_tags($data->product->name),0,50,'utf-8').'...' : strip_tags($data->product->name);
	                                $product = '<a href="'.route('front.product',[$storename,$data->product->slug]).'" target="_blank">'.$name.'</a>';
	                                return $product;
	                            })
	                            ->addColumn('commenter', function(Comment $data) use ($storename){
	                                $name = $data->user->name;
	                                return $name;
	                            })
	                            ->addColumn('text', function(Comment $data) use ($storename){
	                                $text = mb_strlen(strip_tags($data->text),'utf-8') > 250 ? mb_substr(strip_tags($data->text),0,250,'utf-8').'...' : strip_tags($data->text);
	                                return $text;
	                            })
	                            ->addColumn('action', function(Comment $data) use ($storename){
	                                return '<div class="action-list"><a data-href="' . route('admin-comment-show',[$storename,$data->id]) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a><a href="javascript:;" data-href="' . route('admin-comment-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
	                            }) 
	                            ->rawColumns(['product','action'])
	                            ->toJson(); //--- Returning Json Data To Client Side
	    }
	    //*** GET Request
	    public function index($storename)
	    {
	        return view('admin.comment.index',compact('storename'));
	    }

	    //*** GET Request
	    public function show($storename,$id)
	    {
	        $data = Comment::findOrFail($storename,$id);
	        return view('admin.comment.show',compact('data','storename'));
	    }


	    //*** GET Request Delete
		public function destroy($storename,$id)
		{
		    $comment = Comment::findOrFail($id);
		    if($comment->replies->count() > 0)
		    {
		        foreach ($comment->replies as $reply) {
		            $reply->delete();
		        }
		    }
		    $comment->delete();
		    //--- Redirect Section     
		    $msg = 'Data Deleted Successfully.';
	        Session::put('success',$msg);
	        return redirect()->back(); 
		    //--- Redirect Section Ends    
		}
}