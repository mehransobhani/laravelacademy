<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTrans;
use App\Models\Course;
use App\Models\course_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request){


        $append = [];
        $transactions = ClassTrans::query();

        $transactions->where(function($q) {
            $q->where('kind' , 'course_class')
                ->orWhere('kind' , 'bundle');
        })->with('get_class' , 'get_user');

        if($request->username){
            $transactions->whereHas('get_user' , function ($query) use ($request){
                $query->where('username', 'like', '%'.$request->username.'%');
            });
            $append['username'] = $request->username;
        }

        if($request->class){
            $transactions->whereHas('get_class' , function ($query) use ($request){
                $query->where('name', 'like', '%'.$request->class.'%');
            });
            $append['class'] = $request->class;
        }

        if($request->order_id){
            $transactions->where('order_id' , $request->order_id );
            $append['order_id'] = $request->order_id;
        }


        if($request->bank_ref){
            $transactions->where('bank_ref' , $request->bank_ref );
            $append['bank_ref'] = $request->bank_ref;
        }

        if($request->get("id")){
            $transactions->where('id' , $request->id );
            $append['id'] = $request->id;
        }

        if ($request->date){
            $jdate_array = explode('/' , $request->date);
            if (is_integer( intval($jdate_array[0]) ) && is_integer( intval($jdate_array[1]) ) && is_integer( intval($jdate_array[2]) )){
                $geodate_array = jalali_to_gregorian( $jdate_array[0] , $jdate_array[1] , $jdate_array[2]  );
                $timestamp = strtotime ( $geodate_array[0].'-'.$geodate_array[1].'-'.$geodate_array[2] );
                $transactions->whereBetween('created_at', [ $timestamp ,  $timestamp+86400  ]);
                $append['date'] = $request->date;
            }

        }



        if(isset($request->status)){
            $transactions->where('status' , $request->status );
            $append['status'] = $request->status;
        }

        $transactions = $transactions->orderBy('created_at' , 'DESC')->paginate(10);
        $transactions->appends($append);

        //$transactions = ClassTrans::where('kind' , 'course_class')->with('get_class' , 'get_user')->orderBy('created_at' , 'DESC')->paginate(30);




        return view('admin.transaction.index' , compact('transactions'));
    }



    public function addUserClass(){


        $courses = Course::all();

        return view('admin.transaction.addUserClass' , compact('courses'));

    }


    public function storeUserClass(Request $request){


        $validatedData = Validator::make($request->all() ,[
            'username' => 'required',
            'class_id' => 'required',
        ]);

        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }




        $user = \App\Models\User::where("username" , $request->username)->first();

        if (!$user){
            return redirect()->back()->withErrors(['username' => ['چنین کاربری یافت نشد']])->withInput($request->input());
        }


        $coures_user = course_user::where([['user_id' , '=' , $user->id],['type_id' , '=' , $request->class_id]])->first();


        if ($coures_user){
            return redirect()->back()->withErrors(['username' => ['قبلا اضافه شده']])->withInput($request->input());
        }

        $userCourse = new \App\Models\course_user();
        $userCourse->user_id = $user->id;
        $userCourse->create_at = time();
        $userCourse->type = "class";
        $userCourse->type_id = $request->class_id;
        $userCourse->description = "added manually by ".$request->user()->username;
        $userCourse->save();
        $request->session()->flash('success', 'با موفقیت ثبت شد');

        return redirect()->back();
    }


}
