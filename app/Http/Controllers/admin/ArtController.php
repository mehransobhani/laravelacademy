<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Art;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ArtController extends Controller
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
            $arts = Art::where([
                [ 'artName' , 'LIKE' , '%'.$search.'%' ]
            ])->orderBy('artName' , 'asc')->paginate(10) ;
            $arts->appends(['search' => $search]);
        }
        else{
            $arts = Art::orderBy('artName' , 'asc')->paginate(10) ;
        }
        return response(view('admin.art.index' , compact('arts')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('admin.art.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customAttributes = [
            'artName' => 'نام دسته',
        ];

        $validatedData = $request->validate([
            'artName' => 'required|unique:arts,artName',
        ], [] ,$customAttributes);



        $art = new Art;
        $art->artName = $request->artName;
        $art->art_url = $request->art_url;
        $art->description = $request->description;
        $art->save();

        $request->session()->flash('success', 'دسته با موفقیت ثبت شد');

        return redirect()->back();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $art = Art::findOrFail($id);
        return response(view('admin.art.edit' , compact('art')));
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

        $customAttributes = [
            'artName' => 'نام دسته',
        ];

        $validatedData = $request->validate([
            'artName' => 'required|unique:arts,artName,'.$id,
        ], [] ,$customAttributes);


        $art = Art::findOrFail($id);
        $art->artName = $request->artName;
        $art->description = $request->description;
        $art->save();

        $request->session()->flash('success', 'دسته با موفقیت ویرایش شد');

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
        $art = Art::findOrFail($id);
        $art->delete();

        Session::flash('success', 'دسته با موفقیت حذف شد');

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
            $arts = Art::onlyTrashed()->where([
                [ 'artName' , 'LIKE' , '%'.$search.'%' ]
            ])->orderBy('artName' , 'asc')->paginate(10) ;
            $arts->appends(['search' => $search]);
        }
        else{
            $arts = Art::onlyTrashed()->orderBy('artName' , 'asc')->paginate(10) ;
        }


        return view('admin.art.trash' , compact('arts'));

    }

    /**
     * forceDelete Trashed Art
     *
     */
    public function forceDelete($id)
    {
        $art = Art::onlyTrashed()->findOrFail($id);
        $art->forceDelete();

        Session::flash('success', 'دسته برای همیشه حذف شد');

        return redirect()->back();

    }

    /**
     * forceDelete Trashed Art
     *
     */
    public function restore($id)
    {
        $art = Art::onlyTrashed()->findOrFail($id);
        $art->restore();

        Session::flash('success', 'دسته بارزیابی شد');

        return redirect(route('art.index'));

    }
}
