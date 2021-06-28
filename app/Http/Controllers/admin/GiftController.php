<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $search = $request->get('search') ?? null;


        $gifts = Gift::query();
        $gifts->with('courses');

        if (isset($search) && is_array($search)){
            if (isset($search['name'])){
                $gifts->where([
                    [ 'code' , 'LIKE' , '%'.$search['name'].'%' ]
                ]);
            }
            if (isset($search['course'])){
                $gifts->whereHas('courses', function ($query) use ($search){
                    $query->where('name', 'like', '%'.$search['course'].'%');
                });
            }
            $gifts = $gifts->orderBy(DB::raw('-`end_time`'), 'asc')->paginate(10) ;


                isset($search['name']) ? $gifts->appends(['search[name]' => $search['name']]) : null ;
                isset($search['course']) ? $gifts->appends(['search[course]' => $search['course']]) : null ;


        }
        else{
            $gifts = Gift::orderBy(DB::raw('-`end_time`'), 'asc')->paginate(10) ;
        }

        return response(view('admin.gift.index' , compact('gifts')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        return response(view('admin.gift.create' , compact('courses')) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->get('off') > 0) {
            $request->merge(['off' => str_replace(",","", $request->off)]);
        }
        if ($request->get('percent') > 0) {
            $request->merge(['percent' => str_replace(",","", $request->percent)]);
        }
        if ($request->has('start_time')) {
            $request->merge(['start_time' => persian_date_picker_to_timestamp($request->start_time)]);
        }
        if ($request->has('infinity')) {
            $request->merge(['end_time' => null]);
        }
        else{
            $request->merge(['end_time' => persian_date_picker_to_timestamp($request->end_time)]);

        }

        $customAttributes = [
            'code' => 'کد تخفیف',
            'start_time' => 'شروع'
        ];

        $Validating_Array = [
            'code' => 'required|min:3|max:255|unique:gifts',
            'start_time' => 'required',
            'description' => 'max:255',
        ];

        $validatedData = Validator::make($request->all() , $Validating_Array , [] ,$customAttributes);

        $validatedData->after(function ($validatedData) {

            if (request('percent') > 100){
                $validatedData->errors()->add('percent_more_100', 'درصد تخفیف نمیتواند بزرگتر از ۱۰۰ باشد.');
            }
            if (request('off') > 0 && request('percent') > 0){
                $validatedData->errors()->add('both_filled', 'هر دو فیلد مبلغ تخفیف و درصد تخفیف نمیتواند بزرگتر از ۰ باشد.');
            }
            if (request('off') <= 0 && request('percent') <= 0){
                $validatedData->errors()->add('one_required', 'یکی از دو فیلد مبلغ تخفیف و درصد تخفیف باید بزرگتر از ۰ باشد.');
            }
            if (request('off') < 0 ){
                $validatedData->errors()->add('cant_negative', 'فیلد مبلغ تخفیف نمیتواند منفی باشد.');
            }
            if (request('percent') < 0){
                $validatedData->errors()->add('cant_negative', 'فیلد درصد تخفیف نمیتواند منفی باشد.');
            }

        });


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }


        $gift = new Gift();
        $gift->code = $request->code;
        $gift->off = $request->off ?? 0;
        $gift->percent = $request->percent ?? 0;
        $gift->maximum = $request->maximum;
        $gift->start_time = $request->start_time;
        $gift->end_time = $request->end_time;
        $gift->description = $request->description;
        $gift->reusable = $request->reusable ? 1 : 0;
        $gift->save();


        $gift->courses()->sync($request->courses);
        $request->session()->flash('success', 'کد تخفیف با موفقیت ثبت شد');

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gift = Gift::findOrFail($id);
        $courses = Course::all();
        return response(view('admin.gift.edit' , compact('courses' , 'gift')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        if ($request->get('off') > 0) {
            $request->merge(['off' => str_replace(",","", $request->off)]);
        }
        if ($request->get('percent') > 0) {
            $request->merge(['percent' => str_replace(",","", $request->percent)]);
        }
        if ($request->has('start_time')) {
            $request->merge(['start_time' => persian_date_picker_to_timestamp($request->start_time)]);
        }
        if ($request->has('infinity')) {
            $request->merge(['end_time' => null]);
        }
        else{
            $request->merge(['end_time' => persian_date_picker_to_timestamp($request->end_time)]);

        }

        $customAttributes = [
            'code' => 'کد تخفیف',
            'start_time' => 'شروع'
        ];

        $Validating_Array = [
            'start_time' => 'required',
            'description' => 'max:255',
        ];

        $validatedData = Validator::make($request->all() , $Validating_Array , [] ,$customAttributes);

        $validatedData->after(function ($validatedData) {

            if (request('percent') > 100){
                $validatedData->errors()->add('percent_more_100', 'درصد تخفیف نمیتواند بزرگتر از ۱۰۰ باشد.');
            }
            if (request('off') > 0 && request('percent') > 0){
                $validatedData->errors()->add('both_filled', 'هر دو فیلد مبلغ تخفیف و درصد تخفیف نمیتواند بزرگتر از ۰ باشد.');
            }
            if (request('off') <= 0 && request('percent') <= 0){
                $validatedData->errors()->add('one_required', 'یکی از دو فیلد مبلغ تخفیف و درصد تخفیف باید بزرگتر از ۰ باشد.');
            }
            if (request('off') < 0 ){
                $validatedData->errors()->add('cant_negative', 'فیلد مبلغ تخفیف نمیتواند منفی باشد.');
            }
            if (request('percent') < 0){
                $validatedData->errors()->add('cant_negative', 'فیلد درصد تخفیف نمیتواند منفی باشد.');
            }

        });


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }


        $gift = Gift::findOrFail($id);
        $gift->off = $request->off ?? 0;
        $gift->percent = $request->percent ?? 0;
        $gift->maximum = $request->maximum;
        $gift->start_time = $request->start_time;
        $gift->end_time = $request->end_time;
        $gift->description = $request->description;
        $gift->reusable = $request->reusable ? 1 : 0;
        $gift->save();


        $gift->courses()->sync($request->courses);
        $request->session()->flash('success', 'کد تخفیف با موفقیت ویرایش شد');

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);
        $gift->courses()->detach();
        $gift->delete();

        Session::flash('success', 'تخفیف با موفقیت حذف شد');

        return redirect()->back();

    }



    public function trash(Request $request){

        $search = $request->get('search') ?? null;
        if (isset($search)){
            $gifts = Gift::where([
                [ 'code' , 'LIKE' , '%'.$search.'%' ]
            ])->orderBy(DB::raw('-`end_time`'), 'asc')->onlyTrashed()->paginate(10) ;
            $gifts->appends(['search' => $search]);
        }
        else{
            $gifts = Gift::orderBy(DB::raw('-`end_time`'), 'asc')->onlyTrashed()->paginate(10) ;
        }

        return view('admin.gift.trash' , compact('gifts'));
    }



    public function forceDelete($id)
    {
        $gift = Gift::onlyTrashed()->findOrFail($id);
        $gift->courses()->detach();
        $gift->forceDelete();

        Session::flash('success', 'تخفیف برای همیشه حذف شد');

        return redirect()->back();

    }


    public function restore($id)
    {
        $gift = Gift::onlyTrashed()->findOrFail($id);
        $gift->restore();

        Session::flash('success', 'تخفیف بارزیابی شد');

        return redirect(route('gift.index'));

    }
}
