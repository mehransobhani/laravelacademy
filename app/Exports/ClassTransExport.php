<?php

namespace App\Exports;


use App\Models\ClassTrans;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassTransExport implements FromCollection , WithHeadings , ShouldAutoSize
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

        $class_trans = ClassTrans::with('get_class' , "get_user")->where([
            ["status" , '=' , 1]
        ])->whereBetween("created_at" , [$start_time , $end_time])->get();





        return $class_trans->map(function ($item, $key) {

            if ($item->get_user) {
                $user_id = $item->get_user->ex_user_id;

                $zero_length = 8 - strlen($user_id);

                $user_id = '1'.str_pad('', $zero_length , '0').$user_id;
            } else {
                $user_id = '-';
            }


            return [
                $item->get_class ? $item->get_class->name : '-',
                $user_id,
                $item->id,
                $item->order_id,
                '"'.strval($item->bank_ref).'"',
                intval($item->price)*10,
                fa_to_en(jdate('Y/m/d', $item->created_at)),
                $item->bank,

            ];
        });

    }

    public function headings(): array
    {
        return [
            'کلاس',
            'شماره کاربر',
            'شماره فاکتور',
            'کد پیگیری',
            'شماره ارجاع بانک',
            'قبمت (ریال)',
            'تاریخ',
            'درگاه',
        ];
    }

}
