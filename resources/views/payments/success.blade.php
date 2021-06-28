@extends("layouts.pay")

@section('body')
    <div class="payment-success">
        <div class="payment-wrapper col-lg-6 col-md-8 col-sm-10 col-12 p-0 direction-rtl text-center">
            <div class="payment-card">
                <img class="mt-3" width="100" height="100"  alt="successful" src={{ url('images/success.png') }} />
                <p class="mt-5">پرداخت با موفقیت انجام شد.</p>
                <p>دسترسی به دوره به صورت نامحدود برای شما ایجاد شد.</p>
                <?php
                if ($slug ?? false){
                ?>

                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 my-5">
                        <a class="btn btn-honari w-100 py-2" href={{ config('app.class_next_url').$slug ?? "" }}>بازگشت به آموزش</a>
                    </div>
                </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
@endsection
