@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">اساتید</h4>
                {{ Breadcrumbs::render('teacher.edit' , $teacher) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">ویرایش استاد</div>
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
                                    <form method="post" action="{{ route('teacher.update' , $teacher->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-4">
                                            <label for="about">درباره استاد<span class="required">*</span></label>
                                            <textarea type="text" rows="5" class="form-control" name="about" id="about" placeholder="درباره استاد" >{{ old('teacherName' , $teacher->about) ?? null}}</textarea>
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
@endsection
