<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
    Route::post ('/log-user-footprint'                          , 'api\UserController@logUserFootprint'); // TO BE TESTED!
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

Route::get('/shop/new-four-courses', function(){
	$courses = DB::select(
            "SELECT id, `name`, cover_img, price, `off`, kind, urlfa FROM courses WHERE `status` = 1 ORDER BY create_at DESC LIMIT 4 "
        );
        if(count($courses) === 0){
            echo json_encode(array('status' => 'done', 'found' => false, 'message' => 'could not find any available course', 'courses' => []));
            exit();
        }
        foreach($courses as $course){
            if($course->kind === 'class'){
                $course->url = 'https://honari.com/academy/courses/' . $course->urlfa;
            }else if($course->kind === 'bundle'){
                $course->url = 'https://honari.com/academy/bundles/' . $course->urlfa;
            }
            $course->image = 'https://academy.honari.com/warehouse/images/classes/' . $course->cover_img;
        }
        echo json_encode(array('status' => 'done', 'found' => true, 'message' => 'courses information successfully found', 'courses' => $courses));
});

Route::get('/shop/courses', function(){
	$courses = DB::select(
		"SELECT id, name, urlfa, kind FROM courses WHERE status = 1 ORDER BY name ASC "
	);
	if(count($courses) === 0){
		echo json_encode(array('status' => 'done', 'found' => false, 'message' => 'courses not found', 'courses' => []));
		exit();
	}
	echo json_encode(array('status' => 'done', 'found' => true, 'message' => 'courses successfully found', 'courses' => $courses));
});

Route::get('/shop/arts', function(){
	$arts = DB::select("SELECT id, artName AS name, art_url AS url FROM arts ORDER BY id ASC");
	if(count($arts) === 0){
		echo json_encode(array('status' => 'done', 'found' => false, 'message' => 'could not find any art', 'arts' => []));
		exit();
	}
	echo json_encode(array('status' => 'done', 'found' => true, 'message' => 'arts are successfully found', 'arts' => $arts));
});

Route::post('/shop/courses-information', function(Request $request){
	if(!isset($request->courseIds)){
		echo json_encode(array('status' => 'failed', 'message' => 'not enough parameters'));
		exit();
	}
	
	$response = [];
	$courseIds = json_decode($request->courseIds);
	foreach($courseIds as $courseId){
		$courseInfo = DB::select("SELECT name, cover_img, urlfa, price, off FROM courses WHERE id = $courseId LIMIT 1 ");
		if(count($courseInfo) !== 0){
			array_push($response, $courseInfo);
		}
	}
	echo json_encode(array('status' => 'done', 'message' => 'course Information successfully found', 'courses' => $response));
});
