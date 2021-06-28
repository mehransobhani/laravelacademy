<?php

namespace App\Models;

use App\Custom\Sitemap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Art extends Model
{
    protected $table = 'arts';
    public $timestamps = false;
    use HasFactory;

    public static function boot() {

        parent::boot();

        static::saved(function($item) {
            Sitemap::category();
        });


    }

    public function courses(){
        return $this->belongsToMany(Course::class )->where("status" , 1);
    }
}
