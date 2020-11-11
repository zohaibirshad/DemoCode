<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class LanguageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = Language::where('storename',$storename)->orderBy('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(Language $data) use($storename) {
                                $delete = $data->language == 'English' ? '':'<a href="javascript:;" data-href="' . route('admin-lang-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                $default = $data->is_default == 1 ? '<a><i class="fa fa-check"></i> Default</a>' : '<a class="status" data-href="' . route('admin-lang-st',[$storename,'id1'=>$data->id,'id2'=>1]) . '">Set Default</a>';
                                return '<div class="action-list"><a href="' . route('admin-lang-edit',[$storename,$data->id]) . '"> <i class="fas fa-edit"></i>Edit</a>'.$delete.$default.'</div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.language.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {
        return view('admin.language.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $input = $request->all();
        $data = new Language();
        $data->language = $input['language'];
        $data->file = time().str_random(8).'.json';
        $data->storename = $storename;
        $data->save();
        unset($input['_token']);
        unset($input['language']);
        $mydata = json_encode($input);
        file_put_contents(public_path().'/assets/languages/'.$data->file, $mydata); 
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        Session::put('success',$msg);
        return redirect()->back();  
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($storename,$id)
    {
        $data = Language::findOrFail($id);
        $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
        $lang = json_decode($data_results);
        return view('admin.language.edit',compact('data','lang','storename'));
    }

    //*** POST Request
    public function update(Request $request,$storename,$id)
    {
        //--- Validation Section
        
        //--- Validation Section Ends

        //--- Logic Section
        $input = $request->all();
        $data = Language::findOrFail($id);
        if (file_exists(public_path().'/assets/languages/'.$data->file)) {
            // unlink(public_path().'/assets/languages/'.$data->file);
        }
        $data->language = $input['language'];
        $data->file = time().str_random(8).'.json';
        $data->update();
        unset($input['_token']);
        unset($input['language']);
        $mydata = json_encode($input);
        file_put_contents(public_path().'/assets/languages/'.$data->file, $mydata); 
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
            return redirect()->back();    
        //--- Redirect Section Ends            
    }

      public function status($storename,$id1,$id2)
        {
            $data = Language::findOrFail($id1);
            $data->is_default = $id2;
            $data->update();
            $data = Language::where('id','!=',$id1)->update(['is_default' => 0]);
            //--- Redirect Section     
            $msg = 'Data Updated Successfully.';
            Session::put('success',$msg);
            return redirect()->back();    
            //--- Redirect Section Ends  
        }

    //*** GET Request Delete
    public function destroy($storename,$id)
    {
        if($id == 1)
        {
            Session::put('error',"You don't have access to remove this language");
            return redirect()->back();
        }
        $data = Language::findOrFail($id);
        if($data->is_default == 1)
        {
            Session::put('error',"You can not remove default language.");
            return redirect()->back();
        }

        if($data->file != '1579762052FstnupIm.json')
        {
            if (file_exists(public_path().'/assets/languages/'.$data->file)) {
                // unlink(public_path().'/assets/languages/'.$data->file);
            }
        }
        
        
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
            return redirect()->back();       
        //--- Redirect Section Ends     
    }
}
