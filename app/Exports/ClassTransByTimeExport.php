<?php

namespace App\Exports;


use App\Models\ClassTrans;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassTransByTimeExport implements FromCollection , WithHeadings , ShouldAutoSize
{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
//        $start_time = persian_date_picker_to_timestamp(implode([$this->request->start , ' - 00:00:00']));
//         = persian_date_picker_to_timestamp(implode([$this->request->end , ' - 23:59:59']));
        $jdate_start_array = explode('/' , $this->request->start);
        if (is_integer( intval($jdate_start_array[0]) ) && is_integer( intval($jdate_start_array[1]) ) && is_integer( intval($jdate_start_array[2]) )){
            $geodate_start_array = jalali_to_gregorian( $jdate_start_array[0] , $jdate_start_array[1] , $jdate_start_array[2]  );
            $start_time = strtotime ( $geodate_start_array[0].'-'.$geodate_start_array[1].'-'.$geodate_start_array[2] );
        }


        $jdate_end_array = explode('/' , $this->request->end);
        if (is_integer( intval($jdate_end_array[0]) ) && is_integer( intval($jdate_end_array[1]) ) && is_integer( intval($jdate_end_array[2]) )){
            $geodate_end_array = jalali_to_gregorian( $jdate_end_array[0] , $jdate_end_array[1] , $jdate_end_array[2]  );
            $end_time = strtotime ( $geodate_end_array[0].'-'.$geodate_end_array[1].'-'.$geodate_end_array[2] );
        }

        $class_trans = ClassTrans::with('get_class')->where([["status" , '=' , 1]])->whereBetween("created_at" , [($start_time) , ($end_time+24*60*60)])->get();
        $class_trans = $class_trans->groupBy('class_register_id');

        $class_trans = $class_trans->map(function($item, $key){
            return [
                "class_name" => $item[0] ? $item[0]->get_class ? $item[0]->get_class->name : null : null,
                "total_sell" => $item->sum("price"),
                "count" => $item->count(),
            ];
        });
        return $class_trans->sortByDesc("count");
    }

    public function headings(): array
    {
        return [
            'نام کلاس',
            'قیمت فروش',
            'تعداد فروش',
        ];
    }

}
