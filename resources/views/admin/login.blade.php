@extends('layouts.auth')

@section('body')
    <div class="container">
                <div class="card-group card-authentication mx-auto my-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-content direction-rtl p-3">
                                <div class="card-title text-uppercase text-center py-3">
                                    ورود
                                </div>
                                <form method="POST" action="{{ route('login') }}">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="auth_error_message my-2"><strong>{{$error}}</strong></div>
                                        @endforeach
                                    @endif
                                    @csrf
                                    <div class="form-group">
                                        <div class="has-icon-left">
                                            <div class="position-relative">
                                            <label for="username" class="sr-only">شماره همراه</label>
                                            <input id="username" type="text" class="form-control direction-rtl IRANSans @error('username') is-invalid @enderror" placeholder="شماره همراه" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                            <div class="form-control-position"><i class="icon-user"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="has-icon-left">
                                            <div class="position-relative">
                                                <label for="password" class="sr-only">پسورد</label>
                                                <input id="password" type="password" class="form-control direction-rtl @error('password') is-invalid @enderror" placeholder="پسورد" name="password" required autocomplete="current-password">
                                                <div class="form-control-position"><i class="icon-lock"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info btn-block waves-effect waves-light">ورود</button>



                                </form>
                            </div>
                        </div>
                    </div>
                </div>

    </div>
@endsection
