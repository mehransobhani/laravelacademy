@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">جلسات</h4>
                {{ Breadcrumbs::render('course.steps.create' , $course) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">جلسه جدید برای : <span class="text-secondary">{{ $course->name }}</span></div>
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
                            <div class="col-12 p-0">
                                <form method="post" id="course-create-form" action="{{ route('course.steps.store' , $course->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-4 col-md-6 px-0">
                                        <label for="name">نام جلسه <span class="required">*</span></label>
                                        <input type="text" class="form-control make-slug-from" name="name" id="name" value="{{ old('name') ?? null}}">
                                    </div>
                                    <div class="form-group mb-4 col-md-6 px-0">
                                        <label for="urlKey">slug (غیر قابل تغییر) <span class="required">*</span></label>
                                        <input type="text" class="form-control make-slug-to" name="urlKey" id="urlKey" value="{{ old('urlKey') ?? null}}">
                                    </div>
                                    <div class="form-group mb-4 col-md-6 px-0">
                                        <label for="short_desc">خلاصه توضیحات <span class="required">*</span></label>
                                        <textarea class="form-control" name="short_desc" id="short_desc" rows="5">{{ old('short_desc') ?? null}}</textarea>
                                    </div>
                                    <div class="form-group mb-4 col-md-6 px-0">
                                        <label for="order">شماره جلسه <span class="required">*</span></label>
                                        <input type="text" class="form-control direction-ltr IRANSans" name="order" id="order" value="{{ old('order') ?? null}}">
                                    </div>
                                    <div class="form-group mb-5 col-12 px-0">
                                        <label for="tiny">توضیحات <span class="required">*</span></label>
                                        <div>
                                            <textarea id="tiny" name="summary">{{ old('summary') ?? null}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 px-0">
                                        <p class="mb-2 direction-ltr"><span class="required">*</span> 1 / 1&nbsp;&nbsp;تصویر </p>
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="image">
                                            <label for="image" class="custom-file-label">انتخاب عکس</label>
                                        </div>
                                    </div>
                                    <div class="crop-image-wrapper col-md-8 px-0">
                                        <img  id="stepCropImage" class="img-fluid">
                                    </div>
                                    <button class="btn btn-info mt-2">ثبت اطلاعات</button>
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
    <script src="{{ asset('dist/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/tinymceConfig.js') }}"></script>
    <script>

        function showImage(src, image) {
            const fr = new FileReader();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function (e) {
                image.src = this.result;
                $(image).parent('.crop-image-wrapper').addClass("my-4");
            };
            src.addEventListener("change", function () {
                // fill fr with image data
                fr.readAsDataURL(src.files[0]);

            });
        }
        const src = document.getElementById("image");
        const stepCropImage = document.getElementById('stepCropImage');
        showImage(src, stepCropImage);

        const cleave = new Cleave('#order', {
            numeral: true,
        });

    </script>
@endsection
