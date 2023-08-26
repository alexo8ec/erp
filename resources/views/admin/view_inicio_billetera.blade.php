<?php

use App\Models\Utilidades;

$height = 180;
$de = Utilidades::detect();
if ($de['os'] == 'WIN') {
    $height = 70;
}
?>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div>
                        <canvas id="lineChart" height="<?= $height; ?>"></canvas>
                    </div>
                    <div class="m-t-md">
                        <small class="float-right">
                            <i class="fa fa-clock-o"> </i>
                            Update on 16.07.2015
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right">Today</span>
                    </div>
                    <h5>Ingresos</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">$ <?= number_format(config('data.saldos')->ingreso, 2); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-info float-right">Monthly</span>
                    </div>
                    <h5>Egresos</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">$ <?= number_format(config('data.saldos')->egreso, 2); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-warning float-right"><?= date('Y'); ?></span>
                    </div>
                    <h5>Saldo</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">$ <?= number_format(config('data.saldos')->saldo, 2); ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var lineData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                    label: "Ingresos",
                    backgroundColor: "rgba(28,132,198,0.5)",
                    borderColor: "rgba(28,132,198,0.7)",
                    pointBackgroundColor: "rgba(28,132,198,1)",
                    pointBorderColor: "#fff",
                    data: [28, 48, 40, 19, 86, 27, 90, 85, 75, 155, 145, 17]
                },
                {
                    label: "Egresos",
                    backgroundColor: "rgba(237,85,101,0.5)",
                    borderColor: "rgba(237,85,101,1)",
                    pointBackgroundColor: "rgba(237,85,101,1)",
                    pointBorderColor: "#fff",
                    data: [65, 59, 80, 81, 56, 55, 40, 45, 25, 36, 69, 78]
                }
            ]
        };

        var lineOptions = {
            responsive: true
        };


        var ctx = document.getElementById("lineChart").getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: lineData,
            options: lineOptions
        });

    });
</script>