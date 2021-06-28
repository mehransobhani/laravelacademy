@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">دسته ها</h4>
                {{ Breadcrumbs::render('art.edit' , $art) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">ویرایش دسته</div>
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
                                    <form method="post" action="{{ route('art.update' , $art->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-4">
                                            <label for="artName">نام دسته <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="artName" id="artName" placeholder="نام دسته" value="{{ old('artName' , $art->artName) ?? null}}">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="art_url">slug (غیر قابل تغییر) <span class="required">*</span></label>
                                            <input type="text" disabled class="form-control make-slug-to" name="art_url" id="art_url" value="{{ old('art_url' , $art->art_url ) ?? null}}">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="description">توضیحات</label>
                                            <textarea name="description" class="form-control" id="description" cols="30" rows="5">{{ old('description' , $art->description ) ?? null}}</textarea>
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
