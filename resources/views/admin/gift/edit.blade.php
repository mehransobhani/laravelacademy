@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">تخفیف ها</h4>
                {{ Breadcrumbs::render('gift.create') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تخفیف جدید</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
                        <div class="row">
                            @if ($errors->any())
                                <div class="alert alert-danger w-100">
                                    <ul class="mb-0 pr-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12 p-0">
                                <form method="post" action="{{ route('gift.update' , $gift->id ) }}">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="code">کد تخفیف<span class="required">&nbsp*&nbsp</span></label>
                                            <input type="text" class="form-control direction-ltr" name="code" id="code" placeholder="کد تخفیف" value="{{ old('code' , $gift->code ) }}" disabled>
                                        </div>
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="off">مبلغ تخفیف<span class="required">&nbsp*&nbsp</span></label>
                                            <input type="text" class="form-control IRANSans direction-ltr" name="off" id="off" value="{{ old('off' , $gift->off ) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="percent">درصد تخفیف<span class="required">&nbsp*&nbsp</span></label>
                                            <input type="text" class="form-control IRANSans direction-ltr" name="percent" id="percent" value="{{ old('percent' , $gift->percent ) }}">
                                        </div>
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="maximum">حداکثر مبلغ تخفیف</label>
                                            <input type="text" class="form-control IRANSans direction-ltr" name="maximum" id="maximum" value="{{ old('maximum' , $gift->maximum ) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="start_time">تاریخ شروع<span class="required">&nbsp*&nbsp</span> | {{ jdate( 'd / m / Y - H:i' , $gift->start_time  ) }} </label>
                                            <input name="start_time" id="start_time" type="text" class="form-control" value={{ old('start_time'  , $gift->start_time )}} />
                                        </div>
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="end_time">تاریخ پایان {{ $gift->end_time>0 ? ' | ' . jdate( 'd / m / Y - H:i' , $gift->end_time ) : null }} </label>
                                            <input name="end_time" id="end_time" type="text" class="form-control" value="{{ old('end_time' , $gift->end_time )}}" />
                                            <?php $infinity = $gift->end_time==null ? true : false ?>
                                            <input type="checkbox" class="form-check-input" name="infinity" id="infinity" {{ old('infinity' , $infinity ) ? 'checked' : null }} />
                                            <label for="infinity" class="form-check-label">نامحدود</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4 col-lg-6">
                                            <label for="courses">مختص کلاس</label>
                                            <select multiple class="form-control" id="courses" name="courses[]">
                                                @foreach($courses as $key=>$value)
                                                    <option value="{{$value->id}}" {{ is_array(old('courses' , $gift->courses->pluck('id')->toArray() )) ? in_array( $value->id , old('courses' ,  $gift->courses->pluck('id')->toArray() ) ) ? 'selected' : null : null }} >{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4 col-12">
                                            <label for="description">توضیحات</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description' , $gift->description ) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-4 col-12">
                                            <input type="checkbox" class="form-check-input" id="reusable" name="reusable" {{ old('reusable' , $gift->reusable ) ? 'checked' : null }}>
                                            <label class="form-check-label" for="reusable" >بیش از یکبار استفاده</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-2">ثبت اطلاعات</button>
                                </form>
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
        let off_cleave = new Cleave('#off', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        let percent_cleave = new Cleave('#percent', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#start_time").pDatepicker({
                "format": "L - HH:mm",
                "timePicker": {
                    "enabled": true,
                    "step": 1,
                    "hour": {
                        "enabled": true,
                        "step": null
                    },
                    "minute": {
                        "enabled": true,
                        "step": null
                    },
                    "second": {
                        "enabled": false,
                        "step": null
                    },
                    "meridian": {
                        "enabled": false
                    }
                }
            });
            $("#end_time").pDatepicker({
                "format": "L - HH:mm",
                "timePicker": {
                    "enabled": true,
                    "step": 1,
                    "hour": {
                        "enabled": true,
                        "step": null
                    },
                    "minute": {
                        "enabled": true,
                        "step": null
                    },
                    "second": {
                        "enabled": false,
                        "step": null
                    },
                    "meridian": {
                        "enabled": false
                    }
                }
            });
        });
        $(document).ready(function() {
            $('#courses').select2();
        });
    </script>
@endsection
