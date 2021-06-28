<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function get_course(){
        return $this->hasOne(Course::class , 'id' , 'course_id');
    }
    public function get_art(){
        return $this->hasOne(Art::class , 'id' , 'art_id');
    }


}
