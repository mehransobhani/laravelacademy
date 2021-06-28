@extends('layouts.admin')

@section("content")

    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">خروجی فروش</h4>
                {{ Breadcrumbs::render('excel') }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">خروجی فروش</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 px-0 py-3">
                                <form method="post" target="_blank" action="{{ route("transaction.excel") }}">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="start_time">تاریخ شروع<span class="required">&nbsp*&nbsp</span></label>
                                            <input name="start_time" id="start_time" type="text" class="form-control" autocomplete="off" />
                                        </div>
                                        <div class="form-group mb-4 col-xl-3 col-md-6">
                                            <label for="end_time">تاریخ پایان<span class="required">&nbsp*&nbsp</span></label>
                                            <input name="end_time" id="end_time" type="text" class="form-control"  autocomplete="off" />
                                        </div>
                                    </div>
                                    <button class="btn btn-primary">ارسال</button>
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
        $(document).ready(function() {
            $("#start_time").pDatepicker({
                "format": "L",
                "timePicker": {
                    "enabled": true,
                    "step": 1,
                    "hour": {
                        "enabled": false,
                        "step": null
                    },
                    "minute": {
                        "enabled": false,
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
                "format": "L",
                "timePicker": {
                    "enabled": true,
                    "step": 1,
                    "hour": {
                        "enabled": false,
                        "step": null
                    },
                    "minute": {
                        "enabled": false,
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
        $('#status').on('change' , function (){
            $('#search-form').submit();
        })
    </script>
@endsection
