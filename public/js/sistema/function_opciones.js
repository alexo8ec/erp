$(document).ready(function () {
    $('#usuarios').change(function () {
        $('#id_submodulos_permiso').val('').trigger('change');;
        if ($('#usuarios').val() != '') {
            $.ajax({
                url: $('#controlador').val() + '/getPermisos',
                type: 'post',
                data: {
                    _token: $('#token').val(),
                    id: $('#usuarios').val()
                },
                dataType: 'json',
                success: function (json) {
                    if (json != '') {
                        var ids = JSON.parse(json.id_submodulos_permiso);
                        $('#id_permiso').val(json.id_permiso);
                        $('#id_usuario_permiso').val(json.id_usuario_permiso);
                        $('#id_usuario_creacion_permiso').val(json.id_usuario_creacion_permiso);
                        $('#id_usuario_modificacion_permiso').val(json.id_usuario_modificacion_permiso);
                        $('#id_submodulos_permiso').val(ids).trigger('change');
                    } else {
                        var ids = '';
                        $('#id_usuario_permiso').val($('#usuarios').val());
                        $('#id_usuario_creacion_permiso').val(json.id_usuario_creacion_permiso);
                        $('#id_usuario_modificacion_permiso').val(json.id_usuario_modificacion_permiso);
                        $('#id_submodulos_permiso').val(ids).trigger('change');
                    }
                }
            });
        }
    });
});
$('#guardarPermisos').click(function () {
    $.ajax({
        url: $('#controlador').val() + '/savePermisos',
        type: 'post',
        data: $('#frm_').serialize(),
        dataType: 'json',
        success: function (json) {
            var mns = json.message.split('|');
            console.log(mns);
            if (mns[0] == 'ok') { // mensaje('success', mns[1]);
                mensaje(mns[1], 'success', null, 'form');
            } else { // mensaje('error', mns[1]);
                mensaje(mns[1], 'error', null, 'form');
            }
        }
    });
});
