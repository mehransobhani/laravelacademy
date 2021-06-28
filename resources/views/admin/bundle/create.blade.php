@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">بسته ها</h4>
                {{ Breadcrumbs::render('bundle.create') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">بسته جدید</div>
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
                        <form method="post" id="bundle-create-form" action="{{ route('bundle.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4 col-lg-6 px-0">
                                <label for="title">عنوان بسته <span class="required">*</span></label>
                                <input type="text" class="form-control make-slug-from" name="title" id="title"
                                       placeholder="عنوان بسته" value="{{ old('title') ?? null}}">
                            </div>
                            <div class="form-group mb-4 col-lg-6 px-0">
                                <label for="slug">slug (غیر قابل تغییر) <span class="required">*</span></label>
                                <input type="text" class="form-control make-slug-to" name="slug" id="slug"
                                       value="{{ old('slug') ?? null}}">
                            </div>

                            <div class="form-group mb-4 col-md-6 px-0">
                                <label for="category">دسته <span class="required">*</span></label>
                                <select name="category[]" id="category" multiple="multiple" class="d-block w-100">
                                    @foreach($arts as $key => $value)
                                        <option value="{{ $value->id }}" <?php if(old('category')){ if( in_array( $value->id,old('category') ) ) { echo 'selected'; }  } ?>>{{ $value->artName }}</option>
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
                                <label for="status">وضعیت انتشار <span class="required">*</span></label>
                                <select id="status" class="w-100" name="status">
                                    <option @if(intval(old('status')) === 0) selected @endif value="0">پیش‌نویس</option>
                                    <option @if(intval(old('status')) === 1) selected @endif value="1">منتشر</option>
                                </select>
                            </div>

                            <div class="col-lg-6 px-0">
                                <p class="mb-2 direction-ltr"><span class="required">*</span> 16 / 9&nbsp;&nbsp;تصویر </p>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="image">
                                    <label for="image" class="custom-file-label">انتخاب عکس</label>
                                </div>
                            </div>

                            <div class="crop-image-wrapper col-lg-8 px-0">
                                <img id="BundleCropImage" class="img-fluid">
                            </div>

                            <br>
                            <hr>
                            <br>
                            <div class="row">
                                <div class="col form-group mb-4 col-md-6">
                                    <label for="course">دوره ها (قابل تغییر نمیباشد)<span class="required">*</span></label>
                                    <select id="course" class="d-block w-100">
                                        @foreach($courses as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col align-self-center">
                                    <div id="add-course" class="btn btn-dark">اضافه</div>
                                </div>
                            </div>

                            <div id="courses-wrapper">
                                    @foreach(old('course') ?? [] as $key => $value)
                                        <div class="row mb-4 align-items-center">
                                            <div data-id="{{ $value }}" class="delete-course user-pointer" style="padding-right:15px"><i class="fas fa-times"></i></div>
                                            <div class="col-6 col-md-4">
                                                <input name="course[]" class="course-id" value="{{ $value }}" type="hidden">
                                                <input name="course_name[{{ $value }}]" class="form-control" value="{{ old('course_name')[$value] }}" readonly type="text">
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <input class="form-control direction-ltr IRANSans course-price" placeholder="قیمت"  name="course_price[{{ $value }}]" value="{{ old('course_price')[$value] }}" type="text">
                                            </div>
                                        </div>
                                    @endforeach
                            </div>



                            <br>
                            <hr>
                            <br>



                            <button class="btn btn-info mt-2" >ثبت
                                اطلاعات</button>

                        </form>
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
        const BundleCropImage = document.getElementById('BundleCropImage');
        showImage(src, BundleCropImage);

        $('#course').select2();
        $('#category').select2();


        let cleave = new Cleave('#price', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    </script>
    <script>

        let courses = []

        $('.course-id').toArray().forEach(function (field){
            courses.push(field.value)
        })

        $("#add-course").on("click", function (){
            const course_id = $("#course").val()
            const course_name = $("#course option:selected").text()

            if (courses.includes(course_id)){
                return true
            }

            courses.push(course_id)


            $("#courses-wrapper").append(`
                <div class="row mb-4 align-items-center">
                    <div data-id="${course_id}" class="delete-course user-pointer" style="padding-right:15px"><i class="fas fa-times"></i></div>
                    <div class="col col-md-4">
                        <input name="course[]" value="${course_id}" type="hidden">
                        <input class="form-control" value="${course_name}" readonly="readonly" type="text"  name="course_name[${course_id}]" />
                    </div>
                    <div class="col col-md-4">
                        <input class="form-control direction-ltr IRANSans" placeholder="قیمت" id="course_price_${course_id}" name="course_price[${course_id}]" type="text" />
                    </div>
                </div>
              `);
            let cleave= new Cleave(`#course_price_${course_id}`, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            })

        })
        $("body").on("click" , ".delete-course", function () {
            const id = $(this).data("id");


            courses = courses.filter(function(value){
                return parseInt(value) !== parseInt(id);
            })

            $(this).parent().remove();
            })
    </script>

@endsection
