<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Art;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function category(){
        $categories = Art::all();

        $response = [
            'success' => true,
            'data'    => CategoryResource::collection($categories),
            'message' => 'Categories obtained successfully.',
        ];
        return response()->json($response);
    }

    public function course(Request $request){

        $course = Course::query();
	
        $course->with(['get_teacher' , 'arts'])->withCount('get_steps')->where("status" , 1);
	
        if($request->category){
            if(is_array($request->category)){
                $course->whereHas('arts' , function($q) use ($request)
                {
                    $q->whereIn('arts.id', $request->category );
                });
            }
            else{
                $val = intval($request->category);
                $category = [$val];
                $course->whereHas('arts' , function($q) use ($category)
                {
                    $q->whereIn('arts.id', $category );
                });
            }
        }


        if ($request->search){
            $course->where('name' , 'LIKE' , '%'.$request->search.'%');
        }

        $course = $course->orderBy('create_at' , 'desc')->paginate(9);
	
        $course->makeHidden([
            'bundles',
            'create_at',
            'created_at',
            'description',
            'img',
            'message',
            'pivot',
            'priority',
            'updated_at',
            'teacher_about',
            'user_id',
        ]);


        $response = [
            'success' => true,
            'data'    => $course,
            'message' => 'Course obtained successfully.',
        ];
        return response()->json($response);
    }


    public function mostPopular(){
        $mostPopular = Banner::with(['get_art'  => function($query) {
            $query->select(['id','artName',"art_url"]);
        }])->where('type' , 'most-popular')->orderBy('position' , 'asc')->get();
        $response = [
            'success' => true,
            'data'    => $mostPopular,
            'message' => 'most popular arts obtained successfully.',
        ];
        return response()->json($response);
    }

    public function ourOffer(){
        $ourOffer = Banner::with(['get_course'])->where('type' , 'our-offer')->orderBy('position' , 'asc')->get();

        $response = [
            'success' => true,
            'data'    => $ourOffer,
            'message' => 'our offer courses obtained successfully.',
        ];
        return response()->json($response);
    }


    public function courseSingle($course_urlfa){
        $user = Auth::guard('api')->user();

        $course = Course::query();

        $course->with([
            'get_teacher' ,
            'arts' => function($query) {
                $query->addSelect(['artName', 'catID' ,'art_url']);
            } ,
            'get_steps' => function($query) {
                $query->addSelect(['id', 'name', 'short_desc' ,'class_id' , 'img' , 'order' , 'urlKey']);
        } , 'get_users']);


        $course->where([
            ['urlfa' , '=' ,$course_urlfa],
        ]);

        if ($user){
            if (!in_array($user->role , ['admin', 'contenter', 'writer'])){
                $course->where([
                    ["status" , '=' ,1],
                ]);
            }
        }
        else{
            $course->where([
                ["status" , '=' ,1],
            ]);
        }


        $course =   $course->first();

        if (!$course){
            return response()->json(['message'=> '404 not find'], 404);
        }
        $course->makeHidden(["member_count" , "img"]);
        $course->arts->makeHidden(["pivot"]);

        $course->is_owner = false;

        $users_array = $course->get_users->pluck('id')->toArray();
        $course->member_count = count($users_array);
        if ($user){
            if (in_array($user->role , ['admin' , 'teacher' , 'contenter' , 'writer']) ){
                $course->is_owner = true;
            }
            else if (in_array($user->id , $users_array)){
                $course->is_owner = true;
            }
	    if($user->role === NULL){
                $time = time();
                $courseRecord = DB::select(
                    "SELECT id 
                    FROM course_user 
                    WHERE user_id = $user->id AND type_id = $course->id AND type IN ('class', 'bundle') "
                );
                if(count($courseRecord) === 0){
                    DB::insert(
                        "INSERT INTO user_footprints (
                            user_id, course_id, action_id, date
                        ) VALUES (
                            $user->id, $course->id, 1, $time
                        )"
                    );
                }
            }
        }

        $course_array = $course->toArray();
        unset($course_array['get_users']);

        $response = [
            'success' => true,
            'data'    => $course_array,
            'message' => 'Course obtained successfully.',
        ];
        return response()->json($response);
    }

    public function stepSingle($step_urlkey){
	//var_dump(1);die();

        $step = Step::with(['get_course.get_steps' => function($query) {
            $query->addSelect(['id', 'name', 'class_id' , 'img' , 'order' , 'urlKey']);
        }])->where('urlkey' , $step_urlkey)->where('status', 1)->first();
        $user = Auth::guard('api')->user();
	//var_dump($user);die();

        if (!$step){
            return response()->json(['message'=> '404 not find'], 404);
        }

	if($step->order === 1 && $user->role === NULL){
            $time = time();
            $courseRecord = DB::select(
                "SELECT id 
                FROM course_user 
                WHERE user_id = $user->id AND type_id = $step->class_id AND type IN ('class', 'bundle')"
            );
            if(count($courseRecord) === 0){
                DB::insert(
                    "INSERT INTO user_footprints (
                        user_id, course_id, action_id, date
                    ) VALUES (
                        $user->id, $step->class_id, 2, $time
                    )"
                );
            }
        }

        if ($step->order !== 1)
        {
	    //var_dump($step);die();
	    //var_dump($user);die();
            if($user){
                if(!in_array($user->role , ['admin' , 'contenter', 'writer'])){
		    //var_dump($user);die();
                    if($user->get_courses){
                        $user_courses_id = $user->get_courses->pluck("id")->toArray();
			//var_dump($user_course_id);die();
                        if(!in_array($step->get_course->id,$user_courses_id)){
                            return response()->json(['message'=> 'user does not have access.'] , 403);
                        }
                    }
                    else{
                        return response()->json(['message'=> 'user does not have any course.' ], 403);
                    }
                }
            }
            else{
                return response()->json(['message'=> 'user is not authenticate.' ], 401);
            }
        }


        $step->makeHidden([
            "sectionID" ,
            "level" ,
            "likes" ,
            "comments" ,
            "views"  ,
            "downloads",
            "relatedProds",
            "relatedTutorials",
            "suppliesCategories",
            "author",
            "featured",
            "collections",
            "tags",
            "video_id",
            "material",
            "tools",
            "category",
            "prerequisites",
            "steps",
            "owner_id",
            "bundles",
            "aparat",
            "video_cover",
            "match_id",
            "owner",
            "needed_time",
            "redirect",
            "reviewRequest",
        ]);

        $step->makeHidden([
            "create_at",

            ]);

        $response = [
            'success' => true,
            'data'    => ['step' => $step ],
            'message' => 'Step obtained successfully.',
        ];
        return response()->json($response);
    }

    public function stepComments ($step){

        $comments = Comment::with('get_reply.get_user','get_user')->orderBy('date' , 'desc')->where([
            ['onIDofSection' , $step],
            ['replyToID' , 0],
            ['onSection' , 3],
            ['visibilityStatus' , 1],
        ])->paginate(10);

        $response = [
            'success' => true,
            'data'    => ['comments' => $comments ],
            'message' => 'comments obtained successfully.',
        ];

        return response()->json($response);
    }

    public function addReplyComment(Request $request){


        $validator = Validator::make($request->all(), [
            'comment' => ['required'],
            'replyToID' => ['required'],
            'onIDofSection' => ['required'],
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => $validator->messages(),
                'message' => 'validation error.',
            ];

            return response()->json($response  , 400);

        }

        $user = Auth::guard('api')->user();


        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->date = time();
        $comment->sender = $user->mobile;
        $comment->user_id = $user->id;
        $comment->replyToID = $request->replyToID;
        $comment->onSection = 3;
        $comment->onIDofSection = $request->onIDofSection;
        if (in_array($user->role , ['admin' , 'contenter', 'writer'])){
            $comment->visibilityStatus = 1;
        }
        else{
            $comment->visibilityStatus = 2;
        }
        $comment->receiver = '';
        $comment->onSection = 3;
        $comment->repliersId = 0;
        $comment->isReported = 0;
        $comment->EditedAt = time();
        $comment->isNew = 0;
        $comment->save();



        $response = [
            'success' => true,
            'data'    => [],
            'message' => 'reply added successfully.',
        ];

        return response()->json($response);

    }

    public function searchPreview(Request $request){
        $courses = Course::where([
            ['name' , 'LIKE' , '%'.$request->q.'%'],
            ["status" , '=' , 1]
        ])->select('id' , 'name' , 'urlfa' , 'img' , 'price' , 'kind')->get();

        $arts = Art::where('artName' , 'LIKE' , '%'.$request->q.'%')->select('id' , 'artName' , 'art_url')->get();

        $response = [
            'success' => true,
            'data'    => [
                'courses' => $courses,
                'arts' => $arts
            ],
            'message' => 'these are search preview.',
        ];

        return response()->json($response);
    }

    public function categoryPosts($categoryKey){
        $art = Art::select('id','artName','art_url' , "description")->where("art_url" , $categoryKey)->first();


        if (!$art){
            $response = [
                'success' => true,
                'data'    => [],
                'message' => 'no art found.',
            ];
            return response()->json($response , 404);

        }

        $courses = $art->courses()->orderBy('create_at' , 'desc')->paginate(9);


        $courses->makeHidden([
            'bundles',
            'create_at',
            'created_at',
            'description',
            'img',
            'message',
            'pivot',
            'priority',
            'updated_at',
            'teacher_about',
            'user_id',
        ]);

        $response = [
            'success' => true,
            'data'    => ["category" => $art , "courses" => $courses ],
            'message' => 'courses retrieved successfully.',
        ];
        return response()->json($response);



    }

    public function bundleSingle($slug){
        $user = Auth::guard('api')->user();

        $bundle = Course::where("urlfa" , $slug)->with('related')->first();

        if (!$bundle){
            return response()->json(['message'=> '404 not find'], 404);
        }

        $bundle->is_owner = false;

        $users_array = $bundle->get_users->pluck('id')->toArray();
        $bundle->member_count = count($users_array);
        if ($user){
            if (in_array($user->role , ['admin' , 'contenter' , 'teacher' , 'writer'])){
                $bundle->is_owner = true;
            }
            else if (in_array($user->id , $users_array)){
                $bundle->is_owner = true;
            }
        }

        $response = [
                'success' => true,
                'data'    => ["bundle" => $bundle ],
                'message' => 'bundle retrieved successfully.',
            ];
        return response()->json($response);

    }

    public function filepond(Request $request){
        $customAttributes = [
            'filepond' => 'تصویر',
        ];

        $validator = Validator::make($request->all() ,[
            'filepond' => 'required|image',
        ], [] ,$customAttributes);


        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->messages(),
                'message' => 'validation error.',
            ];
            return response()->json($response, 400);
        }

        $directory = Storage::disk('warehouse')->put("/images/projects", $request->filepond);
        $directory = basename($directory);
        return $directory;
    }

    public function filepondrevert(Request $request){
        Storage::disk('warehouse')->delete("/images/projects/" . $request->getContent());
        return "ok";
    }

    public function getfilepond(Request $request){
        $img = file_get_contents("http://class.honari.devel/warehouse/images/projects/".$request->load);
        return response($img)->header('Content-type','image/png');
        return Image::make($request->load)->response();
    }




}
