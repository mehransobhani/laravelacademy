@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">بسته ها</h4>
                {{ Breadcrumbs::render('bundle.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی بسته ها</div>
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
                                                <th scope="col">عنوان</th>
                                                <th scope="col">تاریخ ایجاد</th>
                                                <th scope="col">نصویر</th>
                                                <th scope="col">قیمت</th>
                                                <th scope="col">تعداد خرید</th>
                                                <th scope="col">وضعیت</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = ($bundles->currentPage()-1) * $bundles->perPage() @endphp
                                            @forelse($bundles as $key => $value)
                                                <tr>
                                                    <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                    <td><a target="_blank" href="{{ config("app.class_next_url")."/bundles/".$value->urlfa }}">{{ $value->name }}</a></td>
                                                    <td>{{ jdate( 'Y/m/d' , strtotime($value->created_at)) }}</td>
                                                    <td>
                                                        <img class="table-image" src="{{ image_absolute_path('classes/' . $value->cover_img) }}" >
                                                    </td>
                                                    <td>{{ number_format($value->price) }}</td>
                                                    <td>{{ $value->get_users->count() }}</td>
                                                    <td>{{ $value->get_status() ?? null }}</td>
                                                    <td>
                                                        <a href="{{ route('bundle.edit' ,  $value->id ) }}" class="btn btn-info mx-1">ویرایش</a>
{{--                                                        <button class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/art/">حذف</button>--}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center bg-light">
                                                    <td scope="row" colspan="6">
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
                                    {{ $bundles->links() }}
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
