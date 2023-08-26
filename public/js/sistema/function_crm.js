var idPaisEmpresa;
var idProvinciaEmpresa;
var idCiudadEmpresa;
$(document).ready(function () {
    $('#tablaClientes').DataTable({
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
                data: 'id_cliente',
                render: function (data, type, row) {
                    return row.id_cliente;
                }
            },
            {
                data: 'nombre_persona',
                render: function (data, type, row) {
                    return row.nombre_persona;
                }
            },
            {
                data: 'apellido_persona',
                render: function (data, type, row) {
                    return row.apellido_persona;
                }
            },
            {
                className: 'text-right',
                data: 'identificacion_persona',
                render: function (data, type, row) {
                    return row.identificacion_persona;
                }
            }, {
                className: 'text-right',
                data: 'telefono_persona',
                render: function (data, type, row) {
                    return row.telefono_persona;
                }
            }, {
                className: 'text-right',
                data: 'celular_persona',
                render: function (data, type, row) {
                    return row.celular_persona;
                }
            }, {
                data: 'email_persona',
                render: function (data, type, row) {
                    return row.email_persona;
                }
            }, {
                data: 'direccion_persona',
                render: function (data, type, row) {
                    return row.direccion_persona;
                }
            }, {
                data: 'estado_cliente',
                className: 'text-center',
                render: function (data, type, row) {
                    var estado = '<span class="label label-danger">Inactivo</span>';
                    if (row.estado_cliente == 1) {
                        estado = '<span class="label label-primary">Activo</span>';
                    }
                    return estado;
                }
            }, {
                data: 'id_cliente',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstado(\'' + row.id_cliente + '\',1,\'Cliente\')"><i class="fa fa-check-circle" style="color:green;"></i> ' + $('#activar').val() + '</a></li>';
                    if (row.estado_cliente == 1) {
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstado(\'' + row.id_cliente + '\',0,\'Cliente\')"><i class="fa fa-window-close" style="color:red"></i> ' + $('#desactivar').val() + '</a></li>';
                    }
                    return '<div class = "btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i></button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verCliente(\'' + row.id_cliente + '\',1)"><i class="fa fa-edit"></i> ' + $('#editar').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verFicha(\'' + row.id_cliente + '\',0)"><i class="fa fa-address-card"></i> ' + $('#ficha').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                }
            },
        ]
    });
    $('#btnagregarCliente').click(function () {
        limpiarModal();
        $('#myModal').modal('toggle');
    });
    $('#pais_empresa').change(function () {
        if ($('#pais_empresa').val() != '') {
            $.ajax({
                url: 'utilidades/provincias',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    id: $('#pais_empresa').val()
                },
                dataType: 'json',
                success: function (json) {
                    var html = '<option value="">--Seleccione una provincia--</option>';
                    $.each(json, function (i, item) {
                        html += '<option value="' + item.id_provincia + '">' + capitalizar(item.nombre_provincia) + '</option>';
                    });
                    $('#provincia_empresa').html(html).trigger('change');
                    if (idProvinciaEmpresa == 0) {
                        $('#provincia_empresa').val('').trigger('change');
                    } else {
                        $('#provincia_empresa').val(idProvinciaEmpresa).trigger('change');
                    }
                },
                error: function (error) {
                    alert(error);
                }
            });
        }
    });
    $('#provincia_empresa').change(function () {
        if ($('#provincia_empresa').val() != '') {
            $.ajax({
                url: 'utilidades/ciudades',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    id: $('#provincia_empresa').val()
                },
                dataType: 'json',
                success: function (json) {
                    var html = '<option value="">--Seleccione una ciudad--</option>';
                    $.each(json, function (i, item) {
                        html += '<option value="' + item.id_ciudad + '">' + capitalizar(item.nombre_ciudad) + '</option>';
                    });
                    $('#ciudad_empresa').html(html).trigger('change');
                    if (idCiudadEmpresa == 0) {
                        $('#ciudad_empresa').val('').trigger('change');
                    } else {
                        $('#ciudad_empresa').val(idCiudad).trigger('change');
                    }
                },
                error: function (error) {
                    alert(error);
                }
            });
        }
    });
    $('#btnGuardarCliente').click(function () {
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
    });
});
function verCliente(id) {
    $.ajax({
        url: $('#controlador').val() + "/getCliente",
        type: "POST",
        data: {
            _token: $('#token').val(),
            id: id
        },
        dataType: "json",
        success: function (json) {
            $('#id_cliente').val(json.id_cliente);
            $('#id_persona').val(json.id_persona);
            $('#id_usuario_creacion_persona').val(json.id_usuario_creacion_persona);
            $('#id_usuario_modificacion_persona').val(json.id_usuario_modificacion_persona);
            $('#nombre_persona').val(json.nombre_persona);
            $('#apellido_persona').val(json.apellido_persona);
            $('#id_tipo_identificacion_persona').val(json.id_tipo_identificacion_persona).trigger('change');
            $('#identificacion_persona').val(json.identificacion_persona);
            $('#telefono_persona').val(json.telefono_persona);
            $('#celular_persona').val(json.celular_persona);
            $('#email_persona').val(json.email_persona);
            $('#fecha_nacimiento_persona').val(json.fecha_nacimiento_persona);
            $('#id_genero_persona').val(json.id_genero_persona).trigger('change');
            idPais = json.id_pais_ciudad;
            $('#pais').val(idPais).trigger('change');
            idProvincia = json.id_provincia_ciudad;
            idCiudad = json.id_ciudad;
            if (json.estado_persona == 1) {
                $('#cheestado').attr('checked', true);
                $('#estado').val(1);
            } else {
                $('#cheestado').attr('checked', false);
                $('#estado').val(0);
            }
            if (json.valor_compra_persona == 1) {
                $('#chevalor1').attr('checked', true);
            } else if (json.valor_compra_persona == 2) {
                $('#chevalor2').attr('checked', true);
            } else if (json.valor_compra_persona == 3) {
                $('#chevalor3').attr('checked', true);
            } else if (json.valor_compra_persona == 4) {
                $('#chevalor4').attr('checked', true);
            }
            $('#observacion_cliente').val(json.observacion_persona);
            $('#id_tipo_cliente').val(json.id_tipo_cliente).trigger('change');
            $('#urbanizacion_persona').val(json.urbanizacion_persona);
            $('#etapa_persona').val(json.etapa_persona);
            $('#mz_persona').val(json.mz_persona);
            $('#villa_persona').val(json.villa_persona);
            $('#direccion_persona').val(json.direccion_persona);
            $('#referencia_domicilio_persona').val(json.referencia_domicilio_persona);
            $('#nombre_empresa_persona').val(json.nombre_empresa_persona);
            $('#ruc_empresa_persona').val(json.ruc_empresa_persona);
            $('#fecha_ingreso_empresa_persona').val(json.fecha_ingreso_empresa_persona);
            $('#sueldo_empresa_persona').val(json.sueldo_empresa_persona);
            $('#telefono_empresa_persona').val(json.telefono_empresa_persona);
            $('#celular_empresa_persona').val(json.celular_empresa_persona);
            idPaisEmpresa = json.id_pais_ciudad_empresa;
            $('#pais_empresa').val(idPaisEmpresa).trigger('change');
            idProvinciaEmpresa = json.id_provincia_ciudad_empresa;
            idCiudadEmpresa = json.id_ciudad_empresa;
            $('#direccion_empresa_persona').val(json.direccion_empresa_persona);
            $('#referencia_empresa_direccion_persona').val(json.referencia_empresa_direccion_persona);
            $('#urbanizacion_empresa_persona').val(json.urbanizacion_empresa_persona);
            $('#etapa_empresa_persona').val(json.etapa_empresa_persona);
            $('#mz_empresa_persona').val(json.mz_empresa_persona);
            $('#villa_empresa_persona').val(json.villa_empresa_persona);
            $('#myModal').modal('toggle');
        }
    });
}
