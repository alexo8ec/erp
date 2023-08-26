$(document).ready(function () {
    $('#tablaCatalogo').DataTable({
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
        order:[[1,'asc']],
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
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js?id="+$('#id_subcatalogo_').val(),
        aoColumns: [
            {
                data: 'id_catalogo',
                render: function (data, type, row) {
                    return row.id_catalogo;
                }
            },
            {
                data: 'nombre_catalogo',
                render: function (data, type, row) {
                    return row.nombre_catalogo
                }
            },
            {
                data: 'codigo_catalogo',
                render: function (data, type, row) {
                    return row.codigo_catalogo;
                }
            }, {
                data: 'valor_catalogo',
                render: function (data, type, row) {
                    return row.valor_catalogo;
                }
            }, {
                data: 'orden_catalogo',
                render: function (data, type, row) {
                    return row.orden_catalogo;
                }
            },{
                data: 'estado_catalogo',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_catalogo==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_catalogo',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoCatalogo('${row.id_catalogo}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_catalogo==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoCatalogo('${row.id_catalogo}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verCatalogo('${row.id_catalogo}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verCatalogo('${row.id_catalogo}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="subCatalogos('${row.id_catalogo}','${row.nombre_catalogo}')"><i class="fa fa-plus"></i> ${$('#subcatalogo').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#tablaUsuarios').DataTable({
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
        order:[[1,'asc']],
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
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js?id="+$('#id_subcatalogo_').val(),
        aoColumns: [
            {
                data: 'id_usuario',
                render: function (data, type, row) {
                    return row.id_usuario;
                }
            },
            {
                data: 'usuario',
                render: function (data, type, row) {
                    return row.usuario
                }
            },
            {
                data: 'nombre_usuario',
                render: function (data, type, row) {
                    return row.nombre_usuario+' '+row.apellido_usuario;
                }
            }, {
                data: 'usuario_creacion',
                render: function (data, type, row) {
                    return row.usuario_creacion;
                }
            }, {
                data: 'created_at_usuario',
                render: function (data, type, row) {
                    return getFecha(row.created_at_usuario,'hora','-');
                }
            }, {
                data: 'email_usuario',
                render: function (data, type, row) {
                    return row.email_usuario;
                }
            }, {
                data: 'telefono_usuario',
                render: function (data, type, row) {
                    return row.telefono_usuario;
                }
            }, {
                data: 'celular_usuario',
                render: function (data, type, row) {
                    return row.celular_usuario;
                }
            }, {
                data: 'direccion_usuario',
                render: function (data, type, row) {
                    return row.direccion_usuario;
                }
            },{
                data: 'estado_usuario',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_usuario==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_usuario',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoUsuario('${row.id_usuario}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_usuario==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoUsuario('${row.id_usuario}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verCatalogo('${row.id_usuario}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verCatalogo('${row.id_usuario}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="asignarEmpresas('${row.id_usuario}')"><i class="fa-brands fa-slideshare"></i> ${$('#definirEmpresas').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#btnagregarCatalogo').click(function(){
        $("#frm_")[0].reset();
        $('#estado_catalogo').val(1);
        $('#myModal').modal('toggle');
    });
    $('#btnagregarSubCatalogo').click(function(){
        $("#frm_")[0].reset();
        $('#estado_catalogo').val(1);
        $('#id_catalogo_pertenece').val($('#id_catalogo_pertenece_').val());
        $('#myModal').modal('toggle');
    });
    $('#btnguardarCatalogo').click(function(){
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?','question',null,'frm_','modal');
    });
    $('#btnguardarempresasUsuario').click(function(){
        $.ajax({
            url:$('#controlador').val()+'/asignarEmpresas',
            type:'post',
            data:{
                _token:$('#token').val(),
                empresas:$('#id_empresas_usuario').val()
            },
            dataType:'html',
            success:function(html){
                $('#myModalEmpresas').modal('toggle');
                if(html=='ok')
                    mensaje('Empresas asignadas correctamente','info');
            }
        });
    });
});
function subCatalogos(id,n)
{
    window.open($('#controlador').val()+'/subcatalogo?id='+id+'&n='+n,'_self');
}
function asignarEmpresas(id)
{
    $('#myModalEmpresas').modal('toggle');
}