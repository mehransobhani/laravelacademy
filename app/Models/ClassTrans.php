<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTrans extends Model
{
    use HasFactory;
    protected $table = 'class_trans';
    public $timestamps = false;

    public function get_user(){
        return $this->hasOne(User::class , 'id' , 'user_id');
    }

    public function get_class(){
        return $this->hasOne(Course::class , 'id' , 'class_register_id');
    }

    public static function successful_trans($class_trans , $course , $amount , $class_id , $bank_ref){

        if ($class_trans->status !== 1) {
            $class_trans->status = 1;
            $class_trans->save();


            if ($class_trans->gift_id){
                $gift_user = new GiftUsage;
                $gift_user->user_id = $class_trans->user_id;
                $gift_user->off = $course->off ? $course->off - $amount :  $course->price - $amount;
                $gift_user->gift_id = $class_trans->gift_id;
                $gift_user->course_id = $class_id;
                $gift_user->order_id = $class_trans->id;
                $gift_user->created_at = time();


                $gift_user->save();
            }


            $user = User::find($class_trans->user_id);


            course_user::registerUserCourse($class_trans->user_id , $course , $amount);



            if ($user && $user->username){
                $api = new \Kavenegar\KavenegarApi( "7358684B76496D5079754170615766594F534A31724130495344335152326D4F" );
                $sender = "10000055373520";
                $message = "همراه هنری سلام عضویت شما در کلاس ".$course->name." با موفقیت انجام شد .
جهت پرسیدن سوالات و اشکالات خود در زمینه ی کلاس به چت داخل سایت مراجعه نمایید.";
                $receptor = [$user->username];
                $result = $api->Send($sender,$receptor,$message);
            }


        }

        return true;


    }

}
