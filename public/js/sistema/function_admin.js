$(document).ready(function () {
    if ($('#id_empresa').val() != '') {
        $('.chart').easyPieChart({
            barColor: '#f8ac59',
            //                scaleColor: false,
            scaleLength: 5,
            lineWidth: 4,
            size: 80
        });

        $('.chart2').easyPieChart({
            barColor: '#1c84c6',
            //                scaleColor: false,
            scaleLength: 5,
            lineWidth: 4,
            size: 80
        });
        let monthSales = JSON.parse($('#ventas_mensuales').val());
        let arrayData2 = [];
        $.each(monthSales, function (i, item) {
            let array = [
                gd(item[0], item[1], item[2]),
                item[3]
            ];
            arrayData2.push(array);
        });
        let monthCharge = JSON.parse($('#cobros_mensuales').val());
        let arrayData3 = [];
        $.each(monthCharge, function (i, item) {
            let array = [
                gd(item[0], item[1], item[2]),
                item[3]
            ];
            arrayData3.push(array);
        });
        var data2 = arrayData2;
        var data3 = arrayData3;
        var dataset = [
            {
                label: "Ventas",
                data: data2,
                color: "#1ab394",
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 24 * 60 * 60 * 600,
                    lineWidth: 0
                }

            }, {
                label: "Cobros",
                data: data3,
                yaxis: 2,
                color: "#1C84C6",
                lines: {
                    lineWidth: 1,
                    show: true,
                    fill: true,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0.2
                            }, {
                                opacity: 0.4
                            }
                        ]
                    }
                },
                splines: {
                    show: false,
                    tension: 0.6,
                    lineWidth: 1,
                    fill: 0.1
                }
            }
        ];
        var options = {
            xaxis: {
                mode: "time",
                tickSize: [
                    3, "day"
                ],
                tickLength: 0,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Arial',
                axisLabelPadding: 10,
                color: "#d5d5d5"
            },
            yaxes: [
                {
                    position: "left",
                    max: 1070,
                    color: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 3
                }, {
                    position: "right",
                    clolor: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: ' Arial',
                    axisLabelPadding: 67
                }
            ],
            legend: {
                noColumns: 1,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: false,
                borderWidth: 0
            }
        };
        var previousPoint = null,
            previousLabel = null;
        $.plot($("#flot-dashboard-chart"), dataset, options);
    }
    try {
        $('#tablaPlanCuentas').DataTable({
            language: {
                "decimal": "",
                "emptyTable": $('#emptyTable').val(),
                "info": $('#mostrando').val() + " _START_ " + $('#a').val() + " _END_ " + $('#de').val() + " _TOTAL_ " + $('#entradas').val(),
                "infoEmpty": $('#mostrando').val() + " 0 " + $('#a').val() + " 0 " + $('#de').val() + " 0 " + $('#entradas').val(),
                "infoFiltered": "(" + $('#filtrado').val() + " " + $('#de').val() + " _MAX_ total " + $('#entradas').val() + ")",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": $('#mostrar').val() + " _MENU_ " + $('#entradas').val(),
                "loadingRecords": $('#loadingRecords').val() + "...",
                "processing": $('#processing').val() + "...",
                "search": $('#search').val(),
                "zeroRecords": $('#zeroRecords').val(),
                "paginate": {
                    "first": $('#first').val(),
                    "last": $('#last').val(),
                    "next": $('#next').val(),
                    "previous": $('#previous').val()
                }
            },
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copy'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'excel',
                    title: 'ExampleFile',
                    text: '<i class="fa fa-file-excel-o"></i> Excel'
                },
                {
                    extend: 'pdf',
                    title: 'ExampleFile',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                }, {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
    } catch (error) {}
    $('#btncopiarPlan').click(function () {
        $.ajax({
            url: 'contabilidad/copiarPlan',
            type: 'post',
            data: {
                _token: $('#token').val()
            },
            dataType: 'json',
            success: function (json) {
                var mns = json.message.split('|');
                console.log(json);
                if (mns[0] == 'ok') {
                    mensaje(mns[1], 'success');
                    location.reload();
                } else {
                    mensaje(mns[1], 'error');
                }
            }
        });
    });
});
function gd(year, month, day) {
    return new Date(year, month - 1, day).getTime();
}
