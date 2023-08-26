$(document).ready(function () {
    $('#tablaDocumentos').DataTable({
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
        ]
    });
    $('#docSri').change(function () {
        var inputFileImage = document.getElementById('docSri');
        var file = inputFileImage.files[0];
        var fileSize = $('#docSri')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            $('#estado_firma').html('');
            $('#estado_firma').css({'display': 'none'});
            $('#docSri').val('');
            alert('Error: el archivo es muy pesado para ser procesado');
        } else {
            var fd = new FormData();
            fd.append('docSri', file);
            fd.append('tipo_archivo', 'docSri');
            fd.append('_token', $('#token').val());
            $.ajax({
                url: $('#controlador').val() + '/importDocs',
                data: fd,
                processData: false,
                contentType: false,
                type: 'post',
                success: function () {
                    $('#tablaDocumentos').dataTable().fnClearTable();
                    $('#tablaDocumentos').dataTable().fnDestroy();
                    $("#tablaDocumentos").DataTable({
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
                        sAjaxSource: $('#controlador').val() + "/getDatosSriImport",
                        aoColumns: [
                            {
                                data: 'no'
                            },
                            {
                                className: 'text-center',
                                data: 'estado'
                            },
                            {
                                className: 'text-center',
                                data: 'tipo'
                            },
                            {
                                className: 'text-center',
                                data: 'num_comprobante'
                            }, {
                                className: 'text-center',
                                data: 'ruc_emisior'
                            }, {
                                data: 'razon_social'
                            }, {
                                className: 'text-center',
                                data: 'fecha_emision'
                            }, {
                                className: 'text-center',
                                data: 'fecha_autorizacion'
                            }, {
                                className: 'text-center',
                                data: 'clave_acceso'
                            }, {
                                className: 'text-center',
                                data: 'num_autorizacion'
                            }, {
                                data: 'no',
                                className: 'text-center',
                                render: function (data, type, row) {
                                    if (row.estado == '') {
                                        return `<div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                                <i class="fa-brands fa-joomla"></i> 
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:;" onclick="ingresarDocumento('${
                                            row.clave_acceso
                                        }')"><i class="fa-duotone fa-pen-field"></i> ${
                                            $('#ingresar').val()
                                        }</a></li>
                                            </ul>
                                        </div>`;
                                    } else {
                                        return '';
                                    }
                                }
                            },
                        ]
                    });
                    $('#docSri').val('');
                }
            });
        }
    });
    $('#btnguardarCompraXml').click(function () {
        if ($('#id_sustento_tributario_compra_cabecera').val() == '') {
            $('#myModalCompra').modal('toggle');
            mensaje('Seleccione el sustento tributario...', 'info', null, null, 'myModalCompra');
            return;
        } else if ($('#id_forma_pago_compra_cabecera').val() == '') {
            $('#myModalCompra').modal('toggle');
            mensaje('Seleccione una forma de pago...', 'info', null, null, 'myModalCompra');
            return;
        } else if ($('#dias_credito_compra_cabecera').val() == '') {
            $('#myModalCompra').modal('toggle');
            mensaje('Seleccione el tiempo de pago...', 'info', null, null, 'myModalCompra');
            return;
        }
        $('#myModalCompra').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?', 'question', null, 'form');
    });
});
function ingresarDocumento(clave) {
    $.ajax({
        url: $('#controlador').val() + '/traerDocumentoSri',
        type: 'post',
        data: {
            _token: $('#token').val(),
            clave: clave
        },
        dataType: 'json',
        success: function (json) {
            $('#id_forma_pago_compra_cabecera').val('').trigger('change');
            $('#id_sustento_tributario_compra_cabecera').val('').trigger('change');
            $('#dias_credito_compra_cabecera').val('').trigger('change');
            if (json.status == true) {
                var fechaEmision = json.comprobante.infoFactura.fechaEmision.split('/');
                $('#fecha_emision_compra_cebacera').val(fechaEmision[2] + '-' + fechaEmision[1] + '-' + fechaEmision[0]);
                $('#id_proveedor_compra_cabecera').val(json.proveedor.id_proveedor).trigger('change');
                $('#id_tipo_documento_compra_cabecera').val(json.id_tipo_doc).trigger('change');
                $('#serie_compra_cabecera').val(json.comprobante.infoTributaria.estab + '-' + json.comprobante.infoTributaria.ptoEmi);
                $('#secuencial_tributario_compra_cabecera').val(json.comprobante.infoTributaria.secuencial);
                $('#clave_acceso_compra_cabecera').val(json.comprobante.infoTributaria.claveAcceso);
                var documento = $('#id_tipo_documento_compra_cabecera option:selected').html().split(' | ');
                var glosa = $('#id_proveedor_compra_cabecera option:selected').html() + ' | ' + documento[1] + ' | COMPRA | ' + json.comprobante.infoTributaria.estab + '-' + json.comprobante.infoTributaria.ptoEmi + '-' + json.comprobante.infoTributaria.secuencial;
                $('#glosa_asiento_cabecera').val(glosa);

                var html = '';
                var impuesto = '';
                var ivaTotal = 0;
                var tarifaIva = 0;
                var tarifa0 = 0;
                var tarifa12 = 0;
                if (json.comprobante.detalles.detalle.length === undefined) {
                    $.each(json.comprobante.detalles.detalle.impuestos, function (k, item_1) {
                        impuesto = '';
                        if (item_1.tarifa > 0) 
                            tarifa12 += parseFloat(item_1.baseImponible);
                         else 
                            tarifa0 += parseFloat(item_1.baseImponible);
                         impuesto += '<input type="hidden" id="tarifa" name="tarifa[]" value="' + item_1.tarifa + '" /><input type="hidden" id="valor" name="valor[]" value="' + item_1.valor + '" />';
                        ivaTotal += parseFloat(item_1.valor);
                    });
                    html += '<tr>';
                    html += '<td style="text-align:center"><input type="hidden" id="codigoPrincipal" name="codigoPrincipal[]" value="' + json.comprobante.detalles.detalle.codigoPrincipal + '" /><input type="hidden" id="codigoAuxiliar" name="codigoAuxiliar[]" value="' + json.comprobante.detalles.detalle.codigoAuxiliar + '" />' + json.comprobante.detalles.detalle.codigoPrincipal + '</td>';
                    html += '<td style="text-align:right"><input type="hidden" id="cantidad" name="cantidad[]" value="' + json.comprobante.detalles.detalle.cantidad + '" />' + json.comprobante.detalles.detalle.cantidad + '</td>';
                    html += '<td><input type="hidden" id="descripcion" name="descripcion[]" value="' + json.comprobante.detalles.detalle.descripcion + '" />' + json.comprobante.detalles.detalle.descripcion + '</td>';
                    html += '<td>' + impuesto + '<div class="form-group"><div class="input-group date"><select class="form-control select2_demo_45" id="tipo_contable_producto_0" name="tipo_contable_producto[]" onchange="traerCuentaContable(\'0\')" width="100%"><option value="">--Seleccionar--</option><option value="activo">1 | Activos</option><option value="pasivo">2 | Pasivos</option><option value="ingreso">4 | Ingresos</option><option value="costo">5.01 | Costos</option><option value="gasto">5.02 | Gastos</option></select></div></div></td>';
                    html += '<td><div class="form-group"><div class="input-group date"><select class="form-control select2_demo_45" id="id_plan_cuenta_producto_0" name="id_plan_cuenta_producto[]" width="100%"><option value="">--Seleccionar--</option></select></div></div></td>';
                    html += '<td style="text-align:right"><input type="hidden" id="precioUnitario" name="precioUnitario[]" value="' + json.comprobante.detalles.detalle.precioUnitario + '" />' + json.comprobante.detalles.detalle.precioUnitario + '</td>';
                    html += '<td style="text-align:right"><input type="hidden" id="descuento" name="descuento[]" value="' + json.comprobante.detalles.detalle.descuento + '" />' + parseFloat(json.comprobante.detalles.detalle.descuento).toFixed(2) + '</td>';
                    html += '<td style="text-align:right"><input type="hidden" id="precioTotalSinImpuesto" name="precioTotalSinImpuesto[]" value="' + json.comprobante.detalles.detalle.precioTotalSinImpuesto + '" />' + json.comprobante.detalles.detalle.precioTotalSinImpuesto + '</td>';

                    html += '</tr>';
                } else {
                    $.each(json.comprobante.detalles.detalle, function (i, item) {
                        $.each(item.impuestos, function (j, item_) {
                            impuesto = '';
                            tarifaIva = item_.tarifa;
                            if (item_.tarifa > 0) 
                                tarifa12 += parseFloat(item_.baseImponible);
                             else 
                                tarifa0 += parseFloat(item_.baseImponible);
                             impuesto += '<input type="hidden" id="tarifa" name="tarifa[]" value="' + item_.tarifa + '" /><input type="hidden" id="valor" name="valor[]" value="' + item_.valor + '" />';
                            ivaTotal += parseFloat(item_.valor);
                        });
                        html += '<tr>';
                        html += '<td style="text-align:center"><input type="hidden" id="codigoPrincipal" name="codigoPrincipal[]" value="' + item.codigoPrincipal + '" /><input type="hidden" id="codigoAuxiliar" name="codigoAuxiliar[]" value="' + item.codigoAuxiliar + '" />' + item.codigoPrincipal + '</td>';
                        html += '<td style="text-align:righy"><input type="hidden" id="cantidad" name="cantidad[]" value="' + item.cantidad + '" />' + item.cantidad + '</td>';
                        html += '<td><input type="hidden" id="descripcion" name="descripcion[]" value="' + item.descripcion + '" />' + item.descripcion + '</td>';
                        html += '<td>' + impuesto + '<div class="form-group"><div class="input-group date"><select class="form-control select2_demo_45" id="tipo_contable_producto_' + i + '" name="tipo_contable_producto[]" onchange="traerCuentaContable(\'' + i + '\')" width="100%"><option value="">--Seleccionar--</option><option value="activo">1 | Activos</option><option value="pasivo">2 | Pasivos</option><option value="ingreso">4 | Ingresos</option><option value="costo">5.01 | Costos</option><option value="gasto">5.02 | Gastos</option></select></div></div></td>';
                        html += '<td><div class="form-group"><div class="input-group date"><select class="form-control select2_demo_45" id="id_plan_cuenta_producto_' + i + '" name="id_plan_cuenta_producto[]" width="100%"><option value="">--Seleccionar--</option></select></div></div></td>';
                        html += '<td style="text-align:right"><input type="hidden" id="precioUnitario" name="precioUnitario[]" value="' + item.precioUnitario + '" />' + item.precioUnitario + '</td>';
                        html += '<td style="text-align:right"><input type="hidden" id="descuento" name="descuento[]" value="' + item.descuento + '" />' + parseFloat(item.descuento).toFixed(2) + '</td>';
                        html += '<td style="text-align:right"><input type="hidden" id="precioTotalSinImpuesto" name="precioTotalSinImpuesto[]" value="' + item.precioTotalSinImpuesto + '" />' + parseFloat(item.precioTotalSinImpuesto).toFixed(2) + '</td>';

                        html += '</tr>';
                    });
                } html += '<script>$(document).ready(function(){$(".select2_demo_45").select2({theme: \'bootstrap4\'});});</script>';
                $('#cuerpo').html(html);
                $('#tarifa0').html(parseFloat(tarifa0).toFixed(2));
                $('.tarifaIva').html(tarifaIva);
                $('#subtotal_0_compra_cabecera').val(parseFloat(tarifa0).toFixed(2));
                $('#tarifa12').html(parseFloat(tarifa12).toFixed(2));
                $('#subtotal_12_compra_cabecera').val(parseFloat(tarifa12).toFixed(2));
                $('#subtotal').html(parseFloat(json.comprobante.infoFactura.totalSinImpuestos).toFixed(2));
                $('#totalDescuento').html(parseFloat(json.comprobante.infoFactura.totalDescuento).toFixed(2));
                $('#descuento_compra_cabecera').val(parseFloat(json.comprobante.infoFactura.totalDescuento).toFixed(2));
                $('#total').html((parseFloat(json.comprobante.infoFactura.totalSinImpuestos) - parseFloat(json.comprobante.infoFactura.totalDescuento)).toFixed(2));
                $('#iva').html(parseFloat(ivaTotal).toFixed(2));
                $('#iva_compra_cabecera').val(parseFloat(ivaTotal).toFixed(2));
                $('#total_pagar').html((parseFloat(json.comprobante.infoFactura.totalSinImpuestos) - parseFloat(json.comprobante.infoFactura.totalDescuento) + parseFloat(ivaTotal)).toFixed(2));
                $('#total_compra_cabecera').val((parseFloat(json.comprobante.infoFactura.totalSinImpuestos) - parseFloat(json.comprobante.infoFactura.totalDescuento) + parseFloat(ivaTotal)).toFixed(2));

                var arrayPago = [];
                $.each(json.forma_pago, function (i, pagos) {
                    arrayPago[i] = pagos.id_metodo_pago;
                });
                console.log(arrayPago);
                $('#id_forma_pago_compra_cabecera').val(arrayPago).trigger('change');

                $('#myModalCompra').modal('toggle');
            } else {
                mensaje(json.error, 'error');
            }
        }
    });
}
function traerCuentaContable(id) {
    $.ajax({
        url: 'inventario/traerPlanCuenta',
        type: 'post',
        data: {
            _token: $('#token').val(),
            tipo: $('#tipo_contable_producto_' + id).val()
        },
        dataType: 'json',
        success: function (json) {
            var html = '';
            html = '<option value="">--Seleccionar--</option>';
            var select = '';
            $.each(json, function (i, item) {
                html += '<option value="' + item.id_plan + '" >' + item.codigo_contable_plan + ' | ' + item.nombre_cuenta_plan + '</option>'
            });
            $('#id_plan_cuenta_producto_' + id).html(html).trigger('change');
        }
    });
}
