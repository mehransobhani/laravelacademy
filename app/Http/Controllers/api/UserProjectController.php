<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ProjectImage;
use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProjectController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        $userProject = UserProject::where('course_id', $request->course_id)->where('user_id' , $user->id)->first();
        if ($userProject){
            $userProjectImages =  $userProject->images->pluck('id')->toArray();
            ProjectImage::destroy($userProjectImages);
        }
        else{
            $userProject = new UserProject;
        }

        $userProject->course_id = $request->course_id;
        $userProject->user_id = $user->id;
        $userProject->description = $request->description;

        $userProject->save();

        $i =1;
        foreach ($request->images as $image){
            if ($image){
                $ProjectImage = new ProjectImage;
                $ProjectImage->project_id = $userProject->id;
                $ProjectImage->image = $image;
                $ProjectImage->position = $i++;
                $ProjectImage->save();

            }
        }




    }



    public function edit(Request $request)
    {
        $user = Auth::guard('api')->user();

        $userProject = UserProject::with('images')->where('course_id', $request->course_id)->where('user_id' , $user->id)->first();


        if ($userProject){
            $response = [
                'success' => true,
                'data'    => $userProject,
                'message' => 'projects retrieved successfully.',
            ];
            return response()->json($response);
        }
        else{
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'no project found.',
            ];
            return response()->json($response);
        }



    }
}
