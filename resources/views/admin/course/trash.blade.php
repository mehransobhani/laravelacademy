@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">دوره ها</h4>
                {{ Breadcrumbs::render('course.trash') }}
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
                                                <th scope="col">تصویر</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = ($courses->currentPage()-1) * $courses->perPage() @endphp
                                            @forelse($courses as $key => $value)
                                                <tr>
                                                    <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->get_teacher->name ?? null }}</td>
                                                    <td><img class="table-image" src="{{ image_absolute_path('classes/'.$value->cover_img)  }}" alt="{{ $value->name }}"></td>
                                                    <td>
                                                        <button class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/course/trash/">حذف</button>
                                                        <button class="btn btn-info mx-1 honari-restore-row-btn" data-id="{{ $value->id }}" data-url="admin/course/restore/">بازیابی</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center bg-light">
                                                    <td scope="row" colspan="5">
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
                            <div class="modal fade" id="restoreModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">بازیابی دوره</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            آیا میخواهید دوره را بازیابی کنید؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                                            <form method="post" class="honari-restore-row-form">
                                                @csrf
                                                <button type="submit" class="btn btn-info">بله ، بازیابی کن !</button>
                                            </form>
                                        </div>
                                    </div>
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
                                            آیا میخواهید دوره را برای همیشه حذف کنید؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                                            <form method="post" class="honari-delete-row-form">
                                                @csrf
                                                @method('DELETE')
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
