@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">کد تخفیف</h4>
                {{ Breadcrumbs::render('gift.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی کد تخفیف</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
{{--                        <div class="row">--}}
{{--                            <div class="py-3 mr-auto">--}}
{{--                                <a href="{{ route('gift.trash') }}" class="btn btn-danger mx-1">سطل زباله <i class="icon-trash"></i></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="row">
                            <div class="col-sm-12 p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mt-3">
                                        <thead>

                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">کد تخفیف</th>
                                            <th scope="col">مبلغ تخفیف</th>
                                            <th scope="col">درصد تخفیف</th>
                                            <th scope="col">تعداد استفاده</th>
                                            <th scope="col">زمان شروع</th>
                                            <th scope="col">زمان پایان</th>
                                            <th scope="col">مختص کلاس</th>
                                            <th scope="col">عملیات</th>
                                        </tr>

                                        <form  method="get">
                                            <tr>
                                                <th colspan="1"> </th>
                                                <th>
                                                    <input type="text" id="search-name" name="search[name]" class="form-control form-control-sm" value="{{ request()->get('search')['name'] ?? null }}">
                                                </th>
                                                <th colspan="1"> </th>
                                                <th colspan="1"> </th>
                                                <th colspan="1"> </th>
                                                <th colspan="1"> </th>
                                                <th colspan="1"> </th>
                                                <th>
                                                    <input type="text" id="search-course" name="search[course]" class="form-control form-control-sm" value="{{ request()->get('search')['course'] ?? null }}">
                                                </th>
                                                <th colspan="1"> </th>
                                                <button class="d-none"> </button>
                                            </tr>
                                        </form>
                                        </thead>
                                        <tbody>
                                        @php $i = ($gifts->currentPage()-1) * $gifts->perPage() @endphp
                                        @forelse($gifts as $key => $value)
                                            <tr>
                                                <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                <td class="IRANSans">{{ $value->code }}</td>
                                                <td>{{ $value->off }}</td>
                                                <td>{{ $value->percent }}</td>
                                                <td>{{ $value->usage->count() }}</td>
                                                <td><?php echo jdate('Y/m/d -- h:m' , $value->start_time) ?></td>
                                                <td><?php echo $value->end_time != 0 ?  jdate('Y/m/d -- h:m' , $value->end_time) : 'نامحدود' ?></td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @foreach($value->courses as $key2=>$value2)
                                                            <a class="bg-secondary text-white rounded text-small mt-1 p-1 text-center" href="{{ route('course.edit' ,  $value2->id) }}">{{ $value2->name }}</a>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('gift.edit' , $value->id) }}">ویرایش</a>
                                                    <button type="button" class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/gift/">حذف</button>
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
                                {{ $gifts->links() }}
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
