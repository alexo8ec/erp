$(document).ready(function () {
    var btnRuc = $( '.ladda-button-demo-ruc' ).ladda();
    var btnCias = $( '.ladda-button-demo-cias' ).ladda();
    var btnEstatutos = $( '.ladda-button-demo-estat' ).ladda();
    var btnActa = $( '.ladda-button-demo-acta' ).ladda();
    var btnCed = $( '.ladda-button-demo-ced' ).ladda();
    var btnCer = $( '.ladda-button-demo-cer' ).ladda();
    var btnFir = $( '.ladda-button-demo-fir' ).ladda();
    var btnLogo = $( '.ladda-button-demo-logo' ).ladda();
    btnRuc.click(function(){
        btnRuc.ladda( 'start' );
    });
    btnCias.click(function(){
        btnCias.ladda( 'start' );
    });
    btnEstatutos.click(function(){
        btnEstatutos.ladda( 'start' );
    });
    btnActa.click(function(){
        btnActa.ladda( 'start' );
    });
    btnCed.click(function(){
        btnCed.ladda( 'start' );
    });
    btnCer.click(function(){
        btnCer.ladda( 'start' );
    });
    btnFir.click(function(){
        btnFir.ladda( 'start' );
    });
    $('#tablaEmpresas').DataTable({
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
                data: 'id_empresa',
                render: function (data, type, row) {
                    return row.id_empresa;
                }
            },
            {
                data: 'razon_social_empresa',
                render: function (data, type, row) {
                    return row.razon_social_empresa;
                }
            },
            {
                data: 'ruc_empresa',
                render: function (data, type, row) {
                    return row.ruc_empresa;
                }
            },
            {
                data: 'fecha_inicio_empresa',
                render: function (data, type, row) {
                    return row.fecha_inicio_empresa;
                }
            }, {
                data: 'email_empresa',
                render: function (data, type, row) {
                    return row.email_empresa;
                }
            }, {
                data: 'telefono_empresa',
                render: function (data, type, row) {
                    return row.telefono_empresa;
                }
            }, {
                data: 'celular_empresa',
                render: function (data, type, row) {
                    return row.celular_empresa;
                }
            }, {
                data: 'direccion_matriz_empresa',
                render: function (data, type, row) {
                    return row.direccion_matriz_empresa;
                }
            }, {
                data: 'estado_empresa',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_empresa==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_empresa',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa('${row.id_empresa}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_empresa==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEmpresa('${row.id_empresa}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa('${row.id_empresa}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEmpresa('${row.id_empresa}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
        ]
    });
    $('#tablaEstablecimientos').DataTable({
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
        responsive: true,
        sAjaxSource: $('#controlador').val() + "/" + $('#submodulo').val() + "js",
        aoColumns: [
            {
                data: 'id_establecimiento',
                render: function (data, type, row) {
                    return row.id_establecimiento;
                }
            },
            {
                data: 'establecimiento',
                render: function (data, type, row) {
                    return row.establecimiento;
                }
            },
            {
                data: 'emision_establecimiento',
                render: function (data, type, row) {
                    return row.emision_establecimiento;
                }
            },{
                data: 'nombre_establecimiento',
                render: function (data, type, row) {
                    return row.nombre_establecimiento;
                }
            },{
                data: 'tipo_establecimiento',
                render: function (data, type, row) {
                    return row.tipo_establecimiento;
                }
            }, {
                data: 'created_at_establecimiento',
                render: function (data, type, row) {
                    return getFechaMysql(row.created_at_establecimiento, '');
                }
            }, {
                data: 'email_establecimiento',
                render: function (data, type, row) {
                    return row.email_establecimiento;
                }
            }, {
                data: 'telefono_establecimiento',
                render: function (data, type, row) {
                    return row.telefono_establecimiento;
                }
            }, {
                data: 'celular_establecimiento',
                render: function (data, type, row) {
                    return row.celular_establecimiento;
                }
            }, {
                data: 'direccion_establecimiento',
                render: function (data, type, row) {
                    return row.direccion_establecimiento;
                }
            },  {
                data: 'estado_establecimiento',
                className:'text-center',
                render: function (data, type, row) {
                    var estado='<span class="label label-danger">Inactivo</span>';
                    if(row.estado_establecimiento==1)
                        estado='<span class="label label-primary">Activo</span>';
                    return estado;
                }
            }, {
                data: 'id_establecimiento',
                className: 'text-center',
                render: function (data, type, row) {
                    var linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEstablecimiento('${row.id_establecimiento}',1)"><i class="fa fa-check-circle" style="color:green;"></i> ${$('#activar').val()}</a></li>`;
                    if(row.estado_establecimiento==1)
                        linea=`<li><a class="dropdown-item" href="javascript:;" onclick="cambiarEstadoEstablecimiento('${row.id_establecimiento}',0)"><i class="fa fa-window-close" style="color:red"></i> ${$('#desactivar').val()}</a></li>`;
                    return `<div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                <i class="fa-brands fa-joomla"></i> 
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEstablecimiento('${row.id_establecimiento}',1)"><i class="fa fa-eye"></i> ${$('#ver').val()}</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="verEstablecimiento('${row.id_establecimiento}',0)"><i class="fa fa-edit"></i> ${$('#editar').val()}</a></li>
                                <li class="dropdown-divider"></li>
                                ${linea}
                            </ul>
                        </div>`;
                }
            },
         ]
    });    
    $('#btnguardarEmpresa').click(function(){
        if($('#fecha_inicio_empresa').val()=='')
        { 
            $('#myModal').modal('toggle');
            mensaje('Ingrese la facha de inicio de la empresa','warning');
        }
        else{
            $('#myModal').modal('toggle');
            mensaje('Esta segur@ de guardar los datos?','question',null,'frm_');
        }
    });
    $('#btnguardarEstablecimiento').click(function(){
        $('#myModal').modal('toggle');
        mensaje('Esta segur@ de guardar los datos?','question',null,'frm_');
    });    
    $('#entidadRuc').change(function () {
        var inputFileImage = document.getElementById('entidadRuc');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadRuc')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnRuc.ladda('stop');
            $('#entidadRuc').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadRuc', file);
            fd.append('tipo_archivo', 'entidadRuc');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        btnRuc.ladda('stop');
                        $('#entidadRuc').val('');
                        $('#lblruc').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadRuc').val('');
                    }
                }
            });
        }
    });
    $('#entidadRegistroCias').change(function () {
        var inputFileImage = document.getElementById('entidadRegistroCias');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadRegistroCias')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnCias.ladda('stop');
            $('#entidadRegistroCias').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadRegistroCias', file);
            fd.append('tipo_archivo', 'entidadRegistroCias');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        $('#entidadRegistroCias').val('');
                        btnCias.ladda('stop');
                        $('#lblrcias').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadRegistroCias').val('');
                    }
                }
            });
        }
    });
    $('#entidadEstatutos').change(function () {
        var inputFileImage = document.getElementById('entidadEstatutos');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadEstatutos')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnEstatutos.ladda('stop');
            $('#entidadEstatutos').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadEstatutos', file);
            fd.append('tipo_archivo', 'entidadEstatutos');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        $('#entidadEstatutos').val('');
                        btnEstatutos.ladda('stop');
                        $('#lblrestatutos').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadEstatutos').val('');
                    }
                }
            });
        }
    });
    $('#entidadActa').change(function () {
        var inputFileImage = document.getElementById('entidadActa');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadActa')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnActa.ladda('stop');
            $('#entidadActa').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadActa', file);
            fd.append('tipo_archivo', 'entidadActa');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        $('#entidadActa').val('');
                        btnActa.ladda('stop');
                        $('#lblacta').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadActa').val('');
                    }
                }
            });
        }
    });
    $('#entidadCedula').change(function () {
        var inputFileImage = document.getElementById('entidadCedula');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadCedula')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnCed.ladda('stop');
            $('#entidadCedula').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadCedula', file);
            fd.append('tipo_archivo', 'entidadCedula');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        $('#entidadCedula').val('');
                        btnCed.ladda('stop');
                        $('#lblcedula').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadCedula').val('');
                    }
                }
            });
        }
    });
    $('#entidadVotacion').change(function () {
        var inputFileImage = document.getElementById('entidadVotacion');
        var file = inputFileImage.files[0];
        var fileSize = $('#entidadVotacion')[0].files[0].size;
        var siezekiloByte = parseInt(fileSize / 1024);
        if (siezekiloByte > 1072) {
            btnCer.ladda('stop');
            $('#entidadVotacion').val('');
            alert('Error: imagen muy grande o tiene una resolucion muy alta');
        } else {
            var fd = new FormData();
            fd.append('entidadVotacion', file);
            fd.append('tipo_archivo', 'entidadVotacion');
            fd.append('id_empresa', $('#id_empresa').val());
            fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                        console.log(imagen[1]);
                        $('#entidadVotacion').val('');
                        btnCer.ladda('stop');
                        $('#lblvotacion').html(imagen[1]);
                    } else {
                        alert(imagen[1]);
                        $('#entidadVotacion').val('');
                    }
                }
            });
        }
    });
    $('#btnagregarEstablecimiento').click(function(){
        $("#frm_")[0].reset();
        $('#pais').val('').trigger('change');
        $('#provincia').val('').trigger('change');
        $('#ciudad').val('').trigger('change');
        $('#estado_establecimiento').val(1);
        idPais='';
        idProvincia='';
        idCiudad='';
        $('#myModal').modal('toggle');
    });
    $('#btnagregarEmpresa').click(function(){
        $("#frm_")[0].reset();
        $('#pais').val('').trigger('change');
        $('#provincia').val('').trigger('change');
        $('#ciudad').val('').trigger('change');
        idPais='';
        idProvincia='';
        idCiudad='';
        $('#checontabilidad').attr('checked',false);
        $('#cheretencion').attr('checked',false);
        $('#estado_empresa').val(1);
        $('#contabilidad_empresa').val(0);
        $('#agente_retencion_empresa').val(0);
        $('#myModal').modal('toggle');
    });
});
function verEstablecimiento(id,block){
    $.ajax({
        url:$('#controlador').val()+'/verEstablecimiento',
        type:'post',
        data:{
            _token:$('#token').val(),
            id:id
        },
        dataType:'json',
        success:function(json){
            $('#id_establecimiento').val(json.id_establecimiento);
            $('#id_empresa_establecimiento').val(json.id_empresa_establecimiento);
            $('#nombre_establecimiento').val(json.nombre_establecimiento);
            $('#tipo_establecimiento').val(json.tipo_establecimiento);
            $('#establecimiento').val(json.establecimiento);
            $('#emision_establecimiento').val(json.emision_establecimiento);
            $('#num_inicial_establecimiento').val(json.num_inicial_establecimiento);
            idPais=json.id_pais_ciudad;
            $('#pais').val(idPais).trigger('change');
            idProvincia=json.id_provincia_ciudad;
            idCiudad=json.id_ciudad_establecimiento;
            $('#direccion_establecimiento').val(json.direccion_establecimiento);
            $('#telefono_establecimiento').val(json.telefono_establecimiento);
            $('#celular_establecimiento').val(json.celular_establecimiento);
            $('#email_establecimiento').val(json.email_establecimiento);
            $('#leyenda_establecimiento').val(json.leyenda_establecimiento);
            $('#formatofact_establecimiento').val(json.formatofact_establecimiento);
            $('#formatocoti_establecimiento').val(json.formatocoti_establecimiento);
            $('#formatonota_establecimiento').val(json.formatonota_establecimiento);
             if(json.estado_establecimiento==1)
            {
                $('#cheestado').attr('checked',true);
                $('#estado_establecimiento').val(1);
            }
            else
            {
                $('#cheestado').attr('checked',false);
                $('#estado_establecimiento').val(0);
            }
            if(block==1)
                $('#btnguardarEstablecimiento').attr('disabled',true);
            else
                $('#btnguardarEstablecimiento').attr('disabled',false);
            $('#myModal').modal('toggle');
        }
    });
}
function validarFirma() {
    $.ajax({
        url: 'utilidades/validarFirma',
        type: 'post',
        data: 'tipo_archivo=entidadFirma' + '&clave=' + $('#clave_token_empresa').val() + '&_token=' + $('#token').val(),
        datatype: 'json',
        success: function (json) {
            $('#myModal').modal('toggle');
            mensaje(json,'info');
        }
    });
}
function subirFirma() {
    var inputFileImage = document.getElementById('entidadFirma');
    var file = inputFileImage.files[0];
    var fileSize = $('#entidadFirma')[0].files[0].size;
    var siezekiloByte = parseInt(fileSize / 1024);
    if (siezekiloByte > 1072) {
        btnFir.ladda('stop');
        $('#entidadFirma').val('');
        alert('Error: imagen muy grande o tiene una resolucion muy alta');
    } else {
        var fd = new FormData();
        fd.append('entidadFirma', file);
        fd.append('tipo_archivo', 'entidadFirma');
        fd.append('id_empresa', $('#id_empresa').val());
        fd.append('id_usuario', $('#id_usuario_creacion_empresa').val());
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
                    console.log(imagen[1]);
                    btnFir.ladda('stop');
                    $('#btnfirma').html(imagen[1]);
                    $('#entidadFirma').val('');
                } else {
                    alert(imagen[1]);
                    $('#entidadFirma').val('');
                }
            }
        });
    }
}
function verEmpresa(id,block){
    $.ajax({
        url:$('#controlador').val()+'/verEmpresa',
        type:'post',
        data:{
            _token:$('#token').val(),
            id:id
        },
        dataType:'json',
        success:function(json){
            $('#id_empresa').val(json.id_empresa);
            $('#id_usuario_creacion_empresa').val(json.id_usuario_creacion_empresa);
           
            $('#razon_social_empresa').val(json.razon_social_empresa);
            $('#ruc_empresa').val(json.ruc_empresa);
            $('#nombre_comercial_empresa').val(json.nombre_comercial_empresa);
            $('#email_empresa').val(json.email_empresa);
            $('#telefono_empresa').val(json.telefono_empresa);
            $('#celular_empresa').val(json.celular_empresa);
            $('#nombre_corto_empresa').val(json.nombre_corto_empresa);

            if(json.estado_empresa==1)
            {
                $('#cheestado').attr('checked',true);
                $('#estado_empresa').val(1);
            }
            else
            {
                $('#cheestado').attr('checked',false);
                $('#estado_empresa').val(0);
            }

            $('#direccion_matriz_empresa').val(json.direccion_matriz_empresa);
            idPais=json.id_pais;
            $('#pais').val(idPais).trigger('change');
            idProvincia=json.id_provincia;
            idCiudad=json.id_ciudad_empresa;
            $('#urbanizacion_empresa').val(json.urbanizacion_empresa);
            $('#etapa_empresa').val(json.etapa_empresa);
            $('#mz_empresa').val(json.mz_empresa);
            $('#villa_empresa').val(json.villa_empresa);
            $('#referencia_direccion_empresa').val(json.referencia_direccion_empresa);
            $('#fecha_inicio_empresa').val(json.fecha_inicio_empresa);
            $('#representante_empresa').val(json.representante_empresa);
            $('#identificacion_representante_empresa').val(json.identificacion_representante_empresa);
            $('#contador_empresa').val(json.contador_empresa);
            $('#identificacion_contador_empresa').val(json.identificacion_contador_empresa);
            $('#num_resolucion_empresa').val(json.num_resolucion_empresa);
            $('#num_contribuyente_especial_empresa').val(json.num_contribuyente_especial_empresa);
            $('#id_emision_empresa').val(json.id_ambiente_empresa).trigger('change');
            $('#id_ambiente_empresa').val(json.id_ambiente_empresa).trigger('change');
            $('#actividad_empresa').val(json.actividad_empresa);
            if(json.contabilidad_empresa==1)
            {
                $('#checontabilidad').attr('checked',true);
                $('#contabilidad_empresa').val(1);
            }
            else
            {
                $('#checontabilidad').attr('checked',false);
                $('#contabilidad_empresa').val(1);
            }
            if(json.agente_retencion_empresa==1)
            {
                $('#cheretencion').attr('checked',true);
                $('#agente_retencion_empresa').val(1);
            }
            else
            {
                $('#cheretencion').attr('checked',false);
                $('#agente_retencion_empresa').val(1);
            }

            $('#id_tipo_regimen_empresa').val(json.id_tipo_regimen_empresa).trigger('change');
            $('#id_moneda_empresa').val(json.id_moneda_empresa).trigger('change');

            if (json.id_archivoRuc !== null) 
                $('#lblruc').html('RUC: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadRuc" target="new">Ver</a>');
             else 
                $('#lblruc').html('RUC:');
            if (json.id_archivoRcia !== null) 
                $('#lblrcias').html('Registro CIAS: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadRegistroCias" target="new">Ver</a>');
             else 
                $('#lblrcias').html('Registro CIAS:');
            if (json.id_archivoEstatuto !== null) 
                $('#lblrestatutos').html('Estatutos: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadEstatutos" target="new">Ver</a>');
             else 
                $('#lblrestatutos').html('Estatutos:');
            if (json.id_archivoCedula !== null) 
                $('#lblcedula').html('Cedula Representante: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadCedula" target="new">Ver</a>');
             else 
                $('#lblcedula').html('Cedula Representante:');
            if (json.id_archivoActa !== null) 
                $('#lblacta').html('Acta de accionistas: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadActa" target="new">Ver</a>');
             else 
                $('#lblacta').html('Acta de accionistas:');
            if (json.id_archivoVotacion !== null) 
                $('#lblvotacion').html('Certificado de Votación: <a href="utilidades/viewPdf?id=' + json.id_empresa + '&t=entidadVotacion" target="new">Ver</a>');
             else 
                $('#lblvotacion').html('Certificado de Votación:');
            if (json.id_archivoFirma !== null) 
                $('#btnfirma').html('<label id="lblvotacion" class="font-normal">Firma dígital: </label><div><label title="Archivo .p12 de la compañia" for="entidadFirma" class="ladda-button ladda-button-demo-fir btn btn-primary"  data-style="zoom-in"><input type="file" name="entidadFirma" onchange="subirFirma();" id="entidadFirma" style="display:none"><i class="fa fa-thumbs-up"></i> Firma encontrada</label></div>');
                //$('#btnfirma').html('<label title="Subir firma digital" for="entidadFirma" class="btn btn-primary mb-1" style="cursor: pointer;"><input type="file" id="entidadFirma" onchange="subirFirma();" name="entidadFirma" style="display:none;"><i class="fa fa-thumbs-up"></i>&nbsp;Firma encontrada</label>');
                try {
                    $.ajax({
                        url: 'utilidades/getArchivo',
                        type: 'post',
                        data: 'tipo_archivo=entidadLogo&id_usuario=&id_empresa=' + json.id_empresa + '&archivo=false&clase=class="img-fluid"&w=200' + '&_token=' + $('#token').val(),
                        datatype: 'html',
                        success: function (html) {
                            if (html != '') 
                                $('#logoEntidad').html(html);
                             else 
                                $('#logoEntidad').html('<div id="logoEntidad" style="text-align: center;width: 200px;height: 250px;margin:0px auto;display:flex; justify-content: center; align-items: center;"><img src="public/images/sinfoto.png" class="img-fluid"></div>');
                        }
                    });
                } catch (error) {
                    mensaje(error, 'error', '', '');
                }
            if(block==1)
                $('#btnguardarEmpresa').attr('disabled',true);
            else
                $('#btnguardarEmpresa').attr('disabled',false);
            $('#myModal').modal('toggle');
        }
    });
    
}
function cambiarEstadoEmpresa(id,estado){
    $.ajax({
        url:$('#controlador').val()+'/cambiarEstadoEmpresa',
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
function cambiarEstadoEstablecimiento(id,estado){ 
    $.ajax({
        url:$('#controlador').val()+'/cambiarEstadoEstablecimiento',
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
                mensaje(mns[1],'info',null,null,null);
            }else
            {
                mensaje(mns[1],'error');
            }
        }
    });
}