var filaNotaReparacion = 0;
$(document).ready(function () {
    document.querySelector(".navbar-minimalize").click();
    $('.oculto').hide();
    $('#tablaReparaciones').DataTable({
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
                data: 'numero_nota',
                render: function (data, type, row) {
                    return row.numero_nota;
                }
            },
            {
                data: 'modelo_nota',
                render: function (data, type, row) {
                    return row.nombre_subcategoria + ' ' + row.modelo_nota;
                }
            },
            {
                className: 'text-center',
                data: 'tipo_nota',
                render: function (data, type, row) {
                    var tipo = row.tipo_nota.split(' ');
                    var comp = '';
                    let result = tipo[0].substring(0, 1);
                    try {
                        comp = '-' + tipo[1].substring(0, 1);
                        comp = comp.toUpperCase();
                    } catch (error) {
                        comp = '';
                    }
                    return result + comp;
                }
            },
            {
                className: 'text-center',
                data: 'numero_nota',
                render: function (data, type, row) {
                    var html = '<img src="public/img/ok.png" style="width:28px" />';
                    if (row.presupuesto_nota == 0) 
                        html = '<a href="javascript:;" onclick="presupuesto(\'' + row.id_nota + '\');"><img src="public/img/remove-128.png" style="width:28px" /></a>';
                    return html;
                }
            }, {
                className: 'text-center',
                data: 'numero_nota',
                render: function (data, type, row) {
                    var html = '<img src="public/img/ok.png" style="width:28px" />';
                    if (row.repuesto_nota == 0) 
                        html = '<a href="javascript:;" onclick="repuesto(\'' + row.id_nota + '\',\'' + row.presupuesto_nota + '\',\'' + row.numero_nota + '\',\'' + row.cliente + '\',\'' + row.fecha_nota + '\');"><img src="public/img/remove-128.png" style="width:28px" /></a>';
                    return html;
                }
            }, {
                className: 'text-center',
                data: 'numero_nota',
                render: function (data, type, row) {
                    var html = '<img src="public/img/ok.png" style="width:28px" />';
                    if (row.reparado_nota == 0) 
                        html = '<a href="javascript:;" onclick="reparar(\'' + row.id_nota + '\',\'' + row.repuesto_nota + '\');"><img src="public/img/remove-128.png" style="width:28px" /></a>';
                    return html;
                }
            }, {
                className: 'text-center',
                data: 'numero_nota',
                render: function (data, type, row) {
                    var html = '<img src="public/img/ok.png" style="width:28px" />';
                    if (row.retirado_nota == 0) 
                        html = '<a href="javascript:;" onclick="retirar(\'' + row.id_nota + '\',\'' + row.reparado_nota + '\',\'' + row.tipo_nota + '\');"><img src="public/img/remove-128.png" style="width:28px" /></a>';
                    return html;
                }
            }, {
                className: 'text-right',
                data: 'total_nota',
                render: function (data, type, row) {
                    return parseFloat(row.total_nota).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'abono_nota',
                render: function (data, type, row) {
                    return parseFloat(row.abono_nota).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'numero_nota',
                render: function (data, type, row) {
                    var saldo = parseFloat(row.total_nota) - parseFloat(row.abono_nota);
                    return parseFloat(saldo).toFixed(2);
                }
            }, {
                className: 'text-center',
                data: 'usuario',
                render: function (data, type, row) {
                    return row.usuario;
                }
            }, {
                className: 'text-center',
                data: 'fecha_nota',
                render: function (data, type, row) {
                    return row.fecha_nota;
                }
            }, {
                className: 'text-center',
                data: 'fecha_entrega_nota',
                render: function (data, type, row) {
                    return row.fecha_entrega_nota;
                }
            }, {
                data: 'cliente',
                render: function (data, type, row) {
                    return row.cliente;
                }
            }, {
                className: 'text-center',
                data: 'telefono',
                render: function (data, type, row) {
                    return row.telefono;
                }
            }, {
                data: 'id_nota',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_nota + '\',1)"><i class="fa-regular fa-circle" style="color:blue;"></i> ' + $('#anular').val() + '</a></li>';
                    if (row.estado_nota == 0) 
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa(\'' + row.id_nota + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $('#activar').val() + '</a></li>';
                    return '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i> </button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_nota + '\',1)"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_nota + '\',0)"><i class="fa fa-edit"></i> ' + $('#editar').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa(\'' + row.id_nota + '\',0)"><i class="fa-light fa-print"></i> ' + $('#imprimir').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                }
            },
        ]
    });
    $('#btnagregarNota').click(function () {
        $.ajax({
            url: $('#controlador').val() + '/getNumNotaReparacion',
            type: 'post',
            data: {
                _token: $('#token').val()
            },
            dataType: 'json',
            success: function (json) {
                $('#numero_nota').val(json);
            }
        });
        $('#myModalNota').modal('toggle');
    });
    $("#id_cliente").select2({
        theme: 'bootstrap4',
        noResults: function () {
            return "No hay resultado";
        },
        searching: function () {
            return "Buscando..";
        },
        placeholder: "--Seleccione un cliente--",
        minimumInputLength: 3,
        ajax: {
            url: $('#controlador').val() + '/buscarCliente',
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
        if ($("#id_cliente").val() == 0) {
            $('#myModalCliente').modal('toggle');
        } else {
            var datos = $("#id_cliente").val().split('|');
            $('#telefono').val(datos[2] + ' - ' + datos[3]);
            $('#email').val(datos[4]);
            $('#valor_compra_cliente').val(datos[1]);
            $('.valorventa').attr('disabled', false);
            if (datos[5] == 'p') {
                $.ajax({
                    url: $('#controlador').val() + '/asignarCliente',
                    type: 'post',
                    data: {
                        _token: $('#token').val(),
                        id: datos[0]
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
    $('#id_articulo').change(function () {
        $.ajax({
            url: $('#controlador').val() + '/getModelosCombo',
            type: 'post',
            data: {
                _token: $('#token').val(),
                id: $('#id_articulo').val()
            },
            dataType: 'json',
            success: function (json) {
                var html = '<option value="">--Seleccionar--</option>';
                $.each(json, function (i, item) {
                    var select = '';
                    if ($('#id_modelo').val() == item.modelo_producto) 
                        select = 'selected';
                    


                    html += '<option value="' + item.modelo_producto + '" ' + select + '>' + item.modelo_producto + '</option>';
                });
                html += '<option value="agregar">--Agregar--</option>';
                $('#modelo').html(html).trigger('change');
            }
        });
    });
    $('#modelo').change(function () {
        if ($('#modelo').val() == 'agregar') {
            // $('#cambioModelo').append('<span class="input-group-addon"><i class="fa-solid fa-pencil"></i></span><input type="text" id="modelo" name="modelo" placeholder="Agregar modelo" class="form-control">');
            //     $('#myModalNota').css({'overflow-y': 'scroll'});
            //     $('#myModalNota').focus();
            //     $('.modal-body').focus();
            //     $('.modal-body').css({'overflow-y': 'scroll'});
            //    dropdownParent: $('#myModalNota');
            $('.modelo_').show();
        } else {
            $('.modelo_').hide();
        }
    });
    $('#id_tipo_nota').change(function () {
        var text = $('select[name="id_tipo_nota"] option:selected').text();
        if (/Garant/.test(text)) 
            $('.oculto').show();
         else 
            $('.oculto').hide();
    });
    $('#btnguardarNota').click(function () {
        if (!validarRangoFecha($('#fecha_hoy').val(), $('#fecha_entrega').val())) {
            $('#myModalNota').modal('toggle');
            mensaje('La fecha de entrega debe ser mayor o igual a la de hoy...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#id_cliente').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Seleccione un cliente, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#punto_venta').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Seleccione un punto de venta, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#fecha_entrega').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Ingrese la fecha de entrega estimada, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#id_articulo').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Seleccione un artículo, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#id_marca').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Seleccione una marca, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#accesorio').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Ingrese si el artículo tiene o no accesorios, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else if ($('#id_tipo_nota').val() == '') {
            $('#myModalNota').modal('toggle');
            mensaje('Seleccione el tipo de nota, por favor...', 'warning', null, null, 'myModalNota', false);
            return;
        } else {
            $('#myModalNota').modal('toggle');
            mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_');
        }
    });
    $('#chenhr').change(function () {
        if ($('#chenhr').is(':checked')) 
            $('#nhr').val(1);
         else 
            $('#nhr').val(0);
    });
    $('#cherepos').change(function () {
        if ($('#cherepos').is(':checked')) 
            $('#repos').val(1);
         else 
            $('#repos').val(0);
    });
    $('#cheautorizado').change(function () {
        if ($('#cheautorizado').is(':checked')) 
            $('#autorizado').val(1);
         else 
            $('#autorizado').val(0);
    });
    $('#chedoc_entregado').change(function () {
        if ($('#chedoc_entregado').is(':checked')) 
            $('#doc_entregado').val(1);
         else 
            $('#doc_entregado').val(0);
    });
    $('#btnAgregarLineaNota').click(function () {
        $.ajax({
            url: $('#controlador').val() + '/traerProductos',
            type: 'post',
            data: {
                _token: $('#token').val(),
                id: $('#id_articulo').val()
            },
            dataType: 'json',
            success: function (json) {
                var combo = '';
                $.each(json, function (i, item) {
                    combo += '<option value="' + item.id_producto + '|' + item.codigo_producto + '|' + item.valor1_producto + '|' + item.valor2_producto + '|' + item.valor3_producto + '|' + item.valor4_producto + '|' + item.procentaje_iva + '">' + item.codigo_producto + '|' + item.descripcion_producto + '|' + item.modelo_producto + '</option>';
                });
                var html = '<tr id="lineaNota' + filaNotaReparacion + '">';
                html += '<td><input type="text" id="codigo' + filaNotaReparacion + '" name="codigo[]" class="form-control text-center" readonly /></td>';
                html += '<td><select class="form-control productos" id="producto' + filaNotaReparacion + '" name="producto[]" onchange="asignarProducto(\'' + filaNotaReparacion + '\');calcular();" style="width:100%;"><option value="">--Seleccionar--</option>' + combo + '</select></td>';
                html += '<td><input type="hidden" id="iva' + filaNotaReparacion + '" name="iva[]" /><input type="text" id="cantidad' + filaNotaReparacion + '" name="cantidad[]" onblur="calcular();" class="form-control text-right" /></td>';
                html += '<td><input type="text" id="precio' + filaNotaReparacion + '" name="precio[]" onblur="calcular();" class="form-control text-right" /></td>';
                html += '<td><input type="text" id="descuento' + filaNotaReparacion + '" name="descuento[]" onblur="calcular();" class="form-control text-right" /></td>';
                html += '<td><input type="text" id="total' + filaNotaReparacion + '" name="total[]" onblur="calcular();"class="form-control text-right" readonly /></td>';
                html += '<td align="center"><a href="javascript:;" onclick="eliminarLineaNota(\'' + filaNotaReparacion + '\')"><i class="fa-solid fa-trash-can fa-2x" style="color:red;"></i></a></td>';
                html += '</tr>';
                html += '<script>$(document).ready(function(){$(\'.productos\').select2({theme: \'bootstrap4\'});});</script>';
                $('#cuerpo').append(html);
                filaNotaReparacion++;
            }
        });
    });
    $('#btnguardarNotaRepuestos').click(function () {
        if ($('#id_bodega').val() == '') {
            $('#myModalRepuesto').modal('toggle');
            mensaje('Seleccione la bodega de los repuestos...', 'info', null, null, 'myModalRepuesto');
        } else if ($('#id_tecnico').val() == '') {
            $('#myModalRepuesto').modal('toggle');
            mensaje('Seleccione un técnico...', 'info', null, null, 'myModalRepuesto');
        } else {
            $('#myModalRepuesto').modal('toggle');
            mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_repuesto', 'myModalRepuesto', false, 'actionRepuestos');
        }
    });
    $('#btnguardarNotaReparar').click(function () {
        if ($('#id_tecnicoReparacion').val() == '') {
            $('#myModalReparar').modal('toggle');
            mensaje('Seleccione un tecnico el cual va a reparar el artículo...', 'info', null, null, 'myModalReparar');
        } else {
            $('#myModalReparar').modal('toggle');
            mensaje('Esta segur@ de guardar los datos?', 'question', null, 'frm_reparar', 'myModalReparar', false, 'actionReparar');
        }
    });
});
function presupuesto(id) {
    $.ajax({
        url: $('#controlador').val() + '/darPresupuesto',
        type: 'post',
        data: {
            _token: $('#token').val(),
            id: id
        },
        dataType: 'json',
        success: function (json) {
            $('#id_nota').val(json.id_nota);
            $('#nhr').val(json.nhr_nota);
            if (json.nhr_nota == 1) 
                $('#chenhr').prop('checked', true);
            


            $('#repos').val(json.repos_nota);
            if (json.reposicion_nota == 1) 
                $('#cherepos').prop('checked', true);
            


            $('#autorizado').val(json.autorizado_nota);
            if (json.autorizado_nota == 1) 
                $('#cheautorizado').prop('checked', true);
            


            $('#doc_entregado').val(json.documento_nota);
            if (json.documento_nota == 1) 
                $('#chedoc_entregado').prop('checked', true);
            


            $('#id_usuario_creacion_nota').val(json.id_usuario_creacion_nota);
            $('#fecha_nota').val(json.fecha_nota);
            $('#id_modelo').val(json.modelo_nota);
            $('#id_articulo').val(json.id_artefacto_nota).trigger('change');
            $('#idC').val(json.id_cliente_nota);
            if ($('#idC').val() !== undefined && $('#idC').val() != '') {
                var cliente = '';
                id_cliente = '';
                valor_compra = '';
                $.ajax({
                    url: $('#controlador').val() + '/comboClienteSeleccionado',
                    data: {
                        id: $('#idC').val()
                    },
                    type: 'get',
                    dataType: 'html',
                    success: function (html) {
                        $('#id_cliente').html(html).trigger('change');
                        cliente = $('#id_cliente').val().split('|');
                        id_cliente = cliente[0];
                        valor_compra = cliente[1];
                        $('#id_cliente').select2({
                            placeholder: "--Seleccione un cliente--",
                            minimumInputLength: 3,
                            ajax: {
                                url: $('#controlador').val() + '/buscarCliente',
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
                        }).focus();
                    }
                });
            }
            $('#numero_nota').val(json.numero_nota);
            $('#observacion_del_cliente').val(json.nota_del_cliente_nota);
            $('#fecha_entrega').val(json.fecha_entrega_nota);
            $('#id_marca').val(json.id_marca_nota).trigger('change');
            $('#accesorio').val(json.accesorio_nota);
            $('#id_tipo_nota').val(json.id_tipo_nota).trigger('change');
            $('#lugar_compra').val(json.lugar_compra_nota);
            $('#factura_compra').val(json.factura_compra_nota);
            $('#observacion_al_cliente').val(json.nota_al_cliente_nota);
            $('#instrucciones_al_taller').val(json.instrucciones_taller_nota);
            $('#fecha_compra').val(json.fecha_compra_nota);
            $('#myModalNota').modal('toggle');
        }
    });
}
function eliminarLineaNota(id) {
    $('#lineaNota' + id).remove();
    filaNotaReparacion--;
    calcular();
}
function asignarProducto(id) {
    var producto = $('#producto' + id).val().split('|');
    $('#codigo' + id).val(producto[1]);
    $('#cantidad' + id).val(1);
    $('#precio' + id).val(parseFloat(0).toFixed(2));
    $('#descuento' + id).val(parseFloat(0).toFixed(2));
    $('#total' + id).val(parseFloat(0).toFixed(2));
}
function calcular() {
    var cantidad = 0;
    var precio = 0;
    var total_fila = 0;
    var descuento = 0;
    var subtotal0 = 0;
    var subtotal12 = 0;
    var iva = 0;
    try {
        $("#frm_").find(':input').each(function () {
            var elemento = this;
            if (/cantidad/.test(elemento.id) || /precio/.test(elemento.id) || /total/.test(elemento.id) || /descuento/.test(elemento.id)) {
                if ($(elemento).val() != '') {
                    if (/cantidad/.test(elemento.id)) {
                        if ($(elemento).val() != '') {
                            cantidad = $(elemento).val();
                        }
                    }
                    if (/precio/.test(elemento.id)) {
                        if ($(elemento).val() != '') {
                            precio = $(elemento).val();
                            total_fila = cantidad * parseFloat(precio);
                            var to = elemento.id.replace('precio', '');
                            descuento += parseFloat($('#descuento' + to).val());
                            $('#total' + to).val(parseFloat(total_fila).toFixed(2));
                            is_iva = $('#producto' + to).val().split('|');
                            if (is_iva[6] > 0) 
                                subtotal12 += parseFloat(cantidad * parseFloat(precio));
                             else 
                                subtotal0 += parseFloat(cantidad * parseFloat(precio));
                             iva += ((is_iva[6]) * (total_fila - parseFloat($('#descuento' + to).val()))) / 100;
                            $('#iva' + to).val(iva);
                        }
                    }
                }
            }
        });
    } catch (error) {
        $('#myModalNota').modal('toggle');
        mensaje(error.message, 'error', null, null, 'myModalNota');
    }
    var subtotal0 = parseFloat(subtotal0);
    var subtotal12 = parseFloat(subtotal12);
    var total_pagar = parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva) - parseFloat(descuento);
    var total = subtotal0 + subtotal12 - descuento;
    $('#tarifa12').val(subtotal12.toFixed(2));
    $('#tarifa0').val(subtotal0.toFixed(2));
    $('#totaldescuento').val(descuento.toFixed(2));
    $('#subtotal').val(total.toFixed(2));
    $('#totaliva').val(iva.toFixed(2));
    $('#total_pagar').val(total_pagar.toFixed(2));
}
function cambiovalores(id) {
    var elemento;
    var productos = '';
    var num = 0;
    var p1 = 0;
    var p2 = 0;
    var p3 = 0;
    var p4 = 0;
    $("#frm_factura").find(':input').each(function () {
        elemento = this;
        if (elemento.type == 'radio') {
            if (/tarjeta/.test(elemento.title)) {
                if ($("#" + elemento.id).is(':checked')) {
                    $('#infotarjeta').show()
                } else {
                    $('#numtarjeta').val('');
                    $('#tarjetas').val('');
                    $('#infotarjeta').hide();
                }
            }
        }
        if (elemento.type == 'text') {
            if (/precio/.test(elemento.id)) {
                num = elemento.id.replace('precio', '');
                productos = $('#producto' + num).val().split('|');
                p1 = parseFloat(productos[1]).toFixed(2);
                p2 = parseFloat(productos[2]).toFixed(3);
                p3 = parseFloat(productos[3]).toFixed(4);
                p4 = parseFloat(productos[4]).toFixed(5);
                $('#precio' + num).val(p1);
                if (id.replace('valorventa', '') == 1) 
                    $('#precio' + num).val(p1);
                 else if (id.replace('valorventa', '') == 2) 
                    $('#precio' + num).val(p2);
                 else if (id.replace('valorventa', '') == 3) 
                    $('#precio' + num).val(p3);
                 else if (id.replace('valorventa', '') == 4) 
                    $('#precio' + num).val(p4);
                


            }
        }
    });
    calcular();
}
function repuesto(id, presupuesto, numNotaReparacion, cliente, fecha) {
    if (presupuesto == 0) {
        mensaje('La nota de reparación debe tener presupuesto, para poder pedir los repuestos...', 'info');
    } else {
        $('#numNotaReparacion').html(numNotaReparacion);
        $('#nombreCliente').html(cliente);
        $('#fechaIngreso').html(fecha);
        $.ajax({
            url: $('#controlador').val() + '/traeArticulos',
            type: 'post',
            data: {
                _token: $('#token').val(),
                id: id
            },
            dataType: 'json',
            success: function (json) {
                var html = 0;
                var cont = 0;
                $('#id_nota_reparacion').val(id);
                $.each(json, function (i, item) {
                    cont++;
                    html += '<tr><td><input type="hidden" id="id_producto" name="id_producto[]" value="' + item.id_producto_detalle_nota + '" /><input type="hidden" id="cantidad" name="cantidad[]" value="' + item.cantidad_detalle_nota + '" />' + cont + '</td><td style="text-align:center;">' + item.codigo_producto + '</td><td>' + item.descripcion_producto + '</td><td style="text-align:right;">' + item.cantidad_detalle_nota + '</td><td><i style="color:red;text-align:center;">Sin despachar</i></td></tr>';
                });
                $('#cuerpoRepuestos').html(html);
            }
        });
        $('#myModalRepuesto').modal('toggle');
    }
}
function reparar(id, repuesto) {
    if (repuesto == 0) {
        mensaje('La nota de reparación debe tener repuestos pedidos, para poder marcar como reparada...', 'info');
    } else {
        $('#id_notaReparar').val(id);
        $('#myModalReparar').modal('toggle');
    }
}
function retirar(id, reparado, tipo) {
    if (reparado == 0) {
        mensaje('La nota de reparación debe estar reparada, para poder ser retirada...', 'info');
    } else {
        $('#id_notaRetirar').val(id);
        $('#myModalRetirar').modal('toggle');
    }
}
