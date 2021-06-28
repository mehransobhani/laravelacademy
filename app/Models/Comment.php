<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    use HasFactory;
    public $timestamps = false;

    public function get_step(){
        return $this->hasOne(Step::class , 'id' , 'onIDofSection');
    }
    public function get_reply(){
        return $this->hasMany(Comment::class , 'replyToID' , 'id')->where([
            ['onSection' , 3],
            ['visibilityStatus' , 1],
        ]);
    }
    public function get_parent(){
        return $this->hasOne(Comment::class , 'id' , 'replyToID');
    }
    public function  get_user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
