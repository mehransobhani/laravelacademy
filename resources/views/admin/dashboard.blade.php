@extends("layouts.admin")

@section("head")
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection

@section("content")

    <div class="row py-4">
        <div class="col-12 direction-rtl">
            <div class="pr-2">
                <h4 class="page-title mb-3">داشبورد</h4>
                 تاریخ امروز : <?php echo jdate("Y/m/d" , time()) ?>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    خلاصه فروش
                </div>
                <div class="card-body direction-rtl">
                    <div class="row">
                        <div class="col-12 mb-2">
                            کل فروش طی ۷ روز گذشته :
                        <?php
                            echo number_format($past_week_sell);
                            ?>
                        </div>
                        <div class="col-12 mb-2">
                            کل فروش طی ۳۰ روز گذشته :
                            <?php
                            echo number_format($past_month_sell);
                            ?>
                        </div>
                        <div class="col-12 mb-2">
                            کل فروش طی یک سال گذشته :
                            <?php
                            echo number_format($past_year_sell);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    فروش ماهانه
                </div>
                <div class="card-body">
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>


@endsection

@section("footer")
    <script>
        let config = {
            type: 'line',
            data: {
                labels: [<?php foreach ($month_name as $key => $value){
                    echo '"'.$value .'",';
                } ?> ],
                datasets: [{
                    label: 'sell rate',
                    backgroundColor: "#00BAC7",
                    borderColor: "#00BAC7",
                    lineTension:0,
                    data: [<?php foreach ($monthly_sell as $key => $value){
                        echo $value .',';
                    } ?> ],
                    fill: false,
                },]
            },
            options: {
                responsive: true,
                // title: {
                //     display: true,
                //     text: 'Chart.js Line Chart'
                // },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'sell rate'
                        }
                    }]
                }
            }
        };

        window.onload = function() {
            let ctx = document.getElementById('canvas').getContext('2d');
            window.myLine = new Chart(ctx, config);
        };
    </script>
@endsection
