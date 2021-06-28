@extends('layouts.admin')

@section("content")
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">تراکنش ها</h4>
                {{ Breadcrumbs::render('transaction.addUserClass') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">تمامی تراکنش ها</div>
                <div class="card-body direction-rtl">
                    <div class="container-fluid">
                        @if ($errors->any())
                            <div class="row">
                                <div class="alert alert-danger w-100">
                                    <ul class="mb-0 pr-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12 px-0 py-3">

                                    <form action="{{ route("transaction.storeUserClass") }}" method="POST">
                                        @csrf
                                            <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="username">شماره همراه</label>
                                                <input name="username" class="form-control" type="text" id="username">
                                            </div>

                                            <div class="form-group mb-5 col-md-6 px-0">
                                                <label for="class_id">کلاس</label>
                                                <select class="d-block w-100" name="class_id" id="class_id">
                                                    @foreach($courses as $key=>$value)
                                                        <option value="{{$value->id}}">
                                                            {{$value->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <button class="btn btn-info">ثبت</button>

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
    <script>
        $('#class_id').select2();
    </script>
@endsection
