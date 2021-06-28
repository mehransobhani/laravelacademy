<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Art;
use App\Models\Course;
use Gumlet\ImageResize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BundleController extends Controller
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
            $bundles = Course::where([
                [ 'title' , 'LIKE' , '%'.$search.'%' ],
                [ 'kind' , 'LIKE' , "bundle"]
            ])->orderBy('created_at' , 'asc')->paginate(10) ;
            $bundles->appends(['search' => $search]);
        }
        else{
            $bundles = Course::where([
                [ 'kind' , 'LIKE' , "bundle"]
            ])->orderBy('created_at' , 'asc')->paginate(10) ;
        }
        return response(view('admin.bundle.index' , compact('bundles')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arts = Art::all();
        $courses = Course::where('kind' , 'LIKE' , 'class')->get();
        return response(view('admin.bundle.create' , compact("courses" , "arts")));
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

        if ($request->has('course_price')) {
            $arr = [];
            foreach ($request->get('course_price') as $key => $value){
                $arr[$key] = str_replace(",","", $value);
            }
            $request->merge(['course_price' => $arr]);
        }


        $customAttributes = [
            'title' => 'نام بسته آموزشی',
            'image' => 'تصویر بسته',
            'status' => 'وضعیت',
            'course' => 'دوره',
        ];

        $validatedData = Validator::make($request->all() ,[
            'title' => 'required|max:255|unique:courses,name',
            'image' => 'required',
            'slug' => 'required|unique:courses,urlfa',
            'description' => 'required',
            'status' => 'required',
            'course' => 'required',
        ], [] ,$customAttributes);


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $bundle = new Course();
        $bundle->name = $request->title;


        $directory = Storage::disk('warehouse')->put('images/classes/', $request->image);
        $directory = basename($directory);
        $image = new ImageResize($request->image);
        $image->resize(600, 300);
        Storage::disk('warehouse')->put('images/classes'."/_600_300/".$directory, $image);



        $bundle->cover_img = $directory;

        $bundle->urlfa = $request->slug;
        $bundle->description = $request->description;
        $bundle->off = 0;
        $bundle->price = array_sum($request->get('course_price'));
        $bundle->status = $request->status;
        $bundle->create_at = time();
        $bundle->kind = "bundle";

        $bundle->save();

        foreach ($request->course as $key=>$value){
            $bundle->related()->attach($value, ['price' => $request->course_price[$value] ]);
        }

        $bundle->arts()->attach($request->category);


        $request->session()->flash('success', 'بسته آموزشی با موفقیت ثبت شد');

        return redirect()->route('bundle.index');

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
        $arts = Art::all();
        $bundle = Course::findOrFail($id);
        $courses = Course::all();
        $category_ids = $bundle->arts->pluck('id')->toArray();
        return response(view('admin.bundle.edit' , compact('bundle' , 'courses' , 'arts' , 'category_ids')));
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

        if ($request->has('course_price')) {
            $arr = [];
            foreach ($request->get('course_price') as $key => $value){
                $arr[$key] = str_replace(",","", $value);
            }
            $request->merge(['course_price' => $arr]);
        }

        $customAttributes = [
            'title' => 'نام بسته آموزشی',
            'course' => 'دوره',
        ];

        $validatedData = Validator::make($request->all() ,[
            'title' => 'required|max:255|unique:courses,name,'.$id,
            'description' => 'required',
            'status' => 'required',
            'course' => 'required',

        ], [] ,$customAttributes);


        if($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput($request->input());
        }

        $bundle = Course::findOrFail($id);

        $bundle->name = $request->title;

        if ($request->image){
            Storage::disk('warehouse')->delete('images/classes/' . $bundle->cover_img);
            Storage::disk('warehouse')->delete('images/classes/_600_300/' . $bundle->cover_img);
            $directory = Storage::disk('warehouse')->put('images/classes/', $request->image);
            $directory = basename($directory);
            $image = new ImageResize($request->image);
            $image->resize(600, 300);
            Storage::disk('warehouse')->put('images/classes'."/_600_300/".$directory, $image);
            $bundle->cover_img = $directory;
        }
        $bundle->description = $request->description;
        $bundle->price = array_sum($request->get('course_price'));
        $bundle->status = $request->status;

        $bundle->save();

        $bundle->arts()->detach();
        $bundle->arts()->attach($request->category);

        $course_price = $request->get('course_price');

        $bundle->related()->detach();
        foreach ($request->course as $key=>$value){
            $bundle->related()->attach($value, ['price' => $course_price[$value] ? str_replace(",","",  $course_price[$value]) : null ]);
        }

        $request->session()->flash('success', 'بسته آموزشی با موفقیت ویرایش شد');

        return redirect()->route('bundle.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
