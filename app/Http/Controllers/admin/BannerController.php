<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Art;
use App\Models\Banner;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(){
        return view('admin.banner.index');
    }


    public function mostPopular(){
        $banners = Banner::with('get_art')->where('type', 'most-popular')->orderBy('position')->get();
        return view('admin.banner.most-popular.index' , compact('banners'));

    }


    public function mostPopularEdit($id){
        $banner = Banner::with('get_course')->findOrFail($id);
        $arts = Art::all();
        return view('admin.banner.most-popular.edit' , compact('banner' , 'arts'));
    }

    public function mostPopularUpdate(Request $request , $id){

        $customAttributes = [
            'art' => 'هنر',
            'img' => "تصویر هنر"
        ];

        $validatedData = $request->validate([
            'art' => 'required|unique:banners,course_id,'.$id,
            'img' => 'required|image',
        ], [] ,$customAttributes);

        $banner = Banner::with('get_course')->findOrFail($id);
        $banner->art_id = $request->art;

        if ($request->img){
            Storage::disk('warehouse')->delete('images/arts/'.$banner->img);
            $img = Storage::disk('warehouse')->put('images/arts', $request->img);
            $banner->img = basename($img);
        }

        $banner->save();

        Session::flash('success', 'بنر به روز شد');

        return redirect()->route("banner.mostPopular.index");
    }





    public function ourOffer(){
        $banners = Banner::with('get_course')->where('type', 'our-offer')->orderBy('position')->get();
        return view('admin.banner.our-offer.index' , compact('banners'));
    }


    public function ourOfferEdit($id){
        $banner = Banner::with('get_course')->findOrFail($id);
        $courses = Course::where("status" , 1)->get();
        return view('admin.banner.our-offer.edit' , compact('banner' , 'courses'));
    }

    public function ourOfferUpdate(Request $request , $id){

        $customAttributes = [
            'course' => 'دوره',
        ];

        $validatedData = $request->validate([
            'course' => 'required|unique:banners,course_id,'.$id,
        ], [] ,$customAttributes);

        $banner = Banner::with('get_course')->findOrFail($id);
        $banner->course_id = $request->course;
        $banner->save();

        Session::flash('success', 'بنر به روز شد');

        return redirect()->route("banner.ourOffer.index");

    }
}
