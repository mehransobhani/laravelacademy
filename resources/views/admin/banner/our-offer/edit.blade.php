@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">هنر های پرطرفدار</h4>
                {{ Breadcrumbs::render('banner.ourOffer.edit' , $banner) }}
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
                            <div class="col-md-6 col-12 p-0">
                                <form method="post" action="{{ route('banner.ourOffer.update' , $banner->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-4">
                                        <label for="course">دوره <span class="required">*</span></label>
                                        <select name="course" id="course"  class="d-block w-100">
                                            @foreach($courses as $key => $value)
                                                <option value="{{ $value->id }}"  {{ $value->id == $banner->get_course->id ? 'selected' : null }}  >{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-2">ویرایش اطلاعات</button>
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
        $('#course').select2();
    </script>
@endsection
