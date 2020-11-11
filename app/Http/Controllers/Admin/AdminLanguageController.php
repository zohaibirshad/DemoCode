<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;
use App\Models\AdminLanguage;
use App;
use Session;

class AdminLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables($storename)
    {
         $datas = AdminLanguage::where('storename',$storename)->orderBy('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(AdminLanguage $data) use($storename){
                                $delete = $data->id == 1 ? '':'<a href="javascript:;" data-href="' . route('admin-tlang-delete',[$storename,$data->id]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                $default = $data->is_default == 1 ? '<a><i class="fa fa-check"></i> Default</a>' : '<a class="status" data-href="' . route('admin-tlang-st',[$storename,'id1'=>$data->id,'id2'=>1]) . '">Set Default</a>';
                                return '<div class="action-list"><a href="' . route('admin-tlang-edit',[$storename,$data->id]) . '"> <i class="fas fa-edit"></i>Edit</a>'.$delete.$default.'</div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index($storename)
    {
        return view('admin.adminlanguage.index',compact('storename'));
    }

    //*** GET Request
    public function create($storename)
    {

        return view('admin.adminlanguage.create',compact('storename'));
    }

    //*** POST Request
    public function store(Request $request,$storename)
    {

        $this->validate($request,[
            'language' => 'required',
           'rtl' => 'required'
        ]);
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $new = null;
        $input = $request->all();
        $data = new AdminLanguage();
        $data->language = $input['language'];
        $name = time().str_random(8);
        $data->name = $name;
        $data->file = $name.'.json';
        $data->rtl = $input['rtl'];
        $data->storename = $storename;
        $data->save();
        unset($input['_token']);
        unset($input['language']);
        $keys = $request->keys;
        $values = $request->values;
        foreach(array_combine($keys,$values) as $key => $value)
        {
            $n = str_replace("_"," ",$key);
            $new[$n] = $value;
        }  
        $mydata = json_encode($new);
        file_put_contents(public_path().'/project/resources/lang/'.$data->file, $mydata); 
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
        $data = AdminLanguage::findOrFail($id);
        if(file_exists(public_path().'/project/resources/lang/'.$data->file))
        {
            $data_results = file_get_contents(public_path().'/project/resources/lang/'.$data->file);
            $lang = json_decode($data_results, true);
            return view('admin.adminlanguage.edit',compact('data','lang','storename'));
        }

        return redirect()->back()->withErrors(["error" => "File not Found"]);
    }

    //*** POST Request
    public function update(Request $request, $storename,$id)
    {

        $this->validate($request,[
            'language' => 'required',
           'rtl' => 'required'
        ]);
        //--- Validation Section
        
        //--- Validation Section Ends

        //--- Logic Section
        $new = null;
        $input = $request->all();
        $data = AdminLanguage::findOrFail($id);
        if (file_exists(public_path().'/project/resources/lang/'.$data->file)) {
            // unlink(public_path().'/project/resources/lang/'.$data->file);
        }
        $data->language = $input['language'];
        $name = time().str_random(8);
        $data->name = $name;
        $data->file = $name.'.json';
        $data->rtl = $input['rtl'];
        $data->update();
        unset($input['_token']);
        unset($input['language']);
        $keys = $request->keys;
        $values = $request->values;
        foreach(array_combine($keys,$values) as $key => $value)
        {
            $n = str_replace("_"," ",$key);
            $new[$n] = $value;
        }        
        $mydata = json_encode($new);
        file_put_contents(public_path().'/project/resources/lang/'.$data->file, $mydata); 
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        Session::put('success',$msg);
        return redirect()->back();      
        //--- Redirect Section Ends            
    }

      public function status($id1,$id2)
        {
            $data = AdminLanguage::findOrFail($id1);
            $data->is_default = $id2;
            $data->update();
            $data = AdminLanguage::where('id','!=',$id1)->update(['is_default' => 0]);
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
            Session::put('error',"You don't have access to remove this language.");
            return redirect()->back();  
        // return "You don't have access to remove this language.";
        }
        $data = AdminLanguage::findOrFail($id);
        if($data->is_default == 1)
        {
            Session::put('error',"You can not remove default language.");
            return redirect()->back();  
        // return "You can not remove default language.";            
        }
        if (file_exists(public_path().'/project/resources/lang/'.$data->file)) {
            // unlink(public_path().'/project/resources/lang/'.$data->file);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        Session::put('success',$msg);
            return redirect()->back();       
        //--- Redirect Section Ends     
    }
}
