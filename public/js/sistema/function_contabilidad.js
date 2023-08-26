var filas = 0;
$(document).ready(function () {
    try {
        $('#tablaAsientos').DataTable({
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
            order: [
                [4, 'asc']
            ],
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
                    data: 'id_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.id_asiento_cabecera;
                    }
                },
                {
                    data: 'glosa_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.glosa_asiento_cabecera;
                    }
                },
                {
                    className: 'text-right',
                    data: 'debe_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.debe_asiento_cabecera;
                    }
                },
                {
                    className: 'text-right',
                    data: 'haber_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.haber_asiento_cabecera;
                    }
                }, {
                    className: 'text-center',
                    data: 'fecha_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.fecha_asiento_cabecera;
                    }
                }, {
                    className: 'text-center',
                    data: 'usuario_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.usuario_asiento_cabecera;
                    }
                }, {
                    data: 'id_asiento_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var estado = '<span class="label label-danger">Descuadrado</span>';
                        if (row.debe_asiento_cabecera == row.haber_asiento_cabecera) 
                            estado = '<span class="label label-primary">Cuadrado</span>';
                        


                        return estado;
                    }
                }, {
                    data: 'id_asiento_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_asiento_cabecera + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $('#activar').val() + '</a></li>';
                        if (row.estado_compra_cabecera == 1) 
                            linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_asiento_cabecera + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $('#desactivar').val() + '</a></li>';
                        


                        return '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i> </button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_asiento_cabecera + '\',1)"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_asiento_cabecera + '\',0)"><i class="fa fa-edit"></i> ' + $('#editar').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                    }
                },
            ]
        });
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
            pageLength: 200,
            responsive: true,
            order: [
                [1, 'asc']
            ],
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
                    data: 'id_plan',
                    render: function (data, type, row) {
                        return row.id_plan;
                    }
                },
                {
                    data: 'codigo_contable_plan',
                    render: function (data, type, row) {
                        return row.codigo_contable_plan;
                    }
                },
                {
                    data: 'nombre_cuenta_plan',
                    render: function (data, type, row) {
                        return row.nombre_cuenta_plan;
                    }
                },
                {
                    data: 'estado_plan',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var estado = '<span class="label label-danger">Inactivo</span>';
                        if (row.estado_plan == 1) 
                            estado = '<span class="label label-primary">Activo</span>';
                        


                        return estado;
                    }
                }, {
                    data: 'id_plan',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_plan + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $('#activar').val() + '</a></li>';
                        if (row.estado_plan == 1) 
                            linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_plan + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $('#desactivar').val() + ' </a></li>';
                        


                        return '<div class = "btn-group"> <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i></button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_plan + '\',1)"><i class="fa fa-eye"></i>' + $('#ver').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_plan + '\',0)"><i class="fa fa-edit"></i>' + $('#editar').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                    }
                },
            ]
        });
        sumar();
    } catch (error) {}
    $('#btnagregarAsiento').click(function () {
        $('#myModal').modal('toggle');
    });
    $('#btnagregarPlanCuenta').click(function () {
        limpiarFormulario();
        $('#myModal').modal('toggle');
    });
    $('#tablaLibroMayor').DataTable({
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
        pageLength: 500,
        responsive: true,
        order: [
            [1, 'asc']
        ],
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
    $('#tablaMayor').DataTable({
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
        pageLength: 500,
        responsive: true,
        order: [
            [1, 'asc']
        ],
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
    $('#clase_contable_plan').change(function () {
        if ($('#clase_contable_plan').val() != '') {
            $.ajax({
                url: $('#controlador').val() + '/getGrupoPlan',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    clase: $('#clase_contable_plan').val()
                },
                dataType: 'json',
                success: function (json) {
                    $('#codigo_contable_plan').val($('#clase_contable_plan').val());
                    var html = '<option value="">--Seleccionar--</option>';
                    $.each(json, function (i, item) {
                        html += '<option value="' + item.grupo_contable_plan + '">' + item.codigo_contable_plan + ' | ' + item.nombre_cuenta_plan + '</option>';
                    });
                    html += '<option value="0">0 | Nuevo grupo</option>';
                    $('#grupo_contable_plan').html(html).trigger('change');
                }
            });
        }
    });
    $('#grupo_contable_plan').change(function () {
        if ($('#grupo_contable_plan').val() != '') {
            $.ajax({
                url: $('#controlador').val() + '/getCuentaPlan',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    clase: $('#clase_contable_plan').val(),
                    grupo: $('#grupo_contable_plan').val()
                },
                dataType: 'json',
                success: function (json) {
                    if ($('#grupo_contable_plan').val() != '') 
                        $('#codigo_contable_plan').val($('#clase_contable_plan').val() + '.' + PadLeft($('#grupo_contable_plan').val(), 2, 0));
                    


                    var html = '<option value="">--Seleccionar--</option>';
                    $.each(json, function (i, item) {
                        html += '<option value="' + item.cuenta_contable_plan + '">' + item.codigo_contable_plan + ' | ' + item.nombre_cuenta_plan + '</option>';
                    });
                    html += '<option value="0">0 | Nueva cuenta</option>';
                    $('#cuenta_contable_plan').html(html).trigger('change');
                }
            });
        }
    });
    $('#cuenta_contable_plan').change(function () {
        if ($('#cuenta_contable_plan').val() != '') {
            $.ajax({
                url: $('#controlador').val() + '/getAuxiliarPlan',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    clase: $('#clase_contable_plan').val(),
                    grupo: $('#grupo_contable_plan').val(),
                    cuenta: $('#cuenta_contable_plan').val()
                },
                dataType: 'html',
                success: function (json) {
                    if ($('#cuenta_contable_plan').val() == 0) {
                        $('#codigo_contable_plan').val($('#clase_contable_plan').val() + '.' + PadLeft($('#grupo_contable_plan').val(), 2, 0) + '.' + PadLeft(json, 3, 0));
                        $('#auxiliar_contable_plan').val('');
                        $('#cuenta_contable_plan_').val(json);
                    } else {
                        $('#auxiliar_contable_plan').val(json);
                        $('#codigo_contable_plan').val($('#clase_contable_plan').val() + '.' + PadLeft($('#grupo_contable_plan').val(), 2, 0) + '.' + $('#cuenta_contable_plan').val() + '.' + PadLeft(json, 6, 0));
                    }
                }
            });
        }
    });
    $('#btnguardarPlanCuenta').click(function () {
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
    });
    $('#agregarAsiento').click(function () {
        if ($('#id_plan').val() != '') {
            if ($('#debe').val() != '' || $('#haber').val() != '') {
                var debe = '';
                var haber = '';
                var html = '';
                if ($('#debe').val() == '') 
                    debe = '0.00';
                 else 
                    debe = $('#debe').val();
                if ($('#haber').val() == '') 
                    haber = '0.00';
                 else 
                    haber = $('#haber').val();
                 filas += filas;
                var codigo = $('#id_plan').val().split('|');
                html = '<tr id="tr' + filas + '"><td><input class="form-control suma" id="codigo_contable" name="codigo_contable[]" type="text" value="' + codigo[1] + '" readonly /></td><td><input class="form-control suma" type="text" value="' + codigo[2] + '" readonly /></td><td align="right"><input class="form-control suma" id="ndebe_' + filas + '" name="ndebe[]" type="text" value="' + debe + '" style="text-align:right;" /></td><td align="right"><input class="form-control suma" type="text" id="nhaber_' + filas + '" name="nhaber[]" value="' + haber + '" style="text-align:right;" /></td><td align="center"><i class="fa fa-times-circle-o" onclick="eliminar(' + filas + ');sumar();" style="cursor:pointer;"></i></td></tr>';
                $('#detalleAsiento').before(html);
                $('#id_plan').val('').trigger('change');
                $('#debe').val('0.00');
                $('#haber').val('0.00');
                sumar();
            } else {
                $('#myModal').modal('toggle');
                mensaje('Por favor debe ingresar un valor en debe o en haber...', 'warning', null, null, 'myModal');
            }
        } else {
            $('#myModal').modal('toggle');
            mensaje('Seleccion la cuenta a usar...', 'warning', null, null, 'myModal');
        }
    });
    $('.suma').blur(function () {
        sumar();
    });
    $('#btnguardarAsiento').click(function () {
        $('#myModal').modal('toggle');
        if ($('#glosa_asiento_cabecera').val() == '') {
            mensaje('Ingrese la glosa del asiento...', 'warning', null, null, 'myModal');
            return;
        }
        mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
    });
});
function verMayor(cod, val) {
    if (val != 0) {
        $('#tablaMayor').dataTable().fnClearTable();
        $('#tablaMayor').dataTable().fnDestroy();

        $('#tablaMayor').DataTable({
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
            pageLength: 500,
            responsive: true,
            order: [
                [1, 'asc']
            ],
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
            sAjaxSource: $('#controlador').val() + "/verMayor?cod=" + cod,
            aoColumns: [
                {
                    data: 'id_asiento_detalle',
                    render: function (data, type, row) {
                        return row.id_asiento_detalle;
                    }
                },
                {
                    data: 'fecha_asiento_detalle',
                    render: function (data, type, row) {
                        return row.fecha_asiento_detalle;
                    }
                },
                {
                    data: 'codigo_cuenta_detalle_asiento',
                    render: function (data, type, row) {
                        return row.codigo_cuenta_detalle_asiento;
                    }
                },
                {
                    data: 'nombre_cuenta_plan',
                    render: function (data, type, row) {
                        return row.nombre_cuenta_plan;
                    }
                }, {
                    data: 'glosa_asiento_cabecera',
                    render: function (data, type, row) {
                        return row.glosa_asiento_cabecera;
                    }
                }, {
                    className: 'text-right',
                    data: 'debe_asiento_detalle',
                    render: function (data, type, row) {
                        return parseFloat(row.debe_asiento_detalle).toFixed(2);
                    }
                }, {
                    className: 'text-right',
                    data: 'haber_asiento_detalle',
                    render: function (data, type, row) {
                        return parseFloat(row.haber_asiento_detalle).toFixed(2);
                    }
                }
            ]
        });
        $('#myModal').modal('toggle');
    }
}
function limpiarFormulario() {
    $('#frm_')[0].reset();
    $('#frm_').trigger('reset');
    $('.select2_demo_1').val('').trigger('change');
}
function sumar() {
    var debe = 0;
    var haber = 0;
    $("#frm_").find(':input').each(function () {
        var elemento = this;
        if (elemento.type == 'text') {
            if ($(elemento).val() != '') {
                if (/ndebe_/.test(elemento.id)) {
                    debe += parseFloat($(elemento).val().replace(',', ''));
                } else if (/nhaber_/.test(elemento.id)) {
                    haber += parseFloat($(elemento).val().replace(',', ''));
                }
            }
        }
    });
    $('#debe_asiento_cabecera').val(debe.toFixed(2));
    $('#haber_asiento_cabecera').val(haber.toFixed(2));
    if (debe.toFixed(2) == haber.toFixed(2)) {
        $('#estadoconte').css({background: '#E3F8D1'});
        $('#estado').html('<label style="color:blue;">Cuadrado</label>');
    } else {
        $('#estadoconte').css({background: '#F3A8AA'});
        $('#estado').html('<label style="color:red;">No Cuadrado</label>');
    }
}
