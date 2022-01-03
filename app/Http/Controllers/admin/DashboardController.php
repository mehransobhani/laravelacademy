<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClassTrans;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){


        $class_trans = ClassTrans::where([
            ["status" , '=' , 1],
        ])->get();


        $past_week_sell = $class_trans->filter(function ($value, $key) {
            return $value->created_at > time()-7*24*60*60;
        });

        $past_week_sell->all();


        $past_month_sell = $class_trans->filter(function ($value, $key) {
            return $value->created_at > time()-31*24*60*60;
        });

        $past_month_sell->all();


        $past_year_sell = $class_trans->filter(function ($value, $key) {
            return $value->created_at > time()-366*24*60*60;
        });

        $past_year_sell->all();


        $current_month = jalali_to_gregorian(jdate("Y" , time()) ,jdate("m" , time()) , "۱" );
        $start_current_month_timestamp = strtotime ( $current_month[0].'-'.$current_month[1].'-'.$current_month[2] );

        $current_month_sell = $class_trans->whereBetween('created_at', [$start_current_month_timestamp, time() ]);
        $current_month_sell->all();

        $_last_month_sell = $class_trans->whereBetween('created_at', [$start_current_month_timestamp - (1*30*24*60*60) , $start_current_month_timestamp ]);
        $_last_month_sell->all();

        $_2nd_last_month_sell = $class_trans->whereBetween('created_at', [$start_current_month_timestamp - 2*30*24*60*60, $start_current_month_timestamp - 1*30*24*60*60]);
        $_2nd_last_month_sell->all();

        $_3rd_last_month_sell = $class_trans->whereBetween('created_at', [$start_current_month_timestamp - 3*30*24*60*60, $start_current_month_timestamp - 2*30*24*60*60]);
        $_3rd_last_month_sell->all();

        $_4rd_last_month_sell = $class_trans->whereBetween('created_at', [$start_current_month_timestamp - 4*30*24*60*60, $start_current_month_timestamp - 3*30*24*60*60]);
        $_4rd_last_month_sell->all();

        return view('admin.dashboard' , [
            'past_week_sell' => $past_week_sell->sum->price   ,
            'past_month_sell' => $past_month_sell->sum->price  ,
            'past_year_sell' => $past_year_sell->sum->price ,
            'monthly_sell' => [
                $_4rd_last_month_sell->sum->price ,
                $_3rd_last_month_sell->sum->price ,
                $_2nd_last_month_sell->sum->price ,
                $_last_month_sell->sum->price ,
                $current_month_sell->sum->price ,
            ] ,
            "month_name" => [
                jdate("F" , time()-4*31*24*60*60),
                jdate("F" , time()-3*31*24*60*60),
                jdate("F" , time()-2*31*24*60*60) ,
                jdate("F" , time()-31*24*60*60) ,
                jdate("F" , time()) ,
            ]
        ]);

    }

    public function courseclass_result(Request $request){

        $jdate_start_array = explode('/' , $request->start);
        if (is_integer( intval($jdate_start_array[0]) ) && is_integer( intval($jdate_start_array[1]) ) && is_integer( intval($jdate_start_array[2]) )){
            $geodate_start_array = jalali_to_gregorian( $jdate_start_array[0] , $jdate_start_array[1] , $jdate_start_array[2]  );
            $start_timestamp = strtotime ( $geodate_start_array[0].'-'.$geodate_start_array[1].'-'.$geodate_start_array[2] );
        }


        $jdate_end_array = explode('/' , $request->end);
        if (is_integer( intval($jdate_end_array[0]) ) && is_integer( intval($jdate_end_array[1]) ) && is_integer( intval($jdate_end_array[2]) )){
            $geodate_end_array = jalali_to_gregorian( $jdate_end_array[0] , $jdate_end_array[1] , $jdate_end_array[2]  );
            $end_timestamp = strtotime ( $geodate_end_array[0].'-'.$geodate_end_array[1].'-'.$geodate_end_array[2] );
        }

//        $collection = collect([
//            ['name' => 'Desk', 'price' => 200],
//            ['name' => 'Chair', 'price' => 100],
//            ['name' => 'Bookcase', 'price' => 150],
//        ]);
//
//        $sorted = $collection->sortBy('price');
//
//        $sorted->values()->all();
//        dd($sorted);


        $class_trans = ClassTrans::with('get_class')->where([["status" , '=' , 1]])->whereBetween("created_at" , [$start_timestamp , $end_timestamp+24*60*60])->get();

        $grouped_class_trans = $class_trans->groupBy('class_register_id');

        $beauty_grouped_class_trans = $grouped_class_trans->map(function($item, $key){
            return [
                "count" => $item->count(),
                "total_sell" => $item->sum("price"),
                "class_name" => $item[0] ? $item[0]->get_class ? $item[0]->get_class->name : null : null,
            ];
        });

        $sorted_beauty_grouped_class_trans = $beauty_grouped_class_trans->sortByDesc("count");
        echo '<div style="direction: rtl">';
        foreach ($sorted_beauty_grouped_class_trans as $key => $value){
                echo $value["class_name"] . '<br><br>';
                echo " فروش " . number_format($value["total_sell"]) . " تومان " . '<br><br>';
                echo " تعداد فروش کلاس " . $value["count"] . '<br><br>';
                echo "<hr>";
        }
        echo "<hr>";

        echo "آمار کل :".'<br><br>';

        echo " تعداد فروش کلاس ".  $class_trans->count() .'<br><br>';
        echo " فروش ".  number_format($class_trans->sum("price")) ." تومان ".'<br><br>';
        echo "<hr>";

        $link = "https://academy.honari.com/admin/classtrans-by-time-excel-export?start=" . $request->start . "&end=" . $request->end;
        echo "<a target='_blank' style='display:inline-block;text-decoration:none;margin-top:15px;background-color:#217346;color:#FFFFFF;padding: 5px 15px;' href='".$link."'>گزارش فروش این دوره</a>";

    }


}
