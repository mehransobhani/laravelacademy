<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Gumlet\ImageResize;


class ApiController extends Controller
{
    /**
     * stores cover image of Course by api request
     *
     */
    public function cover_img(Request $request) {



        $path = $request->path ?? null;

        if ($request->croppedImage){
            $directory = Storage::disk('warehouse')->put($path, $request->croppedImage);
            $directory = basename($directory);
            if ($request->is_step){
                $image = new ImageResize($request->croppedImage);
                $image->resize(200, 200);
                Storage::disk('warehouse')->put($path."/_200/".$directory, $image);
            }
        }
        else{
            $response=[
                'data'=>[],
                'message'=>'no image sent',
                'successful'=> false,
            ];

            return response()->json($response , 400);
        }

        $response=[
            'data'=>['path' => $directory],
            'message'=>'image added successfully',
            'successful'=> true,
        ];
        return response()->json($response);

    }
}
