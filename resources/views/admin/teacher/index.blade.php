@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">اساتید</h4>
                {{ Breadcrumbs::render('teacher.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی اساتید</div>
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
{{--                                    <a href="{{ route('teacher.trash') }}" class="btn btn-danger mx-1">سطل زباله <i class="icon-trash"></i></a>--}}
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
                                                <th scope="col">درباره استاد</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = ($teachers->currentPage()-1) * $teachers->perPage() @endphp
                                            @forelse($teachers as $key => $value)
                                                <tr>
                                                    <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                    <td>{{ $value->name }}</td>
                                                    <td class="text-wrap">{{ $value->about }}</td>
                                                    <td>
                                                        <a href="{{ route('teacher.edit' ,  $value->id ) }}" class="btn btn-info mx-1">ویرایش</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="text-center bg-light">
                                                    <td scope="row" colspan="4">
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
                                    {{ $teachers->links() }}
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
