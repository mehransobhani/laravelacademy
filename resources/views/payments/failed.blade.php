@extends("layouts.pay")

@section('body')
    <div class="payment-failed">
        <div class="payment-wrapper col-lg-6 col-md-8 col-sm-10 col-12 p-0 direction-rtl text-center">
                <div class="alert alert-danger">

                    <?php
                    if($content ?? false){
                        echo '<p>'.$content.'</p>';
                    }
                    ?>

                    <?php
                    if ($slug ?? false){
                        ?>

                        <a href={{ config('app.class_next_url')."/courses/".$slug }}>صفحه آموزش</a>
                        <?php
                    }
                    else{
                        ?>

                        <a href={{ config('app.class_next_url') }}>بازگشت به آموزشگاه</a>
                        <?php
                    }
                    ?>


                </div>
    </div>
    </div>
@endsection
