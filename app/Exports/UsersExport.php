<?php

namespace App\Exports;

use App\Models\ClassTrans;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection , WithHeadings , ShouldAutoSize
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
        $start_time = persian_date_picker_to_timestamp(implode([$this->request->start_time , ' - 00:00']));
        $end_time = persian_date_picker_to_timestamp(implode([$this->request->end_time , ' - 23:59']));

        $class_user = ClassTrans::with("get_user")->where([
            ["status" , '=' , 1]
        ])->whereBetween("created_at" , [$start_time , $end_time])->get();




        $filtered_class_user = $class_user->map(function ($item, $key) {

            if ($item->get_user) {
                $user_id = $item->get_user->ex_user_id;
                $zero_length = 8 - strlen($user_id);
                $user_id = '1'.str_pad('', $zero_length , '0').$user_id;

                $user_name = $item->get_user->name;


                return [
                    "",
                    "1",
                    $user_name,
                    $user_name,
                    $user_id."_".$user_name,
                    "FALSE",
                    "TRUE",
                    "FALSE",
                    "",
                    "",
                    $user_id,
                ];
            }
            else {
                return [
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ];
            }
        });


        $filtered_class_user_array = $filtered_class_user->toArray();

        $arr = array_intersect_key($filtered_class_user_array, array_unique(array_map('serialize', $filtered_class_user_array)));


        return collect($arr);
    }

    public function headings(): array
    {
        return [
            'طرف حساب نوع قلم',
            'طرف حساب نوع',
            'طرف حساب نام',
            'طرف حساب نام خانوادگی',
            'طرف حساب عنوان',
            'طرف حساب تامین کننده',
            'طرف حساب مشتری',
            'طرف حساب واسطه',
            'طرف حساب کد اقتصادی',
            'طرف حساب کدملی/شناسه ملی',
            'طرف حساب کد',
        ];
    }
}
