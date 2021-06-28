<?php

namespace App\Models;

use App\Models\Art;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gift extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'gifts';

    public function usage(){
        return $this->hasMany(GiftUsage::class );
    }

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'giftable');
    }

    public function categories()
    {
        return $this->morphedByMany(Art::class, 'giftable');
    }

    public static function is_discount_ok($code,$course_id){
        $gift = self::where(DB::raw('lower(code)'), '=',   strtolower($code))->first();
        $course = Course::find($course_id);

        if (!$gift) {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'کد تخفیف اشتباه میباشد.',
            ];
            return $response;
        }
        if (!$course) {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'مشکلی پیش آمد لطفا با پشتیبانی سایت ارتباط برقرار کنید.',
            ];
            return $response;
        }

        $course_price = $course->off && $course->off > 0 ? $course->off : $course->price;


        if ($gift->start_time && $gift->start_time > time()) {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'هنوز زمان استفاده از کد تخفیف نرسیده است.',
            ];
            return $response;
        }


        if ($gift->end_time && $gift->end_time < time()) {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'مهلت استفاده از تخفیف تمام شده.',
            ];
            return $response;
        }

        if ($gift->courses->count() > 0) {
            $course_ids = $gift->courses->pluck('id')->toArray();
            if (!in_array($course_id, $course_ids)) {
                $response = [
                    'success' => false,
                    'data' => [],
                    'message' => 'کد تخفیف به این کلاس اختصاص ندارد.',
                ];
                return $response;
            }
        }
        if ($gift->off > 0) {
            $discountRate = $gift->off > $course_price ? $course_price : $gift->off;
            $response = [
                'success' => true,
                'data' => ['discountRate' => $discountRate , 'discount_id' => $gift->id ],
                'message' => 'تخفیف اعمال شد.',
            ];
            return $response;
        }
        if ($gift->percent > 0) {

            $discountRate = $course_price * $gift->percent / 100;

            if ($gift->maximum && $discountRate > $gift->maximum) {
                $discountRate = $gift->maximum;
            }

            $response = [
                'success' => true,
                'data' => ['discountRate' => $discountRate , 'discount_id' => $gift->id ],
                'message' => 'تخفیف اعمال شد.',
            ];
            return $response;
        }

        $response = [
            'success' => false,
            'data' => [],
            'message' => 'مشکلی پیش آمد لطفا با پشتیبانی سایت ارتباط برقرار کنید.',
        ];
        return $response;

    }
}
