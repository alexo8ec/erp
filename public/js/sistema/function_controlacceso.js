$(document).ready(function () {
    $('#tablaDispositivos').DataTable({
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
                data: 'id_dispositivo',
                render: function (data, type, row) {
                    return row.id_dispositivo;
                }
            },
            {
                className: 'text-right',
                data: 'cod_dispositivo',
                render: function (data, type, row) {
                    return row.cod_dispositivo;
                }
            },
            {
                data: 'nombre_dispositivo',
                render: function (data, type, row) {
                    return row.nombre_dispositivo;
                }
            },
            {
                className: 'text-center',
                data: 'direccion_ip',
                render: function (data, type, row) {
                    return row.direccion_ip;
                }
            }, {
                className: 'text-right',
                data: 'puerto_dispositivo',
                render: function (data, type, row) {
                    return row.puerto_dispositivo;
                }
            }, {
                data: 'modelo_dispositivo',
                render: function (data, type, row) {
                    return row.modelo_dispositivo;
                }
            }, {
                data: 'proveedor_dispositivo',
                render: function (data, type, row) {
                    return row.proveedor_dispositivo;
                }
            }, {
                data: 'firmware',
                render: function (data, type, row) {
                    return row.firmware;
                }
            }, {
                data: 'estado_dispositivo',
                className: 'text-center',
                render: function (data, type, row) {
                    var estado = '<span class="label label-danger">Inactivo</span>';
                    if (row.estado_dispositivo == 1) 
                        estado = '<span class="label label-primary">Activo</span>';                    
                    return estado;
                }
            }, {
                data: 'id_dispositivo',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoDispositivo(\'' + row.id_dispositivo + '\',1)"><i class="fa fa-check-circle" style="color:green;"></i> ' + $('#activar').val() + '</a></li>';
                    if (row.estado_dispositivo == 1) 
                        linea = '<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoDispositivo(\'' + row.id_dispositivo + '\',0)"><i class="fa fa-window-close" style="color:red"></i> ' + $('#desactivar').val() + '</a></li>';                    
                    return '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle"><i class="fa-brands fa-joomla"></i> </button><ul class="dropdown-menu"><li><a class="dropdown-item" href="javascript:;" onclick="verDispositivo(\'' + row.id_dispositivo + '\')"><i class="fa fa-eye"></i> ' + $('#ver').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="editarDispositivo(\'' + row.id_dispositivo + '\',0)"><i class="fa fa-edit"></i> ' + $('#editar').val() + '</a></li><li><a class="dropdown-item" href="javascript:;" onclick="detalleLiderlist(\'' + row.id_liderlist_cabecera + '\')"><i class="fa fa-list-ol"></i> ' + $('#detalle').val() + '</a></li><li class="dropdown-divider"></li>' + linea + '</ul></div>';
                }
            },
        ]
    });
    $('#btnagregarDispositivo').click(function () {
        $('#myModalNota').modal('toggle');
    });
});
function cambiarEstadoDispositivo(id, estado) {
    $.ajax({
        url:$('#controlador').val()+'/cambiarEstadoDispositivo',
        type:'post',
        data:{
            _token:$('#token').val(),
            id:id,
            estado:estado
        },
        dataType:'json',
        success:function(json){
            var mns=json.message.split('|');
            if(json.state==true && mns[0]=='ok')
            {
                mensaje(mns[1],'info');
            }else
            {
                mensaje(mns[1],'error');
            }
        }
    });
}
function verDispositivo(id) {
    $('#myModal').modal('toggle');
}
