@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">دوره ها</h4>
                {{ Breadcrumbs::render('course.create') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">دوره جدید</div>
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
                                    <form method="post" id="course-create-form" action="{{ route('course.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="name">نام دوره <span class="required">*</span></label>
                                            <input type="text" class="form-control make-slug-from" name="name" id="name" value="{{ old('name') ?? null}}">
                                        </div>
                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="urlfa">slug (غیر قابل تغییر) <span class="required">*</span></label>
                                            <input type="text" class="form-control make-slug-to" name="urlfa" id="urlfa" value="{{ old('urlfa') ?? null}}">
                                        </div>
                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="category">دسته <span class="required">*</span></label>
                                                <select name="category[]" id="category" multiple="multiple" class="d-block w-100">
                                                    @foreach($arts as $key => $value)
                                                        <option value="{{ $value->id }}" <?php if(old('category')){ if( in_array( $value->id,old('category') ) ) { echo 'selected'; }  } ?>>{{ $value->artName }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="category">مدرس <span class="required">*</span></label>
                                                <select name="teacher" id="teacher" class="d-block w-100">
                                                    @foreach($users as $key => $value)
                                                        <option value="{{ $value->id }}" {{ old('teacher') == $value->id ? 'selected' : null }}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group mb-5 col-12 px-0">
                                            <label for="tiny">توضیحات <span class="required">*</span></label>
                                            <div>
                                                <textarea id="tiny" name="description">{{ old('description') ?? null}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="teacher_about">معرفی استاد  </label>
                                            <textarea class="form-control" name="teacher_about" id="teacher_about" rows="5">{{ old('teacher_about') ?? null}}</textarea>
                                        </div>

                                        <div class="col-md-6 px-0">
                                            <p class="mb-2 direction-ltr"><span class="required">*</span> 16 / 9&nbsp;&nbsp;تصویر </p>
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image">
                                                <label for="image" class="custom-file-label">انتخاب عکس</label>
                                            </div>
                                        </div>
                                        <div class="crop-image-wrapper col-md-8 px-0">
                                            <img  id="courseCropImage" class="img-fluid">
                                        </div>

                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="bundles">محصول</label>
                                            <input type="text" class="form-control direction-ltr IRANSans" name="bundles" id="bundles" value="{{ old('bundles') ?? null}}">
                                        </div>

                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="price">قیمت (تومان)<span class="required">*</span></label>
                                            <input type="text" class="form-control direction-ltr IRANSans" name="price" id="price" value="{{ old('price') ?? null}}">
                                        </div>

                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="off">قیمت بعد از تخفیف  (تومان)</label>
                                            <input type="text" class="form-control direction-ltr IRANSans" name="off" id="off" value="{{ old('off') ?? null}}">
                                        </div>


                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="status">وضعیت انتشار <span class="required">*</span></label>
                                            <select id="status" class="w-100" name="status">
                                                <option @if(intval(old('status')) === 0) selected  @endif value="0">پیش‌نویس</option>
                                                <option @if(intval(old('status')) === 1) selected  @endif value="1">منتشر</option>
                                            </select>
                                        </div>

                                        <br>

                                        <hr>

                                        <h4>SEO</h4>
                                        <div class="form-group mb-4 col-md-6 px-0">
                                            <label for="summary">خلاصه توضیحات </label>
                                            <textarea class="form-control" name="summary" id="summary" rows="5">{{ old('summary') ?? null}}</textarea>
                                        </div>

                                        <hr>
                                        <br>


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


        function showImage(src,target) {
            const fr=new FileReader();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function(e) {
                target.src = this.result;
                $(target).parent('.crop-image-wrapper').addClass("my-4");
            };
            src.addEventListener("change",function() {
                // fill fr with image data
                fr.readAsDataURL(src.files[0]);
            });
        }
        const src = document.getElementById("image");
        const courseCropImage = document.getElementById('courseCropImage');
        showImage(src,courseCropImage);

        $('#category').select2();
        $('#teacher').select2();

        let cleave = new Cleave('#price', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        let cleaveOff = new Cleave('#off', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });


    </script>
@endsection
