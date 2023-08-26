var filas=0;
var id_plan_cuenta=0;
var idSubcategoria=0;
$(document).ready(function () {
    var btnLogo = $( '.ladda-button-demo-logo' ).ladda();
    btnLogo.click(function(){
        btnLogo.ladda( 'start' );
    });
    $('#tablaProductos').DataTable({
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
                data: 'id_producto',
                render: function (data, type, row) {
                    return row.id_producto;
                }
            },
            {
                className: 'text-center',
                data: 'codigo_producto',
                render: function (data, type, row) {
                    return row.codigo_producto
                }
            },
            {
                data: 'descripcion_producto',
                render: function (data, type, row) {
                    var modelo = row.modelo_producto !== null ? row.modelo_producto : '';
                    return row.descripcion_producto + ' ' + modelo;
                }
            },
            {
                className: 'text-center',
                data: 'stock',
                render: function (data, type, row) {
                    return '<a href="javascript:" onclick="verMovimientos(\''+row.id_producto+'\');">'+row.stock+'</a>';
                }
            }, {
                className: 'text-center',
                data: 'presentacion_producto',
                render: function (data, type, row) {
                    return row.presentacion_producto;
                }
            }, {
                data: 'categoria_producto',
                render: function (data, type, row) {
                    return row.categoria_producto;
                }
            }, {
                data: 'subcategoria_producto',
                render: function (data, type, row) {
                    return row.subcategoria_producto;
                }
            }, {
                className: 'text-center',
                data: 'iva_producto',
                render: function (data, type, row) {
                    var iva = row.iva_producto == 'NO DEFINIDO' ? 'NO' : row.iva_producto;
                    return iva;
                }
            },{
                className: 'text-center',
                data: 'ice_producto',
                render: function (data, type, row) {
                    var ice = row.ice_producto == 'NO DEFINIDO' ? 'NO' : row.ice_producto;
                    return ice;
                }
            }, {
                className: 'text-right',
                data: 'costo_producto',
                render: function (data, type, row) {
                    return '$ '+parseFloat(row.costo_producto).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'valor1_producto',
                render: function (data, type, row) {
                    return '$ '+parseFloat(row.valor1_producto).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'valor2_producto',
                render: function (data, type, row) {
                    return '$ '+parseFloat(row.valor2_producto).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'valor3_producto',
                render: function (data, type, row) {
                    return '$ '+parseFloat(row.valor3_producto).toFixed(2);
                }
            }, {
                className: 'text-right',
                data: 'valor4_producto',
                render: function (data, type, row) {
                    return '$ '+parseFloat(row.valor4_producto).toFixed(2);
                }
            },{
                className: 'text-center',
                data: 'id_producto',
                render: function (data, type, row) {
                    return 'ref';
                }
            },{
                data: 'estado_producto',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_producto==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_producto',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoProducto('${row.id_producto}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_producto==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoProducto('${row.id_producto}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verProducto('${row.id_producto}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verProducto('${row.id_producto}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#tablaMovimientoProducto').DataTable({
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
    });
    $('#tablaBodegas').DataTable({
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
                data: 'id_bodega',
                render: function (data, type, row) {
                    return row.id_bodega;
                }
            },
            {
                className: 'text-center',
                data: 'establecimiento_bodega',
                render: function (data, type, row) {
                    return row.establecimiento_bodega;
                }
            },{
                data: 'nombre_bodega',
                render: function (data, type, row) {
                    return row.nombre_bodega;
                }
            },{
                className: 'text-center',
                data: 'created_at_bodega',
                render: function (data, type, row) {
                    return getFecha(row.created_at_bodega,'horas');
                }
            }, {
                className: 'text-center',
                data: 'usuario_bodega',
                render: function (data, type, row) {
                    return row.usuario_bodega;
                }
            },{
                data: 'estado_bodega',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_bodega==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_bodega',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoProducto('${row.id_bodega}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_bodega==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoProducto('${row.id_bodega}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verProducto('${row.id_bodega}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verProducto('${row.id_bodega}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#btnagregarProducto').click(function () {
        $("#frm_")[0].reset();
        $('#pais').val('').trigger('change');
        $('#provincia').val('').trigger('change');
        $('#ciudad').val('').trigger('change');
        $('#estado_producto').val(1);
        $('#min_stock_producto').val(0);
        $('#costo_producto').val(parseFloat(0).toFixed(2));
        $('#valor1_producto').val(parseFloat(0).toFixed(2));
        $('#valor2_producto').val(parseFloat(0).toFixed(2));
        $('#valor3_producto').val(parseFloat(0).toFixed(2));
        $('#valor4_producto').val(parseFloat(0).toFixed(2));
        $('#id_presentacion_producto').val(43).trigger('change');
        idPais = '';
        idProvincia = '';
        idCiudad = '';
        $('#myModal').modal('toggle');
    });
    $('#codigo_producto').blur(function(){
        if($('#codigo_producto').val!='')
        {
            if(!$('#codigo_producto').attr("readonly"))
            {
                $.ajax({
                    url:$('#controlador').val()+'/validarCodigo',
                    type:'post',
                    data:{
                        _token: $('#token').val(),
                        codigo: $('#codigo_producto').val()
                    },
                    dataType:'json',
                    success:function(json){

                        if(json.state==true)
                        {
                            $('#myModal').modal('toggle');
                            var mns = json.message.split('|');
                            mensaje(mns[1],'info');
                        }

                    }
                });
            }
            else
            {
            }
        }
    });
    $('#readCodigo').click(function(){
        if($('#codigo_producto').attr("readonly"))
        {
            $('#codigo_producto').attr("readonly",false);
        }
        else
        {
            $('#codigo_producto').attr("readonly",true);
        }
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
    $('#id_categoria_producto').change(function(){
        $.ajax({
            url:$('#controlador').val()+'/traerSubcategoria',
            type:'post',
            data:{
                _token:$('#token').val(),
                id:$('#id_categoria_producto').val()
            },
            dataType:'json',
            success:function(json){
                var html='';
                html='<option value="">--Seleccionar--</option>';
                var select='';
                $.each(json, function(i, item) {
                    select='';
                    if(idSubcategoria==item.id_subcategoria)
                    select='selected';
                    html+='<option value="'+item.id_subcategoria+'" '+select+'>'+item.nombre_subcategoria+'</option>'
                });
                $('#id_subcategoria_producto').html(html).trigger('change');
            }
        });
    });
    $('#tipo_contable_producto').change(function(){
        $.ajax({
            url:$('#controlador').val()+'/traerPlanCuenta',
            type:'post',
            data:{
                _token:$('#token').val(),
                tipo:$('#tipo_contable_producto').val()
            },
            dataType:'json',
            success:function(json){
                var html='';
                html='<option value="">--Seleccionar--</option>';
                var select='';
                $.each(json, function(i, item) {
                    select='';
                    if(id_plan_cuenta==item.id_plan)
                    select='selected';
                    html+='<option value="'+item.id_plan+'" '+select+'>'+item.codigo_contable_plan+' | '+item.nombre_cuenta_plan+'</option>'
                });
                $('#id_plan_cuenta_producto').html(html).trigger('change');
            }
        });
    });
    $('#btnguardarProducto').click(function(){
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?','question',null,'frm_');
    });
    $('#btnguardarBuscarMovimiento').click(function(){
        if($('#desde').val()=='' || $('#hasta').val()=='')
        {
            $('#myModalMovimientos').modal('toggle');
            mensaje('Debe ingresar el rango de fecha para continuar..','info',null,null,'myModalMovimientos');
        }
        else{
            window.open($('#controlador').val()+'/moveProducto?d='+$('#desde').val()+'&h='+$('#hasta').val()+'&id='+$('#id_producto_seleccionado').val(),'_blank');
        }
    });
    $('#btnagregarBodegas').click(function(){
        $('#myModalBodega').modal('toggle');
    });
    $('#btnguardarBodega').click(function(){
        $('#myModalBodega').modal('toggle');
        if($('#nombre_bodega').val()=='')
            mensaje('Debe ingresar el nombre de la bodega...','warning',null,null,'myModalBodega');
        else if($('#id_establecimiento_bodega').val()=='')
            mensaje('Seleccione el establecimiento de la bodega...','warning',null,null,'myModalBodega');
        else{
            mensaje('Esta segur@ de guardar los datos?','question',null,'frm_');
        }
    });    
});
function verProducto(id,block){
    $('#codigo_producto').attr("readonly",true);
    $.ajax({
        url:$('#controlador').val()+'/verProducto',
        type:'post',
        data:{
            _token:$('#token').val(),
            id:id
        },
        dataType:'json',
        success:function(json){
            $('#id_producto').val(json.id_producto);
            $('#id_usuario_creacion_producto').val(json.id_usuario_creacion_producto);           
            $('#descripcion_producto').val(json.descripcion_producto);
            $('#codigo_producto').val(json.codigo_producto);
            $('#tipo_contable_producto').val(json.tipo_contable_producto).trigger('change');
            $('#id_tipo_producto').val(json.id_tipo_producto).trigger('change');
            $('#id_categoria_producto').val(json.id_categoria_producto).trigger('change');
            $('#id_marca_producto').val(json.id_marca_producto).trigger('change');
            $('#id_presentacion_producto').val(json.id_presentacion_producto).trigger('change');
            $('#id_ice_producto').val(json.id_ice_producto).trigger('change');
            $('#id_iva_producto').val(json.id_iva_producto).trigger('change');
            $('#id_irbpnr_producto').val(json.id_irbpnr_producto).trigger('change');
            $('#id_deducible_producto').val(json.id_deducible_producto).trigger('change');
            $('#min_stock_producto').val(json.min_stock_producto);
            $('#costo_producto').val(json.costo_producto);
            $('#valor1_producto').val(json.valor1_producto);
            $('#valor2_producto').val(json.valor2_producto);
            $('#valor3_producto').val(json.valor3_producto);
            $('#valor4_producto').val(json.valor4_producto);
            $('#codigo_externo_producto').val(json.codigo_externo_producto);
            $('#qr_producto').val(json.qr_producto);
            $('#modelo_producto').val(json.modelo_producto);
            $('#id_marca_producto').val(json.id_marca_producto).trigger('change');
            $('#id_color_producto').val(json.id_color_producto).trigger('change');
            $('#id_talla_producto').val(json.id_talla_producto).trigger('change');
            if(json.estado_producto==1)
            {
                $('#cheestado').attr('checked',true);
                $('#estado_producto').val(1);
            }
            else
            {
                $('#cheestado').attr('checked',false);
                $('#estado_producto').val(0);
            }
            $.ajax({
                url: 'contabilidad/getidPlan',
                type: 'post',
                data:{
                    _token:$('#token').val(),
                    cod_plan:json.cod_plan_producto
                },
                dataType:'json',
                success: function (plan) {
                    id_plan_cuenta=plan.id_plan;
                    $('#id_plan_cuenta_producto').val(plan.id_plan).trigger('change');
                }
            });
            idSubcategoria=json.id_subcategoria_producto;
            $('#id_subcategoria_producto').val(json.id_subcategoria_producto).trigger('change');
            try{
                $.ajax({
                    url: 'utilidades/getArchivo',
                    type: 'post',
                    data:{
                        _token:$('#token').val(),
                        tipo_archivo:'productoImagen',
                        id_usuario:$('#id_usuario_').val(),
                        id_producto:$('#id_producto').val()
                    },
                    success: function (data) {
                        $('#clonar').html(data);                     
                    }
                });
            }catch(error){}
            if(block==1)
                $('#btnguardarProducto').attr('disabled',true);
            else
                $('#btnguardarProducto').attr('disabled',false);
            $('#myModal').modal('toggle');
        }
    });    
}
function abrirCrop(fila){
    $('#myModal').modal('toggle');
    $('#myModalCrop').modal('toggle');
}
function cerrarDownload(){
    $('#getCroppedCanvasModal').modal('toggle');
}
function subirImagen(fila) {
    var inputFileImage = document.getElementById('imagen'+fila);
    var file = inputFileImage.files[0];
    var fileSize = $('#imagen'+fila)[0].files[0].size;
    var siezekiloByte = parseInt(fileSize / 1024);
    if (siezekiloByte > 1072) {
        $('#foto'+fila).val('');
        alert('Error: imagen muy grande o tiene una resolucion muy alta');
    } else {
        var fd = new FormData();
        fd.append('imagen'+fila, file);
        fd.append('tipo_archivo', 'productoImagen');
        fd.append('id_empresa', $('#id_empresa_').val());
        fd.append('id_usuario',$('#id_usuario_').val());
        fd.append('id_producto_archivo',$('#id_producto').val());
        fd.append('fila',fila)
        fd.append('_token', $('#token').val());
        $.ajax({
            url: 'utilidades/guardarArchivo',
            data: fd,
            processData: false,
            contentType: false,
            type: 'post',
            success: function (data) {
                console.log(data);
                var imagen = data.split('|');
                if (imagen[0] == 'ok') {
                    $('#foto'+fila).html(imagen[1]);
                    $('#imagen'+fila).val('');
                } else {
                    alert(imagen[1]);
                    $('#imagen'+fila).val('');
                }
            }
        });
    }
}
function verMovimientos(id)
{
    $('#id_producto_seleccionado').val('');
    $('#id_producto_seleccionado').val(id);
    $('#myModalMovimientos').modal('toggle');
}