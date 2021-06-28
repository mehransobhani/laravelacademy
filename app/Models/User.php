<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    public $timestamps = false;

    use HasFactory, Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        "userlevel",
        "hubspot_mail",
        "timestamp",
        "valid",
        "mobile",
        "telephone",
        "postalCode",
        "address",
        "orders_count",
        "total_buy",
        "token",
        "gcmToken",
        "newGcmToken",
        "androidToken",
        "user_stock",
        "fname",
        "lname",
        "selectedArts",
        "area",
        "giftcode",
        "followers",
        "following",
        "user_key",
        "can_cash_pay",
        "last_update",
        "v_id",
        "national_code",
        "lat",
        "lng"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_courses()
    {
        return $this->belongsToMany(Course::class , 'course_user' , 'user_id' , 'type_id')->withPivot('create_at','type' , 'value' , 'value1' , 'description')->where([
            ['type', '=' ,'class'],
            ["status" , '=' , 1]
        ]);
    }



    public function get_teacher_course()
    {
        return $this->hasMany(Course::class , 'user_id' , 'id');
    }


}
