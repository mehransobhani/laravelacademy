@extends('layouts.admin')

@section('content')
    <div class="row py-2">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">کامنت ها</h4>
                {{ Breadcrumbs::render('comment.edit' , $comment) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">ویرایش کامنت</div>
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
                                <a href="{{ config("app.class_next_url") . "/courses/" . $comment->get_step->get_course->urlfa . "/" . $comment->get_step->urlKey }}">
                                    <h4 class="text-primary">{{ $comment->get_step->name }}</h4></a>
                                <form method="post"
                                      action="{{ $comment->get_parent ? route('comment.reply.update' , $comment->id) : route('comment.update' , $comment->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-4">
                                        <label for="comment">
                                            فرستنده {{ $comment->sender }}
                                        </label>
                                        <textarea class="form-control" name="comment" id="comment" placeholder="نظر"
                                                  rows="5">{!! old('comment' , $comment->comment) ?? null !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="visibilityStatus">وضعیت</label>
                                        <select class="form-control" name="visibilityStatus" id="visibilityStatus">
                                            <option value="0" {{ $comment->visibilityStatus==0 ? 'selected' : null }}>
                                                حذف
                                            </option>
                                            <option value="1" {{ $comment->visibilityStatus==1 ? 'selected' : null }}>
                                                تایید شده
                                            </option>
                                            <option value="2" {{ $comment->visibilityStatus==2 ? 'selected' : null }}>در
                                                انتظار تایید
                                            </option>
                                        </select>
                                    </div>
                                    <?php if($comment->get_reply) {
                                    foreach ($comment->get_reply as $key => $value) {
                                    ?>
                                    <div class="rounded bg-primary text-white mt-2 pt-2 pr-2 pl-2 pb-4"><p>
                                            <span>{{ $value->sender }}</span></p>
                                        <p class="mb-4">{!! $value->comment !!}</p><a class="btn-dark text-white btn-sm"
                                                                                      href="{{ route('comment.edit' , $value->id) }}">ویرایش</a>
                                    </div>
                                    <?php
                                    }
                                    }
                                    ?>

                                    <div class="form-group mb-4">
                                        @if($comment->get_parent)
                                            <p>این کامنت پاسخ به کامنت <span>{{ $comment->get_parent->sender }}</span>
                                                میباشد</p>
                                            <div class="rounded bg-primary text-white pt-2 pr-2 pl-2 pb-4"><p
                                                    class="mb-4">{!! $comment->get_parent->comment !!}</p><a
                                                    class="btn-dark text-white btn-sm"
                                                    href="{{ route('comment.edit' , $comment->get_parent->id) }}">ویرایش</a>
                                            </div>
                                        @else
                                            <label for="reply">پاسخ</label>
                                            <textarea class="form-control" name="reply" id="reply" placeholder="پاسخ" rows="5"></textarea>

                                        @endif
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
