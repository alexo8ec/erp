var idPais = 0;
var idProvincia = 0;
var idCiudad = 0;
$(document).ready(function () {
    $('.i-checks').iCheck({checkboxClass: 'icheckbox_square-green', radioClass: 'iradio_square-green'});
    try {
        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-default btn-sm';
    } catch (error) {}
    let toast = $('.toast');
    $("#buscar").focus();
    if ($('#id_empresa_').val() == '') {
        setTimeout(function () {
            toast.toast({delay: 9000, animation: true});
            toast.toast('show');

        }, 2200);
    }
    $('#cmbestablecimiento').change(function () {
        $.ajax({
            url: 'admin/session_establecimiento',
            type: 'post',
            data: {
                _token: $('#token').val(),
                id: $('#cmbestablecimiento').val()
            },
            type: 'post',
            success: function () {
                window.open('admin', '_self');
            }
        });
    });
    $('.input-number').on('input', function () {
        this.value = this.value.replace(/[^0-9-.]/g, '');
    });
    $(".select2_demo_1").select2({theme: 'bootstrap4'});
    $(".select2_demo_2").select2({theme: 'bootstrap4'});
    $(".select2_demo_3").select2({theme: 'bootstrap4', placeholder: "--Seleccionar--", allowClear: true});
    var mem = $('#data_1 .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('#pais').change(function () {
        $.ajax({
            url: 'utilidades/provincias',
            type: 'post',
            data: {
                _token: $('#token').val(),
                id: $('#pais').val()
            },
            dataType: 'json',
            success: function (json) {
                var html = '<option value="">--Seleccione una provincia--</option>';
                $.each(json, function (i, item) {
                    html += '<option value="' + item.id_provincia + '">' + capitalizar(item.nombre_provincia) + '</option>';
                });
                $('#provincia').html(html).trigger('change');
                if (idProvincia == 0) {
                    $('#provincia').val('').trigger('change');
                } else {
                    $('#provincia').val(idProvincia).trigger('change');
                }
            }
        });
    });
    $('#provincia').change(function () {
        if ($('#provincia').val() != '') {
            $.ajax({
                url: 'utilidades/ciudades',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    id: $('#provincia').val()
                },
                dataType: 'json',
                success: function (json) {
                    var html = '<option value="">--Seleccione una ciudad--</option>';
                    $.each(json, function (i, item) {
                        html += '<option value="' + item.id_ciudad + '">' + capitalizar(item.nombre_ciudad) + '</option>';
                    });
                    $('#id_ciudad').html(html).trigger('change');
                    if (idCiudad == 0) {
                        $('#id_ciudad').val('').trigger('change');
                    } else {
                        $('#id_ciudad').val(idCiudad).trigger('change');
                    }
                }
            });
        }
    });
    $('#cheestado').change(function () {
        if ($('#cheestado').is(':checked')) {
            $('#estado').val(1);
        } else {
            $('#estado').val(0);
        }
    });
    $('#checontabilidad').change(function () {
        if ($('#checontabilidad').is(':checked')) {
            $('#contabilidad_empresa').val(1);
        } else {
            $('#contabilidad_empresa').val(0);
        }
    });
    $('.miequipo').click(function () {
        $('#myModalFooter').modal('toggle');
    });
    $("#buscar").on("keyup", function () {
        var patron = $(this).val();
        // si el campo está vacío
        if (patron == "") { // mostrar todos los elementos
            $(".lista").css("display", "list-item");
            // si tiene valores, realizar la búsqueda
        } else { // atravesar la lista
            $(".lista").each(function () {
                if ($(this).text().indexOf(patron) < 0) { // si el texto NO contiene el patrón de búsqueda, esconde el elemento
                    $(this).css("display", "none");
                } else { // si el texto SÍ contiene el patrón de búsqueda, muestra el elemento
                    $(this).css("display", "list-item");
                }
            });
        }
    });
});
$(window).bind("scroll", function () {
    let toast = $('.toast');
    toast.css("top", window.pageYOffset + 20);
});
function pregunta() {
    mensaje('Esta segur@ de querer salir del sistema?', 'question', 'admin/logout');
}
function mensaje(title, icon, url = null, form = null, modal = null, reload = true, action = 'action') {
    if (icon == 'question') {
        Swal.fire({
            title: title,
            text: 'Facturalgo informa',
            icon: icon,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#1ab394',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                if (url != null) {
                    window.open(url, '_self');
                } else if (form != null) {
                    $.ajax({
                        url: $('#' + action).val(),
                        type: 'post',
                        data: $('#' + form).serialize() + '&_token=' + $('#token').val(),
                        dataType: 'json',
                        success: function (json) {
                            var mns = json.message.split('|');
                            if (mns[0] == 'ok') {
                                mensaje(mns[1], 'success', null, null, modal);
                                $('.tablaDatos').DataTable().ajax.reload();
                            } else {
                                mensaje(mns[1], 'error', null, null, modal);
                            }
                        }
                    });
                } else {
                    Swal.fire('Saved!', '', 'success')
                }
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            } else {
                if (modal != null) {
                    $('#' + modal).modal('toggle');
                }
            }
        })
    } else {
        Swal.fire({title: title, text: 'Facturalgo informa', icon: icon}).then((result) => {
            if (result.isConfirmed) {
                if (url != null) {
                    window.open(url, '_self');
                } else {
                    try {
                        if (reload == true) {
                            $('.tablaDatos').DataTable().ajax.reload();
                        }
                        if (modal != null) {
                            $('#' + modal).modal('toggle');
                        }
                    } catch (error) {}
                }
            } else {
                if (modal != null) {
                    $('#' + modal).modal('toggle');
                }
            }
        });
    }
}
function designarempresa(id, estado) {
    if (estado == 0) {
        mensaje('La empresa se encuentra desactivada, comuniquese con el administrador del sistema...', 'info');
        return false;
    }
    $.ajax({
        url: 'admin/sesion_empresa',
        type: 'post',
        data: {
            _token: $('#token').val(),
            id: id
        },
        type: 'post',
        success: function () {
            window.open('admin', '_self');
        }
    });
}
function getFechaMysql(fecha, horas = null, separadores = '.') {
    var hora = '';
    if (fecha != '') {
        var parts = null;
        var f = null;
        var h = null;
        try {
            if (/T/.test(fecha)) {
                parts = fecha.split('T');
                f = parts[0].split('-');
                h = parts[1].split(':');
            } else {
                parts = fecha.split(' ');
                f = parts[0].split('-');
                h = parts[1].split(':');
            }
        } catch (error) {
            return;
        }
        if (horas != '') {
            var hora_ = h[0];
            if (hora_ < 10) 
                hora_ = '0' + hora_;
            


            var minuto = h[1];
            if (minuto < 10) 
                minuto = '0' + minuto;
            


            var segundos = h[2];
            if (segundos < 10) 
                segundos = '0' + segundos;
            


            hora = ' ' + hora_ + ':' + minuto + ':' + segundos + ' ' + hora;
        }
        const day = f[2];
        const month = f[1];
        const year = f[0];
        // return day + separadores + month.padStart(2, "0") + separadores + year.padStart(2, "0") + hora;
        return year.padStart(2, "0") + separadores + month.padStart(2, "0") + separadores + day + hora;
    } else 
        return '';
    


}
function getHora(fecha) {
    var f = new Date(fecha);
    var hora = '';
    var hora_ = f.getHours();
    if (hora_ < 10) 
        hora_ = '0' + hora_;
    


    var minuto = f.getMinutes();
    if (minuto < 10) 
        minuto = '0' + minuto;
    


    var segundos = f.getSeconds();
    if (segundos < 10) 
        segundos = '0' + segundos;
    


    hora = hora_ + ':' + minuto + ':' + segundos + ' ' + hora;
    return hora;
}
function eliminar(id) {
    filas --;
    $('#tr' + id).remove();
}
function PadLeft(value, length) {
    try {
        return(value.toString().length < length) ? PadLeft("0" + value, length) : value;
    } catch (error) {
        mensaje(error, 'error', '', '');
    }
}
function getFecha(fecha, horas = null, separadores = '.') {
    if (fecha != '') {
        var f = new Date(fecha);
        var dia = f.getDate();
        if (dia < 10) 
            dia = '0' + dia;
        


        var mes = f.getMonth() + 1;
        if (mes < 10) 
            mes = '0' + mes;
        


        var anio = f.getFullYear();
        var hora = '';
        var hora_ = f.getHours();
        if (hora_ < 10) 
            hora_ = '0' + hora_;
        


        var minuto = f.getMinutes();
        if (minuto < 10) 
            minuto = '0' + minuto;
        


        var segundos = f.getSeconds();
        if (segundos < 10) 
            segundos = '0' + segundos;
        


        hora = hora_ + ':' + minuto + ':' + segundos + ' ' + hora;
        fecha = dia + separadores + mes + separadores + anio;
        if (horas != null) 
            fecha = dia + separadores + mes + separadores + anio + ' ' + hora;
        


        return fecha;
    } else 
        return '';
    


}
function validarRangoFecha(hoy, fecha) {
    var f1 = new Date(hoy); // 31 de diciembre de 2015
    var f2 = new Date(fecha); // 30 de noviembre de 2014
    if (f1 <= f2) {
        return true
    } else 
        return false


    


}
function limpiarModal() {
    $('#frm_')[0].reset();
    $('#frm_').trigger('reset');
    $('.select2_demo_1').val('').trigger('change');
}
function cambiarEstado(id, estado, dato) {
    $.ajax({
        url: $('#controlador').val() + "/estado" + dato,
        type: "POST",
        data: {
            _token: $('#token').val(),
            estado: estado,
            id: id
        },
        dataType: "json",
        success: function (json) {
            if (json.status == 'success') {
                mensaje('Datos actualizados correctamente..', 'info');
                $('.tablaDatos').DataTable().ajax.reload();
            } else {
                mensaje('Error al cambiar el estado del ' + dato, 'error');
            }
        }
    });
}
function capitalizar(string) {
    return string.toLowerCase().trim().split(' ').map(v => v[0].toUpperCase() + v.substr(1)).join(' ');
}
