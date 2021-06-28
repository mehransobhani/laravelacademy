<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
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
            $teachers = User::
                whereIn(
                    'role' , ['supporter' , 'admin' , 'teacher'])
                ->where([
                    [ 'name' , 'LIKE' , '%'.$search.'%' ]])
                ->orderBy('id' , 'asc')
                ->paginate(10) ;
            $teachers->appends(['search' => $search]);
        }
        else{
            $teachers = User::whereIn(
                 'role' , ['supporter' , 'admin' , 'teacher']
            )->orderBy('id' , 'asc')->paginate(10) ;
        }

        return response(view('admin.teacher.index' , compact('teachers')));

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = User::
            whereIn(
                'role' , ['supporter' , 'admin' , 'teacher'])
            ->findOrFail($id);

        return response(view('admin.teacher.edit' , compact('teacher')));
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

        $user = User::
            whereIn(
                'role' , ['supporter' , 'admin' , 'teacher'])
            ->findOrFail($id);
        $user->about = $request->about;
        $user->save();

        $request->session()->flash('success', 'استاد با موفقیت ویرایش شد');

        return redirect()->back();
    }




    public function create(Request $request){


        $search = $request->get('search') ?? null;
        $teachers = null;
        if (isset($search)){
            $teachers = User::
                select('id' , 'name')
                ->whereNotIn(
                    'role' , ['supporter' , 'admin' , 'teacher'])
                ->where([
                    [ 'name' , 'LIKE' , '%'.$search.'%' ]])
                ->orderBy('id' , 'asc')
                ->paginate(10) ;
            $teachers->appends(['search' => $search]);
        }

        return response(view('admin.teacher.create' , compact('teachers')));

    }



    public function store(Request $request , $id){


        $user = User::
        whereNotIn(
            'role' , ['supporter' , 'admin' , 'teacher'])
            ->findOrFail($id);
        $user->role = 'teacher';
        $user->save();

        $request->session()->flash('success', 'استاد با موفقیت ویرایش شد');

        return redirect()->back();


    }

}
