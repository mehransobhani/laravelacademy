<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\TokenRepository;

class UserController extends Controller
{
    use ThrottlesLogins;
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => $validator->messages(),
                'message' => 'validation error.',
            ];

            return response()->json($response  , 400);

        }

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,config('app.auth_laravel_url') ."/api/check-token");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$request->get('token')
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
        ]);

        $output=curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = $output;


        if ($httpCode === 200){

            $res = json_decode ($data);

            $user = User::where("ex_user_id" , $res->data->id)->first();

            if (!$user) {

                $user = new User();
                $user->username = $res->data->username;
                $user->email = $res->data->email;
                $user->role = $res->data->role;
                $user->hubspot_mail = $res->data->hubspot_mail;
                $user->profilepic = $res->data->profilepic;
                $user->timestamp = time();
                $user->name = $res->data->name;
                $user->ex_user_id = $res->data->id;

                $user->save();

            } else{
                if ($user->role !== $res->data->role){
                    $user->role = $res->data->role;
                    $user->save();
                }
            }



            $user_class_token = $user->createToken('MyApp')->accessToken;

            $course_info = (DB::select("
            select  count(class_trans.id) as courses_count,
                    floor(sum(class_trans.price)) as courses_price,
                    group_concat(courses.name) as courses,
                    from_unixtime(MAX(class_trans.created_at)) as created_at
            from class_trans join courses on class_trans.class_register_id = courses.id
            where class_trans.status = 1 and class_trans.user_id = ?", [$user->id]));




            $user_info = [];

            if ($course_info[0]->courses_count > 0){
                $timestamp = strtotime($course_info[0]->created_at);


                $jdate = fa_to_en(jdate( 'Ymd' , $timestamp ));
                $user_info['courses'] = $course_info[0]->courses;
                $user_info['courses_price'] = $course_info[0]->courses_price;
                $user_info['courses_count'] = $course_info[0]->courses_count;
                $user_info['last_purchase_created_at'] = $course_info[0]->created_at;
                $user_info['last_purchase_created_at_shamsi'] = $jdate;
            }
            else {
                $user_info['courses'] = "";
                $user_info['courses_price'] = "";
                $user_info['courses_count'] = "";
                $user_info['last_purchase_created_at'] = "";
                $user_info['last_purchase_created_at_shamsi'] = "";
            }

            $user_info['user_id'] = $user->id;
            $user_info['ex_user_id'] = $user->ex_user_id;
            $user_info['user_name'] = $user->name;
            $user_info['user_mobile'] = $user->username;
            $user_info['profilepic'] = $user->profilepic;
            $user_info['hubspot_mail'] = $user->hubspot_mail;
            $user_info['email'] = $user->email;

            $response_array = [
                'token' => $user_class_token,
                'user_info' => $user_info
            ];


            $response = [
                'success' => true,
                'data'    => $response_array,
                'message' => 'current user information',
            ];

            return response()->json($response);

        }

        else{
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'user is not authenticated',
            ];

            return response()->json($response , 401);
        }
    }


    public function checkToken(Request $request){

        $user_server_token = $request->cookie("user_server_token");

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,config('app.auth_laravel_url') ."/api/check-token");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$user_server_token
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
        ]);

        $output=curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = $output;


        if ($httpCode === 200 && $data !== "user is not authenticate.") {

            $user = Auth::guard('api')->user();

            $course_info = (DB::select("
            select  count(class_trans.id) as courses_count,
                    floor(sum(class_trans.price)) as courses_price,
                    group_concat(courses.name) as courses,
                    from_unixtime(MAX(class_trans.created_at)) as created_at
            from class_trans join courses on class_trans.class_register_id = courses.id
            where class_trans.status = 1 and class_trans.user_id = ?", [$user->id]));


            $user_info = [];


            if ($course_info[0]->courses_count > 0) {
                $timestamp = strtotime($course_info[0]->created_at);


                $jdate = fa_to_en(jdate('Ymd', $timestamp));
                $user_info['courses'] = $course_info[0]->courses;
                $user_info['courses_price'] = $course_info[0]->courses_price;
                $user_info['courses_count'] = $course_info[0]->courses_count;
                $user_info['last_purchase_created_at'] = $course_info[0]->created_at;
                $user_info['last_purchase_created_at_shamsi'] = $jdate;
            } else {
                $user_info['courses'] = "";
                $user_info['courses_price'] = "";
                $user_info['courses_count'] = "";
                $user_info['last_purchase_created_at'] = "";
                $user_info['last_purchase_created_at_shamsi'] = "";
            }


            $user_info['user_id'] = $user->id;
            $user_info['ex_user_id'] = $user->ex_user_id;
            $user_info['user_name'] = $user->name;
            $user_info['user_mobile'] = $user->username;
            $user_info['profilepic'] = $user->profilepic;
            $user_info['hubspot_mail'] = $user->hubspot_mail;
            $user_info['email'] = $user->email;


            $response = [
                'success' => true,
                'data' => ['user_info' => $user_info],
                'message' => 'current user information',
            ];

            return response()->json($response);

        }

        else{
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'user is not authenticated',
            ];

            return response()->json($response , 401);
        }

    }


    public function getCourses (){
        $user = Auth::guard('api')->user();

        $courses = $user->get_courses;

        $collection=[];
        foreach ($courses as $key=>$value){
            array_push($collection ,collect([
                'id' => $value->id,
                'name' => $value->name ,
                "cover_img" => $value->cover_img,
                "urlfa" => $value->urlfa
            ]) );
        }



        $response = [
            'success' => true,
            'data'    => $collection,
            'message' => 'current user information',
        ];

        return response()->json($response);


    }



    public function logout(Request $request){
        $token = Auth::guard('api')->user()->token();

        $tokenRepository = app(TokenRepository::class);

        $tokenRepository->revokeAccessToken($token->id);


        if ($request->get('serverToken')){
            $cURLConnection = curl_init(config('app.auth_laravel_url').'/api/logout');
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLINFO_HEADER_OUT, true);
            curl_setopt($cURLConnection, CURLOPT_POST, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$request->get('serverToken'),
            ));
            curl_exec($cURLConnection);
            curl_close($cURLConnection);

        }

        $response = [
            'success' => true,
            'data' => [],
            'message' => 'token revoked successfully.',
        ];
        return response()->json($response);
    }




    public function userUpdate(Request $request){
        $key = "12345^&*(H0n@r!54321)*&^54321";
        if ($request->header('token') === md5(md5($request->id . "." . $key))){
            $user = User::where("ex_user_id" , $request->id)->first();
            if ($request->fname) $user->fname = $request->fname;
            if ($request->lname) $user->lname = $request->lname;
            if ($request->mobile) $user->mobile = $request->mobile;
            if ($request->telephone) $user->telephone = $request->telephone;
            if ($request->profilepic) $user->profilepic = $request->profilepic;
            if ($request->name) $user->name = $request->name;
            if ($request->email) $user->email = $request->email;
            if ($request->role) {
                if ($request->role === "no"){
                    $user->role = null;
                }
                else{
                    $user->role = $request->role;
                }
            }

            $user->save();

            $response = [
                'success' => true,
                'data' => [],
                'message' => 'user updated successfully.',
            ];
            return response()->json($response);
        }
        else {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'you have no power here.',
            ];
            return response()->json($response , 403);
        }
    }



    public function updateImage(Request $request){
        $key = "12345^&*(H0n@r!54321)*&^54321";

        if ($request->header('token') === md5(md5($request->id . "." . $key))){
            $user = User::find($request->id);
            $user->profilepic = $request->profilepic;
            $user->save();
        }

    }


    public function GetUserCoursesDetails(Request $request){
        $key = "12345^&*(H0n@r!54321)*&^54321";

        if ($request->header('token') === md5(md5($request->id . "." . $key))){
            $user = User::where('ex_user_id' , $request->id)->firstOrFail();
            $course_info = (DB::select("
            select  count(class_trans.id) as courses_count,
                    floor(sum(class_trans.price)) as courses_price,
                    group_concat(courses.name) as courses,
                    from_unixtime(MAX(class_trans.created_at)) as created_at
            from class_trans join courses on class_trans.class_register_id = courses.id
            where class_trans.status = 1 and class_trans.user_id = ?", [$user->id]));

            $user_info = [];

            if ($course_info[0]->courses_count > 0){

                $timestamp = strtotime($course_info[0]->created_at);


                $jdate = fa_to_en(jdate( 'Ymd' , $timestamp ));
                $user_info['courses'] = $course_info[0]->courses;
                $user_info['courses_price'] = $course_info[0]->courses_price;
                $user_info['courses_count'] = $course_info[0]->courses_count;
                $user_info['last_purchase_created_at'] = $course_info[0]->created_at;
                $user_info['last_purchase_created_at_shamsi'] = $jdate;
            }
            else {
                $user_info['courses'] = "";
                $user_info['courses_price'] = "";
                $user_info['courses_count'] = "";
                $user_info['last_purchase_created_at'] = "";
                $user_info['last_purchase_created_at_shamsi'] = "";
            }




            $response = [
                'success' => true,
                'data' => $user_info,
                'message' => 'user obtained successfully',
            ];
            return response()->json($response);

        }
        else {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'token was incorrect.',
            ];
            return response()->json($response , 401);
        }
    }

    public function logUserFootprint(Request $request){
        if(!isset($request->actionId) || !isset($request->courseId)){
            echo json_encode(array('status' => 'failed', 'source' => 'c', 'message' => 'not enough parameter', 'umessage' => 'ورودی کافی نیست'));
            exit();
        }
        $user = Auth::guard('api')->user();
        $courseId = $request->courseId;
        $actionId = $request->actionId;
	$time = time();
	if($user->role !== NULL){
	    echo json_encode(array('status' => 'done'));
 	    exit();
	}
        $courseRecord = DB::select(
            "SELECT id 
            FROM course_user 
            WHERE user_id = $user->id AND type_id = $courseId AND type IN ('class', 'bundle')"
        );
        if(count($courseRecord) !== 0){
            echo json_encode(array('status' => 'failed'));
	    exit();
        }
        DB::insert(
            "INSERT INTO user_footprints (
                user_id, course_id, action_id, date 
            ) VALUES ( 
                $user->id, $courseId, $actionId, $time 
            )"
        );
	echo json_encode(array('status' => 'done'));
    } 

}
