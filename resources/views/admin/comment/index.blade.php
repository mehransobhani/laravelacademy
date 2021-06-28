@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">کامنت ها</h4>
                {{ Breadcrumbs::render('comment.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی کامنت ها</div>
                <div class="card-body direction-rtl">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6 col-md-5 col-lg-4 col-12 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <label for="search">جستجو:&nbsp;&nbsp;</label>
                                        <form  method="get" class="search">
                                            <input type="hidden" name="visibilityStatus" value="<?php echo request()->get('visibilityStatus') ?>">
                                            <input type="text" id="search" name="search" class="form-control form-control-sm" value="{{ request()->get('search') }}">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 col-md-4 col-lg-3 col-12 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <label for="visibilityStatus"> وضعیت:</label>&nbsp;&nbsp;
                                        <form action="" id="visibilityStatusForm">
                                            <input type="hidden" name="search" value="<?php echo request()->get('search') ?>">
                                            <select class="form-control" name="visibilityStatus" id="visibilityStatus">
                                                <option value="-1"{{ $visibilityStatus==-1 ? 'selected' : null }}>انتخاب</option>
                                                <option value="0" {{ $visibilityStatus==0 ? 'selected' : null }}>حذف</option>
                                                <option value="1" {{ $visibilityStatus==1 ? 'selected' : null }}>تایید شده</option>
                                                <option value="2" {{ $visibilityStatus==2 ? 'selected' : null }}>در انتظار تایید</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mt-3">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col">دوره</th>
                                                <th scope="col">نام</th>
                                                <th scope="col">فرستنده</th>
                                                <th scope="col">تاریخ ارسال</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                             $i = ($comments->currentPage()-1) * $comments->perPage();
                                            foreach($comments as $key => $value){
                                            if($value->get_step && $value->get_step->get_course){
                                                $status_class = null;
                                                switch ($value->visibilityStatus) {
                                                    case 0:
                                                        $status_class = 'bg-danger text-white';
                                                        break;
                                                    case 1:
                                                        $status_class = 'bg-success';
                                                        break;
                                                    case 2:
                                                        $status_class = 'bg-light ';
                                                        break;
                                                }
                                                ?>
                                                <tr >
                                                    <th class="{{ $status_class }}" scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                    <td style="white-space: normal;">{{ $value->get_step->get_course->name ?? null }}</td>
                                                    <td style="white-space: normal;">{!! $value->comment !!}</td>
                                                    <td style="white-space: normal;">{{ $value->sender }}</td>
                                                    <td>{{ jdate("Y/m/d H:i:s" , $value->date) }}</td>
                                                    <td>
                                                        <a href="{{ route('comment.edit' ,  $value->id ) }}" class="btn btn-info mx-1">پاسخ</a>
                                                        <?php  if($value->visibilityStatus!=0) { ?> <button class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/comment/">حذف</button> <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 p-0">
                                    {{ $comments->links() }}
                                </div>
                            </div>
                            <div class="modal fade" id="deleteModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">حذف کامنت</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            آیا میخواهید کامنت را حذف کنید؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                                            <form method="post" class="honari-delete-row-form">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">بله ، حذف کن !</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        $('#visibilityStatus').on('change' , function (){
            $('#visibilityStatusForm').submit();
        })
    </script>
@endsection
