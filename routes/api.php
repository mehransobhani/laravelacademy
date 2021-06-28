<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function () {
    dd(auth()->user());
});

Route::post     ('/image-cropper/upload'                        , 'admin\ApiController@cover_img' );
Route::post     ('/filepond/process'                                    , 'api\HomeController@filepond' );
Route::delete     ('/filepond/revert'                                    , 'api\HomeController@filepondrevert' );
Route::get     ('/filepond'                                    , 'api\HomeController@getfilepond' );
Route::post     ('/login'                                       , 'api\UserController@login' );
Route::get      ('/most-popular'                                , 'api\HomeController@mostPopular' );
Route::get      ('/our-offer'                                   , 'api\HomeController@ourOffer' );
Route::get      ('/categories'                                  , 'api\HomeController@category' );
Route::get      ('/category/{category}'                         , 'api\HomeController@categoryPosts' );
Route::get      ('/courses'                                     , 'api\HomeController@course' );
Route::get      ('/courses/{course}'                            , 'api\HomeController@courseSingle' );
Route::get      ('/steps/{step}'                                , 'api\HomeController@stepSingle' );
Route::get      ('/steps/{step}/comments'                       , 'api\HomeController@stepComments' );
Route::get      ('/bundles/{bundle}'                             , 'api\HomeController@bundleSingle' );
Route::get      ('/search-preview'                              , 'api\HomeController@searchPreview' );
Route::get      ('/categories/{category}/courses'               , 'api\HomeController@searchPreview' );
Route::post     ('/user/update'                                 , 'api\UserController@userUpdate' );
Route::post     ('/user/GetUserCoursesDetails'                  , 'api\UserController@GetUserCoursesDetails' );

Route::middleware(['CheckApiAuth'])->group( function () {
    Route::post  ('/user/projects'                              , 'api\UserProjectController@store');
    Route::get  ('/user/projects'                               , 'api\UserProjectController@edit');
    Route::get  ('/user/check-token'                            , 'api\UserController@checkToken');
    Route::post ('/steps/add-reply-comment'                     , 'api\HomeController@addReplyComment');
    Route::get  ('/user/courses'                                , 'api\UserController@getCourses');
    Route::post ('/payment/check-discount'                      , 'api\PaymentController@checkDiscount');
    Route::get  ('/payment'                                     , 'api\PaymentController@request');
    Route::post ('/logout'                                      , 'api\UserController@logout' );
});


Route::get('/import-trans-user-course' , function (){
    $class_trans = \App\Models\ClassTrans::where("status" , 1)->get();

    foreach ($class_trans as $key => $value){
        $whereClause = [
            "type" => "class",
            "user_id" => $value->user_id,
            "type_id" => $value->class_register_id,
        ];

        $course_user = \App\Models\course_user::where($whereClause)->first();

        if ($course_user && $course_user->price === 0){
            $course_user->price = $value->price;
            echo "========".$course_user->id;

            $course_user->save();
        }

    }

});

Route::get('/fix-gift-code-off-price' , function (){


    $gift_user = \App\Models\GiftUsage::where([['off' , '>' , 1614678107 ]])->get();

    dd($gift_user);

    foreach ($class_trans as $key => $value){
        $whereClause = [
            "type" => "class",
            "user_id" => $value->user_id,
            "type_id" => $value->class_register_id,
        ];

        $course_user = \App\Models\course_user::where($whereClause)->first();

        if ($course_user && $course_user->price === 0){
            $course_user->price = $value->price;
            echo "========".$course_user->id;

            $course_user->save();
        }

    }

});
