var fila = 0;
var filaDetalle = 0;
$(document).ready(function () {
    try {
        $("#tablaVentas").DataTable({
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
                [0, 'asc']
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
                    data: 'id_venta_cabecera'
                },
                {
                    data: 'id_venta_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return PadLeft(row.establecimiento_venta_cabecera, 3, 0) + '-' + PadLeft(row.emision_venta_cabecera, 3, 0) + '-' + PadLeft(row.num_factura_venta_cabecera, 9, 0);
                    }
                },
                {
                    data: 'id_venta_cabecera',
                    render: function (data, type, row) {
                        return row.nombre_persona + ' ' + row.apellido_persona;
                    }
                },
                {
                    data: 'id_venta_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return row.estado_sri_venta_cabecera;
                    }
                }, {
                    data: 'id_venta_cabecera',
                    className: 'text-right',
                    render: function (data, type, row) {
                        return parseFloat(row.total_venta_cabecera).toFixed(2);
                    }
                }, {
                    className: 'text-center',
                    data: 'usuario_venta_cabecera'
                }, {
                    className: 'text-center',
                    data: 'fecha_emision_venta_cabecera'
                }, {
                    data: 'id_venta_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        return row.estado_venta_cabecera;
                    }
                }, {
                    data: 'id_venta_cabecera',
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (row.estado_venta_cabecera == 1) {
                            return '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i> </button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="ingresarDocumento(\'' + row.clave_acceso_venta_cabecera + '\')"><i class="fa-duotone fa-pen-field"></i> ' + $('#ingresar').val() + '</a></li></ul></div>';
                        } else {
                            return '<div>Algo</div>';
                        }
                    }
                },
            ]
        });
    } catch (error) {}
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
    $('#btnagregarAdicional').click(function () {
        if (filaDetalle < 5) {
            var tabla = '<tr id="detalle_' + filaDetalle + '">';
            tabla += '<td><input type="text" class="form-control" id="nombreAdicional_' + filaDetalle + '" name="nombreAdicional[]" placeholder="Nombre" /></td>';
            tabla += '<td><input type="text" class="form-control" id="valorAdicional_' + filaDetalle + '" name="valorAdicional[]" placeholder="Detalle" /></td>';
            tabla += '<td><a href="javascript:;" onclick="eliminarDetalle(\'' + filaDetalle + '\');" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a></td>';
            tabla += '</tr>';
            $('#tablaAdicionales').append(tabla);
            $('#nombreAdicional_' + filaDetalle).focus();
            filaDetalle++;
        } else 
            mensaje('Solo puede agregar hasta 5 detalles adicionales', 'info');
        
    });
    $('#agregarLinea').click(function () {
        if ($('#id_cliente').val() == '') {
            mensaje('Seleccione un cliente', 'warning');
            return;
        }
        $.ajax({
            url: $('#controlador').val() + '/agregarLinea',
            type: 'post',
            data: {
                _token: $('#token').val(),
                fila: fila,
                tipo: 'venta'
            },
            dataType: 'html',
            success: function (html) {
                $('#detalleFactura').append(html);
                fila++;
            }
        });
    });
    $('#agregarCliente').click(function () {
        $('#myModalCliente').modal('toggle');
    });
    $('#btnguardarFactura').click(function () {
        if ($('#pagado').is(':checked')) {
            if ($('#id_cuenta_venta_cabecera').val() == '') {
                mensaje('Seleccione la cuenta a la que se abona le pago', 'info');
                return;
            }
        }
        $.ajax({
            url: $('#action').val(),
            type: 'post',
            data: $('#frm_factura').serialize(),
            dataType: 'json',
            success: function (json) {
                var mns = json.message.split('|');
                console.log(mns);
                if (mns[0] == 'ok') {
                    mensaje('success', mns[1]);
                    window.open($('#controlador').val() + '/printFactura?num=' + mns[2], '_new');
                    window.open($('#controlador').val() + '/ventas', '_self');
                } else {
                    mensaje('error', mns[1]);
                }
            }
        });
    });
    if ($('#pagado').is(':checked')) {
        $('#cuentasFactura').show();
    } else {
        $('#cuentasFactura').hide();
        $('#id_cuenta_venta_cabecera').val('').trigger('change');
    }
    $('#pagado').change(function () {
        if ($('#pagado').is(':checked')) {
            $('#cuentasFactura').show();
        } else {
            $('#cuentasFactura').hide();
            $('#id_cuenta_venta_cabecera').val('').trigger('change');
        }
    });
});
function eliminarFila(id) {
    $('#lineaFactura_' + id).remove();
    fila--;
    calcular();
}
function eliminarDetalle(id) {
    $('#detalle_' + id).remove();
    filaDetalle--;
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
            if (/precio_/.test(elemento.id)) {
                num = elemento.id.replace('precio_', '');
                productos = $('#productos_' + num).val().split('|');
                p1 = parseFloat(productos[1]).toFixed(2);
                p2 = parseFloat(productos[2]).toFixed(3);
                p3 = parseFloat(productos[3]).toFixed(4);
                p4 = parseFloat(productos[4]).toFixed(5);
                $('#precio_' + num).val(p1);
                if (id.replace('valorventa', '') == 1) 
                    $('#precio_' + num).val(p1);
                 else if (id.replace('valorventa', '') == 2) 
                    $('#precio_' + num).val(p2);
                 else if (id.replace('valorventa', '') == 3) 
                    $('#precio_' + num).val(p3);
                 else if (id.replace('valorventa', '') == 4) 
                    $('#precio_' + num).val(p4);
                


            }
        }
    });
    calcular();
}
function seleccionarProducto(producto, fila) {
    if (producto.value !== '') {
        var dProducto = producto.value.split('|');
        $('#codigo_' + fila).val(dProducto[5]);
        $('#precio_' + fila).val(parseFloat(dProducto[$('#valor_compra_cliente').val()]).toFixed(2));
        $('#iiva_' + fila).val(dProducto[6]);
        calcular();
    }
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
        $("#frm_factura").find(':input').each(function () {
            var elemento = this;
            if (/cantidad_/.test(elemento.id) || /precio_/.test(elemento.id) || /total_/.test(elemento.id) || /descuento_/.test(elemento.id)) {
                if ($(elemento).val() != '') {
                    if (/cantidad/.test(elemento.id)) {
                        if ($(elemento).val() != '') {
                            cantidad = $(elemento).val();
                        }
                    }
                    if (/precio_/.test(elemento.id)) {
                        if ($(elemento).val() != '') {
                            precio = $(elemento).val();
                            total_fila = cantidad * parseFloat(precio);
                            var to = elemento.id.replace('precio_', '');
                            descuento += parseFloat($('#descuento_' + to).val());
                            $('#total_' + to).val(parseFloat(total_fila).toFixed(2));
                            is_iva = $('#productos_' + to).val().split('|');
                            if (is_iva[6] > 0) 
                                subtotal12 += parseFloat(cantidad * parseFloat(precio));
                             else 
                                subtotal0 += parseFloat(cantidad * parseFloat(precio));
                             iva += ((is_iva[6]) * (total_fila - parseFloat($('#descuento_' + to).val()))) / 100;
                        }
                    }
                }
            }
        });
    } catch (error) {
        mensaje('error', error.message);
    }
    var subtotal0 = parseFloat(subtotal0);
    var subtotal12 = parseFloat(subtotal12);
    var total_pagar = parseFloat(subtotal0) + parseFloat(subtotal12) + parseFloat(iva) + parseFloat($('#propina').val() - parseFloat(descuento));
    var total = subtotal0 + subtotal12 - descuento;
    $('#subtotal12').val(subtotal12.toFixed(2));
    $('#subtotal0').val(subtotal0.toFixed(2));
    $('#descuento').val(descuento.toFixed(2));
    $('#total').val(total.toFixed(2));
    $('#iva').val(iva.toFixed(2));
    $('#total_pagar').val(total_pagar.toFixed(2));

}
