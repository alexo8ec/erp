$(document).ready(function () {
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
