$(document).ready(function () {
    $('#tablaCajas').DataTable({
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
        ],
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js",
        aoColumns: [
            {
                data: 'id_cuenta',
                render: function (data, type, row) {
                    return row.id_cuenta;
                }
            },
            {
                data: 'nombre_cuenta',
                render: function (data, type, row) {
                    return row.nombre_cuenta;
                }
            },
            {
                className: 'text-center',
                data: 'numero_cuenta',
                render: function (data, type, row) {
                    return row.numero_cuenta;
                }
            },
            {
                className: 'text-center',
                data: 'tipo_cuenta',
                render: function (data, type, row) {
                    return row.tipo_cuenta;
                }
            }, {
                className: 'text-center',
                data: 'tipo_cuenta',
                render: function (data, type, row) {
                    return row.tipo_cuenta;
                }
            }, {
                className: 'text-center',
                data: 'id_cuenta',
                render: function (data, type, row) {
                    return '<a href="javascript:;"><img src="public/images/report.png" style="width:24px;" /></a>';
                }
            }, {
                data: 'estado_cuenta',
                className: 'text-center',
                render: function (data, type, row) {
                    var estado = '<span class="label label-danger">Inactivo</span>';
                    if (row.estado_cuenta == 1) 
                        estado = '<span class="label label-primary">Activo</span>';
                    

                    return estado;
                }
            }, {
                data: 'id_cuenta',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_cuenta + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $('#activar').val() + '</a></li>';
                    if (row.estado_cuenta == 1) 
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_cuenta + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $('#desactivar').val() + '</a></li>';
                    
                    return '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i> </button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_cuenta + '\',1)"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_cuenta + '\',0)"><i class="fa fa-edit"></i> ' + $('#editar').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                }
            },
        ]
    });
    $('#btnagregarCaja').click(function () {
        limpiarModal();
        $('#myModal').modal('toggle');
    });
    $('#btnguardarCaja').click(function () {
        if ($('#nombre_cuenta').val() == '') {
            mensaje('warning', 'Ingrese el nombre de la cuenta');
            return;
        } else if ($('#numero_cuenta').val() == '') {
            mensaje('warning', 'Ingrese el número de la cuenta');
            return;
        } else if ($('#tipo_cuenta').val() == '') {
            mensaje('warning', 'Seleccion el tipo de la cuenta');
            return;
        }
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
    });
});
