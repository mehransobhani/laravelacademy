@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">کد تخفیف</h4>
                {{ Breadcrumbs::render('gift.trash') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی کد تخفیف</div>
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
                            <div class="py-3 mr-auto">
                                <a href="{{ route('gift.trash') }}" class="btn btn-danger mx-1">سطل زباله <i class="icon-trash"></i></a>
                            </div>
                        </div>

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
                                            <th scope="col">عملیات</th>
                                        </tr>
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
                                                    <button class="btn btn-danger mx-1 honari-delete-row-btn" data-id="{{ $value->id }}" data-url="admin/gift/trash/">حذف</button>
                                                    <button class="btn btn-info mx-1 honari-restore-row-btn" data-id="{{ $value->id }}" data-url="admin/gift/restore/">بازیابی</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center bg-light">
                                                <td scope="row" colspan="8">
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
