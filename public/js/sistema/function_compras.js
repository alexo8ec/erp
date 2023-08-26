$(document).ready(function () {
    var btnArchivoXML = $( '.ladda-button-demo-ruc' ).ladda();
    btnArchivoXML.click(function(){
        btnArchivoXML.ladda( 'start' );
    });
    $('#tablaCompras').DataTable({
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
                data: 'id_compra_cabecera',
                render: function (data, type, row) {
                    return row.id_compra_cabecera;
                }
            },
            {
                data: 'nombre_persona',
                render: function (data, type, row) {
                    var nombreProveedor=row.razon_social_persona!=null?row.nombre_comercial_persona:(row.nombre_persona!=null?row.nombre_persona+' '+row.apellido_persona:'');
                    return nombreProveedor;
                }
            },
            {
                className:'text-center',
                data: 'fecha_emision_compra_cabecera',
                render: function (data, type, row) {
                    return row.fecha_emision_compra_cabecera;
                }
            }, {
                className:'text-center',
                data: 'secuencial_compra_cabecera',
                render: function (data, type, row) {
                    return PadLeft(row.establecimiento_compra_cabecera,3)+'-'+PadLeft(row.emision_compra_cabecera,3)+'-'+ PadLeft(row.secuencial_compra_cabecera,9,0);
                }
            },
            {
                className:'text-center',
                data: 'cod_tipo_doc',
                render: function (data, type, row) {
                    return '<label title="'+row.tipo+'">'+row.cod_tipo_doc+'</label>';
                }
            }, {
                className:'text-right',
                data: 'subtotal_0_compra_cabecera',
                render: function (data, type, row) {
                    return parseFloat(row.subtotal_0_compra_cabecera).toFixed(2);
                }
            }, {
                className:'text-right',
                data: 'subtotal_12_compra_cabecera',
                render: function (data, type, row) {
                    return parseFloat(row.subtotal_12_compra_cabecera).toFixed(2);
                }
            }, {
                className:'text-right',
                data: 'descuento_compra_cabecera',
                render: function (data, type, row) {
                    return parseFloat(row.descuento_compra_cabecera).toFixed(2);
                }
            }, {
                className:'text-right',
                data: 'iva_compra_cabecera',
                render: function (data, type, row) {
                    return parseFloat(row.iva_compra_cabecera).toFixed(2);
                }
            }, {
                className:'text-right',
                data: 'total_compra_cabecera',
                render: function (data, type, row) {
                    return parseFloat(row.total_compra_cabecera).toFixed(2);
                }
            }, {
                className:'text-center',
                data: 'usuario',
                render: function (data, type, row) {
                    return row.usuario;
                }
            }, {
                data: 'estado_compra_cabecera',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_compra_cabecera==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_compra_cabecera',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa('${row.id_compra_cabecera}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_compra_cabecera==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa('${row.id_compra_cabecera}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa('${row.id_compra_cabecera}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa('${row.id_compra_cabecera}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#btnagregarCompraXml').click(function(){
        $('#myModalXml').modal('toggle');
    });
    $('#docXML').change(function () {
        var inputFileImage = document.getElementById('docXML');
        var file = inputFileImage.files[0];
        var fileSize = $('#docXML')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnArchivoXML.ladda('stop');
            $('#docXML').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('docXML', file);
            fd.append('tipo_archivo', 'docXML');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
            fd.append('_token', $('#token').val());
            $.ajax({
                url: $('#controlador').val()+'/leerCompraXml',
                data: fd,
                processData: false,
                contentType: false,
                type: 'post',
                dataType:'json',
                success: function (json) {
                    btnArchivoXML.ladda('stop');
                    if(json.status==true){
                        console.log(json.comprobante);
                        console.log(json.comprobante.infoFactura)
                        var fechaEmision=json.comprobante.infoFactura.fechaEmision.split('/');
                        $('#fecha_emision_compra_cebacera').val(fechaEmision[2]+'-'+fechaEmision[1]+'-'+fechaEmision[0]);
                        $('#proveedor').val(json.comprobante.infoFactura.razonSocialComprador+' | '+json.comprobante.infoFactura.identificacionComprador);
                        $('#myModalXml').modal('toggle');
                    }else{
                        mensaje(json.error,'info');
                    }
                }
            });
        }
    });
});
