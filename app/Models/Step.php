<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $table = 'stepBySteps';
    public $timestamps = false;
    use HasFactory;

    public function get_course(){
        return $this->hasOne(Course::class , 'id' , 'class_id');
    }
}
