var filas=0;
$(document).ready(function () {
    $('#tablaLiderlist').DataTable({
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
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js",
        aoColumns: [
            {
                data: 'id_liderlist_cabecera',
                render: function (data, type, row) {
                    return row.id_liderlist_cabecera;
                }
            },
            {
                data: 'catalogo_liderlist_cabecera',
                render: function (data, type, row) {
                    return row.catalogo_liderlist_cabecera;
                }
            },
            {
                data: 'descripcion_liderlist_cabecera',
                render: function (data, type, row) {
                    return row.descripcion_liderlist_cabecera;
                }
            },
            {
                data: 'created_at_liderlist_cabecera',
                render: function (data, type, row) {
                    return row.created_at_liderlist_cabecera;
                }
            }, {
                data: 'id_usuario_creacion_liderlist_cabecera',
                render: function (data, type, row) {
                    return row.id_usuario_creacion_liderlist_cabecera;
                }
            },{
                data: 'estado_liderlist_cabecera',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_liderlist_cabecera==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_liderlist_cabecera',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoLiderlist('${row.id_liderlist_cabecera}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_liderlist_cabecera==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoLiderlist('${row.id_liderlist_cabecera}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verLiderlist('${row.id_liderlist_cabecera}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verLiderlist('${row.id_liderlist_cabecera}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="detalleLiderlist('${row.id_liderlist_cabecera}')"><i class="fa fa-list-ol"></i> ${$('#detalle').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#tablaDetalleLiderlist').DataTable({
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
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js?id="+$('#id_cabecera_liderlist_detalle_').val(),
        aoColumns: [
            {
                data: 'id_liderlist_detalle',
                render: function (data, type, row) {
                    return row.id_liderlist_detalle;
                }
            },
            {
                data: 'id_liderlist_detalle',
                render: function (data, type, row) {
                    return row.id_liderlist_detalle;
                }
            },
            {
                data: 'catalogo_liderlist_detalle',
                render: function (data, type, row) {
                    return row.catalogo_liderlist_detalle;
                }
            },
            {
                data: 'pagina_liderlist_detalle',
                render: function (data, type, row) {
                    return row.pagina_liderlist_detalle;
                }
            },
            {
                data: 'referencia_liderlist_detalle',
                render: function (data, type, row) {
                    return row.referencia_liderlist_detalle;
                }
            }, {
                data: 'nombre_liderlist_detalle',
                render: function (data, type, row) {
                    return row.nombre_liderlist_detalle;
                }
            }, {
                data: 'nombre_categoria',
                render: function (data, type, row) {
                    return row.nombre_categoria;
                }
            }, {
                data: 'nombre_subcategoria',
                render: function (data, type, row) {
                    return row.nombre_subcategoria;
                }
            }, {
                data: 'color',
                render: function (data, type, row) {
                    return row.color;
                }
            }, {
                data: 'tallas_liderlist_detalle',
                render: function (data, type, row) {
                    return row.tallas_liderlist_detalle;
                }
            },
            {
                data: 'costo_liderlist_detalle',
                render: function (data, type, row) {
                    return row.costo_liderlist_detalle;
                }
            },
            {
                data: 'precio_liderlist_detalle',
                render: function (data, type, row) {
                    return row.precio_liderlist_detalle;
                }
            },
            {
                data: 'estado_liderlist_detalle',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_liderlist_detalle==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_liderlist_detalle',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoLiderlist('${row.id_liderlist_detalle}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_liderlist_detalle==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoLiderlist('${row.id_liderlist_detalle}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verLiderlist('${row.id_liderlist_detalle}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verLiderlist('${row.id_liderlist_detalle}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="detalleLiderlist('${row.id_liderlist_detalle}')"><i class="fa fa-list-ol"></i> ${$('#detalle').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#btnagregarLiderlist').click(function(){
        $("#frm_")[0].reset();
        $('#estado_liderlist_cabecera').val(1);
        $('#myModal').modal('toggle');
    });
    $('#btnagregarDetalleLiderlist').click(function(){
        $("#frm_")[0].reset();
        $('#estado_liderlist_detalle').val(1);
        $('#id_cabecera_liderlist_detalle').val($('#id_cabecera_liderlist_detalle_').val());
        $('#myModal').modal('toggle');
    });
    $('#agregarImagenProducto').click(function(){
        filas++;
        $.ajax({
            url: 'utilidades/crearLineaProducto',
            type: 'post',
            data: {
                _token:$('#token').val(),
                fila:filas
            },
            dataType: 'html',
            success: function (html) {
                $('#clonar').before(html);
            }
        });
    });
    $('.closeCrop').click(function(){
        $('#myModalCrop').modal('toggle');
        $('#myModal').modal('toggle');
    });
    $('#btnguardarLiderlist').click(function(){
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?','question',null,'form');
    });
    $('#btnguardarDatalleLiderlist').click(function(){
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?','question',null,'form');
    });
    $('#id_categoria_liderlist_detalle').change(function(){
        $.ajax({
            url:$('#controlador').val()+'/traerSubcategoria',
            type:'post',
            data:{
                _token:$('#token').val(),
                id:$('#id_categoria_liderlist_detalle').val()
            },
            dataType:'json',
            success:function(json){
                console.log(json);
                var html='';
                html='<option value="">--Seleccionar--</option>';
                $.each(json, function(i, item) {
                    html+='<option value="'+item.id_subcategoria+'">'+item.nombre_subcategoria+'</option>'
                });
                $('#id_subcategoria_liderlist_detalle').html(html).trigger('change');
                var dato=$('#referencia_liderlist_detalle').val().split('|');
                $('#id_subcategoria_liderlist_detalle').val(dato[4]).trigger('change');
                $('#id_color_liderlist_detalle').val(dato[5]).trigger('change');
            }
        });
    });
    $('#referencia_liderlist_detalle').change(function(){
        var dato=$('#referencia_liderlist_detalle').val().split('|');
        $('#id_categoria_liderlist_detalle').val(dato[3]).trigger('change');
        setSubcategoria(dato[4]);
        return;
        $.ajax({
            url: $('#controlador').val()+'/crearLineaProducto',
            type: 'post',
            data: {
                _token:$('#token').val(),
                fila:filas
            },
            dataType: 'html',
            success: function (html) {
                $('#clonar').before(html);
            }
        });
    });
});
function setSubcategoria(id)
{
    $('#id_subcategoria_liderlist_detalle').val(id).trigger('change');
}
function abrirCrop(fila)
{
    $('#myModal').modal('toggle');
    $('#myModalCrop').modal('toggle');
}
function detalleLiderlist(id)
{
    window.open($('#controlador').val()+'/datelleLiderlist?id='+id,'_self');
}