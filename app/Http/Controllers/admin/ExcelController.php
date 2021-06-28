<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function transactionExport(\Illuminate\Http\Request $request){
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ClassTransExport($request), 'classTransaction_'.str_replace('/' , '-' , fa_to_en($request->start_time)).'_'.str_replace('/' , '-' , fa_to_en($request->end_time)).'.xlsx');
    }

    public function transactionExportIndex(){
        return view('admin.excel.transaction');
    }

    public function userExport(\Illuminate\Http\Request $request){
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersExport($request), 'classUsers_'.str_replace('/' , '-' , fa_to_en($request->start_time)).'_'.str_replace('/' , '-' , fa_to_en($request->end_time)).'.xlsx');
    }

    public function userExportIndex(){
        return view('admin.excel.user');
    }
}
