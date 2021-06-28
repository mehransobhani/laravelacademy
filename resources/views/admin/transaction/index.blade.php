@extends('layouts.admin')

@section("content")
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">تراکنش ها</h4>
                {{ Breadcrumbs::render('transaction.index') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی تراکنش ها</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12 p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mt-3">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">کاربر</th>
                                            <th scope="col">دوره</th>
                                            <th scope="col">شماره فاکتور</th>
                                            <th scope="col">کد پیگیری کاربر</th>
                                            <th scope="col">مبلغ</th>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">ساعت</th>
                                            <th scope="col">نوع</th>
{{--                                            <th scope="col">کد پیگیری بانک</th>--}}
                                            <th scope="col">بانک</th>
                                            <th scope="col">وضعیت پرداخت</th>
                                        </tr>
                                        <tr >
                                            <form id="search-form"  method="get" >
                                                <th scope="col" class="text-center">&nbsp;</th>
                                                <th scope="col">
                                                    <input type="text" id="username" name="username" class="form-control form-control-sm" value="{{ request()->get('username') }}">
                                                </th>
                                                <th scope="col">
                                                    <input type="text" id="class" name="class" class="form-control form-control-sm" value="{{ request()->get('class') }}">
                                                </th>
                                                <th scope="col">
                                                    <input type="text" id="id" name="id" class="form-control form-control-sm" value="{{ request()->get('id') }}">
                                                </th>
                                                <th scope="col">
                                                    <input type="text" id="order_id" name="order_id" class="form-control form-control-sm" value="{{ request()->get('order_id') }}">
                                                </th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">
                                                    <input type="text" id="date" name="date" class="form-control form-control-sm direction-ltr" value="{{ request()->get('date') }}">
                                                </th>
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">&nbsp;</th>
{{--                                                <th scope="col">--}}
{{--                                                    <input type="text" id="bank_ref" name="bank_ref" class="form-control form-control-sm" value="{{ request()->get('bank_ref') }}">--}}
{{--                                                </th>--}}
                                                <th scope="col">&nbsp;</th>
                                                <th scope="col">
                                                    <select id="status" class="w-100" name="status">
                                                        <option value="" @if(is_null(request()->get('status'))) selected  @endif  >انتخاب</option>
                                                        <option @if(!is_null(request()->get('status')) && intval(request()->get('status')) === 1) selected  @endif value="1">موفق</option>
                                                        <option @if(!is_null(request()->get('status')) && intval(request()->get('status')) === 0) selected  @endif value="0">ناموفق</option>
                                                    </select>
                                                </th>
                                                <button class="d-none"></button>
                                            </form>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        @php $i = ($transactions->currentPage()-1) * $transactions->perPage() @endphp
                                        @forelse($transactions as $key => $value)
                                            <tr>
                                                <th scope="row" class="text-center">{{ ($key+1)+$i }}</th>
                                                <td>{{ $value->get_user ? $value->get_user->username : '-' }}</td>
                                                <td>
                                                    @if($value->get_class)
                                                        <a href="{{ route('course.edit' , $value->get_class->id ) }}">{{ $value->get_class->name }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->order_id }}</td>
                                                <td>{{ $value->price }}</td>
                                                <td>{{ jdate('Y/m/d', $value->created_at)  }}</td>
                                                <td>{{ jdate('H:i', $value->created_at)  }}</td>
{{--                                                <td>{{ $value->bank_ref }}</td>--}}
                                                <td>{{ $value->kind }}</td>
                                                <td>{{ $value->bank }}</td>
                                                @if($value->status === 0)
                                                    <td class="alert alert-danger">
                                                        ناموفق
                                                    </td>
                                                @else
                                                    <td class="alert alert-success">
                                                        موفق
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr class="text-center bg-light">
                                                <td scope="row" colspan="10">
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
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('js/persian-date.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.js') }}"></script>
    <script>
        $('#status').on('change' , function (){
            $('#search-form').submit();
        })
    </script>
@endsection
