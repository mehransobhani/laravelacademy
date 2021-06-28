<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Art;
use App\Models\Course;
use App\Models\User;
use Gumlet\ImageResize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search') ?? null;
        if (isset($search)){
            $courses = Course::where([
                [ 'name' , 'LIKE' , '%'.$search.'%' ],
                [ 'kind' , 'LIKE' , 'class' ]
            ])->orderBy('create_at' , 'desc')->paginate(10) ;
            $courses->appends(['search' => $search]);
        }
        else{
            $courses = Course::where([
                [ 'kind' , 'LIKE' , 'class' ]
            ])->orderBy('create_at' , 'desc')->paginate(10) ;
        }
        return response(view('admin.course.index' , compact('courses')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arts = Art::all();
        $users = User::whereIn('role' , ['admin' , 'teacher'])->get();

        return response(view('admin.course.create' , compact('arts' , 'users')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('price')) {
            $request->merge(['price' => str_replace(",","", $request->price)]);
        }
        if($request->has('off')){
            $request->merge(['off' => str_replace(",","", $request->off)]);
        }
        $customAttributes = [
            'name' => 'نام دوره',
            'category' => 'دسته بندی',
            'status' => 'وضعیت انتشار',
            'price' => 'قیمت',
            'urlfa' => "slug"
        ];

        $validatedData = Validator::make($request->all() ,[
            'name' => 'required|max:255|unique:courses',
            'category' => 'required',
            'status' => 'required',
            'image' => 'required',
            'urlfa' => 'required|unique:courses',
            'description' => 'required',
            'price' => 'required|digits_between:1,7',
        ], [] ,$customAttributes);


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $course = new Course();
        $course->name = $request->name;

        $course->summary = $request->summary ? $request->summary :  strip_tags($request->description);
        $course->description = $request->description;
        $course->user_id = $request->teacher;
        $course->teacher_about = $request->teacher_about;
        $course->off = $request->off ? $request->off : 0;
        $course->bundles = $request->bundles;


        $directory = Storage::disk('warehouse')->put('images/classes/', $request->image);
        $directory = basename($directory);
        $image = new ImageResize($request->image);
        $image->resize(600, 300);
        Storage::disk('warehouse')->put('images/classes'."/_600_300/".$directory, $image);



        $course->cover_img = $directory;
        $course->price = $request->price;
        $course->create_at = time();
        $course->status = $request->status;
        $course->urlfa = $request->urlfa;
        $course->save();

        $course->arts()->attach($request->category);


        $request->session()->flash('success', 'دوره با موفقیت ثبت شد');

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
        $course = Course::findOrFail($id);
        $category_ids = $course->arts->pluck('id')->toArray();
        $arts = Art::all();
        $users = User::whereIn('role' , ['admin' , 'teacher'])->get();

        return response(view('admin.course.edit' , compact('arts' , 'users' , 'course' , 'category_ids')));
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

        if ($request->has('price')) {
            $request->merge(['price' => str_replace(",","", $request->price)]);
        }
        if($request->has('off')){
            $request->merge(['off' => str_replace(",","", $request->off)]);
        }
        $customAttributes = [
            'name' => 'نام دوره',
            'category' => 'دسته بندی',
            'summary' => 'خلاصه توضیحات',
            'price' => 'قیمت',
            'status' => 'وضعیت انتشار',
        ];

        $validatedData = Validator::make($request->all() ,[
            'name' => 'required|max:255|unique:courses,name,'.$id,
            'category' => 'required',
            'status' => 'required',
            'description' => 'required',
            'price' => 'required|digits_between:1,7',
        ], [] ,$customAttributes);


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $course = Course::findOrFail($id);
        $course->name = $request->name;


        $course->summary = $request->summary ? $request->summary : strip_tags($request->description);
        $course->description = $request->description;
        $course->user_id = $request->teacher;


        if ($request->image){
            Storage::disk('warehouse')->delete('images/classes/' . $course->cover_img);
            Storage::disk('warehouse')->delete('images/classes/_600_300/' . $course->cover_img);
            $directory = Storage::disk('warehouse')->put('images/classes/', $request->image);
            $directory = basename($directory);
            $image = new ImageResize($request->image);
            $image->resize(600, 300);
            Storage::disk('warehouse')->put('images/classes'."/_600_300/".$directory, $image);
            $course->cover_img = $directory;
        }

        $course->off = $request->off ? $request->off : 0;
        $course->price = $request->price;
        $course->status = $request->status;
        $course->bundles = $request->bundles;

        $course->teacher_about = $request->teacher_about;

        $course->save();

        $course->arts()->detach();
        $course->arts()->attach($request->category);

        $request->session()->flash('success', 'دوره با موفقیت ویرایش شد');

        return redirect()->route('course.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        Session::flash('success', 'دوره با موفقیت حذف شد');

        return redirect()->back();
    }



    /**
     * show all Trashed Arts
     *
     */
    public function trash(Request $request)
    {
        $search = $request->get('search') ?? null;
        if (isset($search)){
            $courses = Course::onlyTrashed()->where([
                [ 'name' , 'LIKE' , '%'.$search.'%' ]
            ])->orderBy('deleted_at' , 'desc')->paginate(10) ;
            $courses->appends(['search' => $search]);
        }
        else{
            $courses = Course::onlyTrashed()->orderBy('deleted_at' , 'desc')->paginate(10) ;
        }


        return view('admin.course.trash' , compact('courses'));

    }

    /**
     * forceDelete Trashed Art
     *
     */
    public function restore($id)
    {
        $art = Course::onlyTrashed()->findOrFail($id);
        $art->restore();

        Session::flash('success', 'دوره بارزیابی شد');

        return redirect(route('course.index'));

    }

    /**
     * forceDelete Trashed Art
     *
     */
    public function forceDelete($id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        Storage::disk('warehouse')->delete('images/classes/'.$course->cover_img);

        $course->forceDelete();

        Session::flash('success', 'دوره برای همیشه حذف شد');

        return redirect()->back();

    }
}
