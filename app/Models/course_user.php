<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_user extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'course_user';

    public static function registerUserCourse($user_id , $course , $purchase_amount){
        if ($course->kind === 'bundle'){

            $user_course = new course_user;

            $user_course->create_at = time() ;
            $user_course->user_id = $user_id;
            $user_course->type = 'bundle';
            $user_course->type_id = $course->id;
            $user_course->price = $course->price;
            $user_course->purchase_amount = $purchase_amount;

            $user_course->save();

            foreach ($course->related as $key=>$value){
                $user_course = new course_user;

                $user_course->create_at = time() ;
                $user_course->user_id = $user_id;
                $user_course->type = 'class';
                $user_course->type_id = $value->id;
                $user_course->price = $value->price;
                $user_course->purchase_amount = $value->pivot->price;

                $user_course->save();

            }
        }
        else {

            $user_course = new course_user;

            $user_course->create_at = time() ;
            $user_course->user_id = $user_id;
            $user_course->type = 'class';
            $user_course->type_id = $course->id;
            $user_course->price = $course->price;
            $user_course->purchase_amount = $purchase_amount;

            $user_course->save();
        }
    }


}
