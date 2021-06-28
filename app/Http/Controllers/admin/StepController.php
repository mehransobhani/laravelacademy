<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Step;
use Gumlet\ImageResize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StepController extends Controller
{
    public function index($course_id){
        $course = Course::with('get_steps')->findOrFail($course_id);

        return view('admin.step.index' , compact('course'));
    }

    public function create($course_id){

        $course  = Course::findOrFail($course_id);

        return view('admin.step.create' , compact('course'));

    }

    public function store($course_id,Request $request){
        $customAttributes = [
            'name' => 'نام دوره',
            'summary' => 'توضیحات',
            'image' => 'تصویر',
            'short_desc' => 'خلاصه توضیحات',
            "order" => "شماره جلسه",
            "urlKey" => "slug"
        ];

        $validatedData = Validator::make($request->all() , [
            'name' => 'required|max:255',
            'summary' => 'required',
            'short_desc' => 'required',
            'image' => 'required',
            'order' => 'required|numeric',
            'urlKey' => 'required|unique:stepBySteps',
        ], [] ,$customAttributes);

        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $step = new Step();
        $step->name = $request->name;

        $step->summary = $request->summary;
        $step->short_desc = $request->short_desc;




        $directory = Storage::disk('warehouse')->put('images/steps/', $request->image);
        $directory = basename($directory);
        $image = new ImageResize($request->image);
        $image->resize(200, 200);
        Storage::disk('warehouse')->put('images/steps/_200/'.$directory, $image);



        $step->img = $directory;
        $step->order = $request->order;
        $step->class_id = $course_id;
        $step->time = time();
        $step->urlKey = $request->urlKey;

        $step->save();



        $request->session()->flash("success", 'جلسه با موفقیت ثبت شد');

        return redirect()->route('course.steps' , $course_id);
    }

    public function edit($course_id,$step_id){

        $step  = Step::findOrFail($step_id);
        $course  = Course::findOrFail($course_id);

        return view('admin.step.edit' , compact('step' , 'course' ));

    }

    public function update($course_id,$step_id,Request $request){

        $customAttributes = [
            'name' => 'نام دوره',
            'summary' => 'توضیحات',
            'short_desc' => 'خلاصه توضیحات'
        ];

        $validatedData = Validator::make($request->all() , [
            'name' => 'required|max:255',
            'summary' => 'required',
            'short_desc' => 'required',
            'order' => 'required|numeric',
        ], [] ,$customAttributes);

        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $step = Step::findOrFail($step_id);
        $step->name = $request->name;

        $step->summary = $request->summary;
        $step->short_desc = $request->short_desc;

        if ($request->cover_img && $step->img !== $request->cover_img){
            Storage::disk('warehouse')->delete('images/steps/' . $step->img);
            Storage::disk('warehouse')->delete('images/steps/_200/' . $step->img);
            $step->img = $request->cover_img;

        }


        if ($request->image){
            Storage::disk('warehouse')->delete('images/steps/' . $step->img);
            Storage::disk('warehouse')->delete('images/steps/_200/' . $step->img);
            $directory = Storage::disk('warehouse')->put('images/steps/', $request->image);
            $directory = basename($directory);
            $image = new ImageResize($request->image);
            $image->resize(200, 200);
            Storage::disk('warehouse')->put('images/steps'."/_200/".$directory, $image);
            $step->img = $directory;
        }

        $step->order = $request->order;
        $step->class_id = $course_id;

        $step->save();



        $request->session()->flash('success', 'جلسه با موفقیت ویرایش شد');

        return redirect()->back();

    }

    public function delete($course_id,$step_id){

        $step  = Step::findOrFail($step_id);
        Storage::disk('warehouse')->delete('images/steps/' . $step->img);
        Storage::disk('warehouse')->delete('images/steps/_200/' . $step->cover_img);

        $step->delete();

        Session::flash('success', 'جلسه با موفقیت حذف شد');

        return redirect()->back();
    }
}
