<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>honari admin panel</title>

    <link rel="icon" href="{{ asset('/images/favicon.ico') }}" type="image/x-icon">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>
<div id="app">
    <div class="admin-layout">
        <div id="wrapper">

            <!--Start sidebar-wrapper-->
            <div id="sidebar-wrapper" data-simplebar>
                <div class="brand-logo">
                    <a href="{{ config('app.class_next_url') }}">
                        <!--                <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">-->
                        <h5 class="logo-text">هنری کلاس</h5>
                    </a>
                </div>
                <ul class="sidebar-menu">
                    @php($current_route = Route::currentRouteName())
                    <li class="sidebar-header">منو اصلی</li>
                    <li class='@if($current_route == 'dashboard')active @endif'>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <span>داشبورد</span>
                            <i class="zmdi zmdi-view-dashboard"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>دسته ها</span>
                            <i class="zmdi zmdi-delicious" ></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'art.index' || $current_route == 'art.edit' || $current_route == 'art.trash')active @endif'>
                                <a href="{{ route('art.index') }}"> تمامی دسته ها</a></li>
                            <li class='@if($current_route == 'art.create')active @endif'><a
                                    href="{{ route('art.create') }}"> دسته جدید</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>دوره ها</span>
                            <i class="zmdi zmdi-graduation-cap"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'course.index' || $current_route == 'course.edit' || $current_route == 'course.trash'  || $current_route == 'course.steps'   || $current_route == 'course.steps.create'  || $current_route == 'course.steps.edit' )active @endif'>
                                <a href="{{ route('course.index') }}"> تمامی دوره ها</a></li>
                            <li class='@if($current_route == 'course.create')active @endif'><a
                                    href="{{ route('course.create') }}"> دوره جدید</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>بسته ها</span>
                            <i class="zmdi zmdi-case"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'bundle.index' || $current_route == 'bundle.edit' || $current_route == 'bundle.trash'  || $current_route == 'bundle.steps'  )active @endif'>
                                <a href="{{ route('bundle.index') }}"> تمامی بسته های آموزشی</a></li>
                            <li class='@if($current_route == 'bundle.create')active @endif'><a
                                    href="{{ route('bundle.create') }}"> بسته آموزشی جدید</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>کد تخفیف</span>
                            <i class="zmdi zmdi-card-giftcard"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'gift.index' || $current_route == 'gift.edit' || $current_route == 'gift.trash'  || $current_route == 'gift.steps'   || $current_route == 'gift.steps.create'  || $current_route == 'gift.steps.edit' )active @endif'>
                                <a href="{{ route('gift.index') }}"> تمامی تخفیف ها</a></li>
                            <li class='@if($current_route == 'gift.create')active @endif'><a
                                    href="{{ route('gift.create') }}"> تخفیف جدید</a></li>
                        </ul>
                    </li>
                    <li class='@if($current_route == 'comment.index')active @endif'>
                        <a href="{{ route('comment.index') }}" class="waves-effect">
                            <span>کامنت ها</span>
                            <i class="zmdi zmdi-comment-alt-text"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>بنر ها</span>
                            <i class="zmdi zmdi-collection-image-o"></i>
                        </a>
                        <ul class="sidebar-submenu" class='@if($current_route == 'banner.index')active @endif'>
                            <li class='@if($current_route == 'banner.mostPopular.index' || $current_route == 'banner.mostPopular.edit' )active @endif'>
                                <a href="{{ route('banner.mostPopular.index') }}"> هنر های پرطرفدار</a>
                            </li>
                            <li class='@if($current_route == 'banner.ourOffer.index' || $current_route == 'banner.ourOffer.edit' )active @endif'>
                                <a href="{{ route('banner.ourOffer.index') }}">پیشنهاد هنری آکادمی</a>
                            </li>
                        </ul>
                    </li>
{{--                    <li>--}}
{{--                        <a href="javaScript:void(0);">--}}
{{--                            <i class="fa fa-angle-down float-left"></i>--}}
{{--                            <span>اساتید</span>--}}
{{--                            <i class="zmdi zmdi-hc-5x zmdi-mood"></i>--}}
{{--                        </a>--}}
{{--                        <ul class="sidebar-submenu" class='@if($current_route == 'teacher.index')active @endif'>--}}
{{--                            <li class='@if($current_route == 'teacher.index' || $current_route == 'teacher.edit' )active @endif'>--}}
{{--                                <a href="{{ route('teacher.index') }}">همه ی استاد ها</a>--}}
{{--                            </li>--}}
{{--                            <li class='@if($current_route == 'teacher.create' )active @endif'>--}}
{{--                                <a href="{{ route('teacher.create') }}">اضافه کردن استاد</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <li class='@if($current_route == 'transaction.index')active @endif'>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>اطلاعات خرید</span>
                            <i class="zmdi zmdi-card"></i>
                        </a>
                        <ul class="sidebar-submenu" class='@if($current_route == 'banner.index')active @endif'>
                            <li class='@if($current_route == 'transaction.index' )active @endif'>
                                <a href="{{ route('transaction.index') }}">تمامی تراکنش ها</a>
                            </li>
                            <li class='@if($current_route == 'transaction.addUserClass' )active @endif'>
                                <a href="{{ route('transaction.addUserClass') }}">اضافه کردن کلاس به کاربر</a>
                            </li>
                            <li>
                                <a target="_blank" href="{{ url('admin/query/courseclass_result?start=1400/01/01&end=1400/01/01') }}">خلاصه تراکنش ها</a>
                            </li>
                        </ul>

                    </li>

                    <li class='@if($current_route == 'user.excel.index')active @endif'>
                        <a href="javaScript:void(0);">
                            <i class="fa fa-angle-down float-left"></i>
                            <span>اکسل</span>
                            <i class="zmdi zmdi-wrap-text"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'user.excel.index' )active @endif'>
                                <a href="{{ route('user.excel.index') }}">خروجی کابر</a>
                            </li>
                        </ul>
                        <ul class="sidebar-submenu">
                            <li class='@if($current_route == 'transaction.excel.index' )active @endif'>
                                <a href="{{ route('transaction.excel.index') }}">خروجی فروش</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-header pt-3 mt-3" style="border-top : 1px solid #e0e0e0 ">ارتباط</li>
                    <li>
                        <a target="_blank" href="https://wa.me/989029088898" class="waves-effect">
                            <span>پشتیبانی</span>
                            <i class="zmdi zmdi-share"></i>
                        </a>
                    </li>
                </ul>

            </div>
            <!--End sidebar-wrapper-->

            <!--Start topbar header-->
            <header class="topbar-nav">
                <nav id="header-setting" class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav align-items-center left-nav-link">
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown"
                               href="javaScript:void(0);"
                               aria-expanded="false">
                                <span class="user-profile"><img src="{{ Auth::user()->profilepic ? config('app.auth_laravel_url').'/warehouse/profileimg/_250/'.Auth::user()->profilepic : asset('/images/avatar-placeholder.png') }}"
                                                                class="img-circle" alt="avatar"></span>
                            </a>
                            @auth
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li class="dropdown-item user-details mr-4">
                                        <div class="media">
                                            <div class="avatar"><img class="align-self-start mr-3"
                                                                     src="{{ Auth::user()->profilepic ? config('app.auth_laravel_url').'/warehouse/profileimg/_250/'.Auth::user()->profilepic : asset('/images/avatar-placeholder.png') }}"
                                                                     alt="user avatar"></div>
                                            <div class="media-body text-right">
                                                <h6 class="my-2 user-title">{{ Auth::user()->name }}</h6>
                                                <p class="user-subtitle">{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li class="dropdown-item p-0 text-right">
                                        <a class="dropdown-item" href="{{ config("app.login_url").'/edit' }}">
                                            پروفایل&nbsp;&nbsp;&nbsp;<i class="icon-user"></i>
                                        </a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li class="dropdown-item p-0 text-right">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                             خروج&nbsp;&nbsp;&nbsp;<i class="icon-power"></i>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            @endauth
                        </li>
                    </ul>


                    <ul class="navbar-nav ml-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link toggle-menu" href="javascript:void(0);">
                                <i class="icon-menu menu-icon"></i>
                            </a>
                        </li>
                    </ul>

                </nav>
            </header>
            <!--End topbar header-->

            <div class="clearfix"></div>

            <div class="content-wrapper">
                <div class="container-fluid">

                    <main>
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        @if (Session::has('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('warning') }}
                            </div>
                        @endif
                        @yield('content')
                    </main>

                    <div class="overlay toggle-menu"></div>
                </div>
                <!-- End container-fluid-->

            </div><!--End content-wrapper-->


            <!--Start Back To Top Button-->
            <a href="javaScript:void(0);" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
            <!--End Back To Top Button-->

            <!--Start footer-->
            <footer class="footer">
                <div class="container">

                    <div class="text-center" style="font-size: 0.8rem">
{{--                        Copyright © Honari Admin--}}
                        Powered By Laravel &nbsp; <img width="20" height="20" src="https://laravel.com/img/logomark.min.svg">
                    </div>
                </div>
            </footer>
            <!--End footer-->

        </div>
            <div class="loading-overlay fade-in">
                <div class="dot-flashing"></div>
            </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('footer')
</body>
</html>
