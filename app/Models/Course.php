<?php

namespace App\Models;

use App\Custom\Sitemap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    /**
     * @var mixed
     */
    private $status;
    use HasFactory;



    public static function boot() {

        parent::boot();

        static::saved(function($item) {
            Sitemap::class();
        });


    }




    public function get_teacher(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function get_steps(){
        return $this->hasMany(Step::class , 'class_id')->orderBy('order' , 'asc');
    }

    public function arts(){
        return $this->belongsToMany(Art::class );
    }

    public function gifts()
    {
        return $this->morphToMany(Gift::class, 'taggable');
    }


    public function get_users()
    {
        return $this
            ->belongsToMany(User::class , 'course_user' , 'type_id' , 'user_id')
            ->withPivot('create_at','type' , 'value' , 'value1' , 'description')
            ->whereIn('type',['class' , 'bundle']);
    }


    public function get_status(){
        switch ($this->status) {
            case 0:
                return 'پیش نویس';
            case 1:
                return 'انتشار یافته';
            default:
                return "...problem...contact admin please";
        }
    }

    public function related(){
        return $this->belongsToMany(Course::class , 'bundle_course', 'bundle_id' , 'course_id' )->withPivot('price');
    }



}
