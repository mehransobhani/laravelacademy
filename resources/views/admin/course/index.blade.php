@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">دوره ها</h4>
                {{ Breadcrumbs::render('course.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی دوره ها</div>
                <div class="card-body direction-rtl">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-6 col-md-5 col-lg-4 col-12 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <label for="search">جستجو:&nbsp;&nbsp;</label>
                                        <form  method="get" class="search">
                                            <input type="text" id="search" name="search" class="form-control form-control-sm" value="{{ request()->get('search') }}">
                                        </form>
                                    </div>
                                </div>
{{--                                <div class="py-3 mr-auto">--}}
{{--                                    <a href="{{ route('course.trash') }}" class="btn btn-danger mx-1">سطل زباله <i class="icon-trash"></i></a>--}}
{{--                                </div>--}}
                            </div>

                            <div class="row">
                                <div class="col-sm-12 p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mt-3">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col">نام</th>
                                                <th scope="col">مدرس</th>
                                                <th scope="col">تاریخ ایجاد</th>
                                                <th scope="col">تصویر</th>
                                                <th scope="col">قیمت</th>
                                                <th scope="col">تعداد خرید</th>
                                                <th scope="col">وضعیت</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = ($courses->currentPage()-1) * $courses->perPage() @endphp
                                            @forelse($courses as $key => $value)
                                                <tr>
                                                    <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                    <td><a target="_blank" href="{{ config("app.class_next_url")."/courses/".$value->urlfa }}">{{ $value->name }}</a></td>
                                                    <td>{{ $value->get_teacher->name ?? null }}</td>
                                                    <td><?php echo jdate('Y/m/d' , $value->create_at) ?></td>
                                                    <td><img class="table-image" src="{{ image_absolute_path('classes/' . $value->cover_img) }}" alt="{{ $value->name }}"></td>
                                                    <td><?php
                                                        if ($value->off){
                                                            echo '<del>'.$value->price.'</del>'.'&nbsp;'.$value->off;
                                                        } else {
                                                            echo $value->price;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $value->get_users->count()  }}</td>
                                                    <td>{{ $value->get_status() ?? null }}</td>
                                                    <td>
                                                        <a href="{{ route('course.steps' ,  $value->id ) }}" class="btn btn-warning mx-1">جلسات</a>
                                                        <a href="{{ route('course.edit' ,  $value->id ) }}" class="btn btn-info mx-1">ویرایش</a>
{{--                                                        <button class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/course/">حذف</button>--}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center bg-light">
                                                    <td scope="row" colspan="9">
                                                        نتیجه ای یافت نشد
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 p-0">
                                    {{ $courses->links() }}
                                </div>
                            </div>
                            <div class="modal fade" id="deleteModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">حذف دوره</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            آیا میخواهید دوره را حذف کنید؟
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
@endsection
