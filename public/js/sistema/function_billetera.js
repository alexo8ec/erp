$(document).ready(function () {
    $('#tablaBilleteras').DataTable({
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
                data: 'id_billetera',
                render: function (data, type, row) {
                    return row.id_billetera;
                }
            },
            {
                data: 'identificacion_billetera',
                render: function (data, type, row) {
                    return row.identificacion_billetera;
                }
            },
            {
                data: 'nombre_cliente_billetera',
                render: function (data, type, row) {
                    return row.nombre_cliente_billetera;
                }
            },
            {
                data: 'codigo_pais_billetera',
                render: function (data, type, row) {
                    return row.codigo_pais_billetera + row.digito_control_1_billetera + row.entidad_billetera + row.oficina_billetera + row.digito_control_2_billetera + row.numero_cuenta_billetera;
                }
            }, {
                data: 'numero_cuenta_billetera',
                render: function (data, type, row) {
                    return row.numero_cuenta_billetera;
                }
            }, {
                data: 'telefono_cliente_billetera',
                render: function (data, type, row) {
                    return row.telefono_cliente_billetera;
                }
            }, {
                data: 'celular_cliente_billetera',
                render: function (data, type, row) {
                    return row.celular_cliente_billetera;
                }
            }, {
                data: 'email_cliente_billetera',
                render: function (data, type, row) {
                    return row.email_cliente_billetera;
                }
            }, {
                data: 'direccion_cliente_billetera',
                render: function (data, type, row) {
                    return row.direccion_cliente_billetera;
                }
            }, {
                data: 'estado_billetera',
                className: 'text-center',
                render: function (data, type, row) {
                    var estado = '<span class="label label-danger">Inactivo</span>';
                    if (row.estado_billetera == 1) {
                        estado = '<span class="label label-primary">Activo</span>';
                    }
                    return estado;
                }
            }, {
                data: 'id_billetera',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_billetera + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $(' #activar ').val() + '</a></li>';
                    if (row.estado_compra_cabecera == 1) {
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_billetera + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $(' #desactivar ').val() + '</a></li>';
                    }
                    return '<div class = "btn-group" > <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i></button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_billetera + '\',1)"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li> <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_billetera + '\',0)"><i class = "fa fa-edit"> </i>' + $('#editar').val() + '</a></li> <li class="dropdown-divider"></li>' + linea + ' </ul></div>';
                }
            },
        ]
    });
    $('#tablaTransferencia').DataTable({
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
                data: 'id_billetera',
                render: function (data, type, row) {
                    return row.id_billetera;
                }
            },
            {
                data: 'identificacion_billetera',
                render: function (data, type, row) {
                    return row.identificacion_billetera;
                }
            },
            {
                data: 'nombre_cliente_billetera',
                render: function (data, type, row) {
                    return row.nombre_cliente_billetera;
                }
            },
            {
                data: 'codigo_pais_billetera',
                render: function (data, type, row) {
                    return row.codigo_pais_billetera + row.digito_control_1_billetera + row.entidad_billetera + row.oficina_billetera + row.digito_control_2_billetera + row.numero_cuenta_billetera;
                }
            }, {
                data: 'estado_billetera',
                className: 'text-center',
                render: function (data, type, row) {
                    var estado = '<span class="label label-danger">Inactivo</span>';
                    if (row.estado_billetera == 1) {
                        estado = '<span class="label label-primary">Activo</span>';
                    }
                    return estado;
                }
            }, {
                data: 'id_billetera',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_billetera + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $(' #activar ').val() + '</a></li>';
                    if (row.estado_compra_cabecera == 1) {
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_billetera + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $(' #desactivar ').val() + '</a></li>';
                    }
                    return '<div class = "btn-group" > <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i></button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_billetera + '\',1)"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li> <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_billetera + '\',0)"><i class = "fa fa-edit"> </i>' + $('#editar').val() + '</a></li> <li class="dropdown-divider"></li>' + linea + ' </ul></div>';
                }
            },
        ]
    });
    $('#btnagregarBilletera').click(function () {
        limpiarModal();
        $.ajax({
            url: $('#controlador').val() + "/numControl",
            type: 'POST',
            data: {
                _token: $('#token').val()
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#digito_control_1_billetera').val(data.control1);
                $('#digito_control_2_billetera').val(data.control2);
                $('#entidad_billetera').val(data.entidad);
                $('#oficina_billetera').val(data.oficina);
                $('#numero_cuenta_billetera').val(data.cuenta);
                $('#IBAN').val($('#codigo_pais_billetera').val() + data.control1 + data.entidad + data.oficina + data.control2 + data.cuenta);
            }
        });
        $('#myModal').modal('toggle');
    });
    $("#id_cliente").select2({
        noResults: function () {
            return "No hay resultado";
        },
        searching: function () {
            return "Buscando..";
        },
        placeholder: "--Seleccione un cliente--",
        minimumInputLength: 3,
        ajax: {
            url: 'ventas/buscarCliente',
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {term: params.term};
            },
            processResults: function (response) {
                if (response.length == 0) {
                    return {
                        results: [
                            {
                                id: 0,
                                text: 'El cliente no existe | Agregar cliente'
                            }
                        ]
                    };
                } else {
                    return {results: response};
                }
            },
            cache: true
        }
    });
    $("#id_cliente").change(function () {
        if ($("#id_cliente").val() != '') {
            var datos = $("#id_cliente").val().split('|');
            $('#identificacion').val(datos[6]);
            $('#id_cliente_billetera').val(datos[0]);
            if (datos[5] == 'p') {
                $.ajax({
                    url: $('#controlador').val() + '/asignarUsuarioBilletera',
                    type: 'post',
                    data: {
                        _token: $('#token').val(),
                        id: datos[6]
                    },
                    dataType: 'json',
                    success: function (json) {
                        $('#id_cliente').append('<option value="' + json.id + '">' + json.text + '</option>');
                        $('#id_cliente').val(json.id).trigger('change');
                    }
                });
            }
        }
    });
    $('#btnGuardarBilletera').click(function () {
        if ($('#id_cliente').val() == '') {
            $('#myModal').modal('toggle');
            mensajes('Debe seleccionar un cliente', 'warning');
            return false;
        } else {
            $('#myModal').modal('toggle');
            mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
        }
    });
    $('#btnagregarTransferencia').click(function () {
        limpiarModal();
        $('#myModal').modal('toggle');
    });
    $('#num_cuenta').blur(function () {
        if ($('#num_cuenta').val().length == 10 && $('#num_cuenta').val() != '') {
            $.ajax({
                url: $('#controlador').val() + '/validarCuenta',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    num_cuenta: $('#num_cuenta').val()
                },
                dataType: 'json',
                success: function (json) {
                    $('#nombre_billetera').val(json.nombre_usuario + ' ' + json.apellido_usuario);
                }
            });
        }
    });
});
