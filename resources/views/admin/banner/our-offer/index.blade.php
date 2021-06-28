@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">هنر های پرطرفدار</h4>
                {{ Breadcrumbs::render('banner.ourOffer.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">هنر های پرطرفدار</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12 p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mt-3">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">موقعیت</th>
                                            <th scope="col">پست</th>
                                            <th scope="col">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($banners as $key => $value)
                                            <tr>
                                                <td style="width: 5%">{{ $value->position }}</td>
                                                <td>
                                                    <img class="table-image" src="{{ image_absolute_path('classes/'.$value->get_course->cover_img ?? null) }}" alt="">
                                                    <a href="{{ route('course.edit' ,  $value->get_course->id ?? null ) }}" class="mr-3">
                                                        {{ $value->get_course->name ?? null }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('banner.ourOffer.edit' , $value->id) }}" class="btn btn-info">ویرایش</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center bg-light">
                                                <td scope="row" colspan="3">
                                                    نتیجه ای یافت نشد
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
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
