$(document).ready(function () {
    $('#external-events div.external-event').each(function () { // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });
        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 1111999, revert: true, // will cause the event to go back to its
            revertDuration: 0 // original position after the drag
        });
    });

    /* initialize the calendar
         -----------------------------------------------------------------*/
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    $('#calendar').fullCalendar({
        locale: 'es',
        monthNames: [
            $('#enero').val(),
            $('#febrero').val(),
            $('#marzo').val(),
            $('#abril').val(),
            $('#mayo').val(),
            $('#junio').val(),
            $('#julio').val(),
            $('#agosto').val(),
            $('#septiembre').val(),
            $('#octubre').val(),
            $('#noviembre').val(),
            $('#diciembre').val(),
        ],
        monthNamesShort: [
            $('#ene').val(),
            $('#feb').val(),
            $('#mar').val(),
            $('#abr').val(),
            $('#may').val(),
            $('#jun').val(),
            $('#jul').val(),
            $('#ago').val(),
            $('#sep').val(),
            $('#oct').val(),
            $('#nov').val(),
            $('#dic').val(),
        ],
        dayNames: [
            $('#domingo').val(),
            $('#lunes').val(),
            $('#martes').val(),
            $('#miercoles').val(),
            $('#jueves').val(),
            $('#viernes').val(),
            $('#sabado').val(),
        ],
        dayNamesShort: [
            $('#dom').val(),
            $('#lun').val(),
            $('#mar').val(),
            $('#mie').val(),
            $('#jue').val(),
            $('#vie').val(),
            $('#sab').val(),
        ],
        plugins: [
            'dayGrid', 'timeGrid', 'interaction'
        ],
        height: 800,
        showNonCurrentDates: false,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        drop: function () { // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                $(this).remove();
            }
            limpiarFormulario();
            $('#ColorFondo').val(info.draggedEl.dataset.colorfondo);
            $('#ColorTexto').val(info.draggedEl.dataset.colortexto);
            $('#title').val(info.draggedEl.dataset.titulo);
            let fechaHora = info.dateStr.split("T");
            $('#start').val(fechaHora[0]);
            $('#end').val(fechaHora[0]);
            if (info.allDay) {
                $('#HoraInicio').val(info.draggedEl.dataset.horainicio);
                $('#HoraFin').val(info.draggedEl.dataset.horafin);
            } else {
                $('#HoraInicio').val(fechaHora[1].substring(0, 5));
                $('#HoraFin').val(moment(fechaHora[1].substring(0, 5)).add(1, 'hours'));
            }
            let registro = recuperarDatosFormulario();
            agregarEventoPredefinido(registro);
        },
        events: $('#controlador').val() + '/getCalendario?a=listar',
        dayClick: function (info) {
            limpiarFormulario();
            $('#BotonAgregar').show();
            $('#BotonModificar').hide();
            $('#BotonBorrar').hide();
            if (info.allDay) {
                $('#start').val(info.dateStr);
                $('#end').val(info.dateStr);
            } else {
                var d = new Date(info._d);
                let fechaHora = sumarDias(d, + 1);
                fechaHora = addHoursToDate(fechaHora, 19);
                const formatDate = (current_datetime) => {
                    anio = current_datetime.getFullYear();
                    mes = (current_datetime.getMonth() + 1);
                    if (mes < 10) {
                        mes = '0' + mes;
                    }
                    dia = current_datetime.getDate();
                    if (dia < 10) {
                        dia = '0' + dia;
                    }
                    hora = current_datetime.getHours();
                    if (hora < 10) {
                        hora = '0' + hora;
                    }
                    minuto = current_datetime.getMinutes();
                    if (minuto < 10) {
                        minuto = '0' + minuto;
                    }
                    segundo = current_datetime.getSeconds();
                    if (segundo < 10) {
                        segundo = '0' + segundo;
                    }
                    let formatted_date = anio + "-" + mes + "-" + dia + " " + hora + ":" + minuto + ":" + segundo;
                    return formatted_date;
                }
                var fecha = formatDate(fechaHora).split(' ');
                $('#start').val(fecha[0]);
                $('#end').val(fecha[0]);
                $('#HoraInicio').val(fecha[1]);
                $('#HoraFin').val(fecha[1]);
            }
            $("#FormularioEventos").modal();
        },
        eventClick: function (info) {
            $('#BotonModificar').show();
            $('#BotonBorrar').show();
            $('#BotonAgregar').hide();
            $('#id_calendario').val(info.id_calendario);
            $('#title').val(info.title);
            $('#start').val(moment(info.start).format("YYYY-MM-DD"));
            $('#end').val(moment(info.end).format("YYYY-MM-DD"));
            $('#HoraInicio').val(moment(info.start).format("HH:mm"));
            $('#HoraFin').val(moment(info.end).format("HH:mm"));
            $('#descripcion').val(info.descripcion);
            $('#colorFondo').val(info.backgroundColor);
            $('#colorTexto').val(info.textColor);
            $("#FormularioEventos").modal();
        },
        eventResize: function (info) {
            $('#id_calendario').val(info.id_calendario);
            $('#title').val(info.title);
            $('#start').val(moment(info.start).format("YYYY-MM-DD"));
            $('#end').val(moment(info.end).format("YYYY-MM-DD"));
            $('#HoraInicio').val(moment(info.start).format("HH:mm"));
            $('#HoraFin').val(moment(info.end).format("HH:mm"));
            $('#colorFondo').val(info.backgroundColor);
            $('#colorTexto').val(info.textColor);
            $('#descripcion').val(info.descripcion);
            let registro = recuperarDatosFormulario();
            modificarRegistro(registro);
        },
        eventDrop: function (info) {
            $('#id_calendario').val(info.id_calendario);
            $('#title').val(info.title);
            $('#start').val(moment(info.start).format("YYYY-MM-DD"));
            $('#end').val(moment(info.end).format("YYYY-MM-DD"));
            $('#HoraInicio').val(moment(info.start).format("HH:mm"));
            $('#HoraFin').val(moment(info.end).format("HH:mm"));
            $('#ColorFondo').val(info.backgroundColor);
            $('#ColorTexto').val(info.textColor);
            $('#descripcion').val(info.descripcion);
            let registro = recuperarDatosFormulario();
            modificarRegistro(registro);
        }
    });
    $('#BotonAgregar').click(function () {
        let registro = recuperarDatosFormulario();
        agregarRegistro(registro);
        $("#FormularioEventos").modal('hide');
    });

    $('#BotonModificar').click(function () {
        let registro = recuperarDatosFormulario();
        modificarRegistro(registro);
        $("#FormularioEventos").modal('hide');
    });

    $('#BotonBorrar').click(function () {
        let registro = recuperarDatosFormulario();
        borrarRegistro(registro);
        $("#FormularioEventos").modal('hide');
    });
    $('#BotonEventosPredefinidos').click(function () {
        window.location = "eventospredefinidos.html";
    });
});
function limpiarFormulario() {
    $('#id_calendario').val('');
    $('#title').val('');
    $('#descripcion').val('');
    $('#start').val('');
    $('#end').val('');
    $('#HoraInicio').val('');
    $('#HoraFin').val('');
    $('#colorFondo').val('#3788D8');
    $('#colorTexto').val('#ffffff');
}
function sumarDias(fecha, dias) {
    fecha.setDate(fecha.getDate() + dias);
    return fecha;
}
function addHoursToDate(objDate, intHours) {
    var numberOfMlSeconds = objDate.getTime();
    var addMlSeconds = (intHours * 60) * 60000;
    var newDateObj = new Date(numberOfMlSeconds - addMlSeconds);
    return newDateObj;
}
function recuperarDatosFormulario() {
    let registro = {
        id_calendario: $('#id_calendario').val(),
        title: $('#title').val(),
        descripcion: $('#descripcion').val(),
        start: $('#start').val() + ' ' + $('#HoraInicio').val(),
        end: $('#end').val() + ' ' + $('#HoraFin').val(),
        backgroundColor: $('#colorFondo').val(),
        textColor: $('#colorTexto').val()
    };
    return registro;
}
function agregarRegistro(registro) {
    $.ajax({
        type: 'post',
        url: $('#controlador').val() + '/getCalendario?a=agregar',
        data: {
            _token: $('#token').val(),
            registro: registro
        },
        success: function (msg) {
            $("#calendar").fullCalendar('refetchEvents');
        },
        error: function (error) {
            alert("Hay un problema:" + error);
        }
    });
}
function modificarRegistro(registro) {
    $.ajax({
        type: 'post',
        url: $('#controlador').val() + '/getCalendario?a=modificar',
        data: {
            _token: $('#token').val(),
            registro: registro
        },
        success: function (msg) {
            $("#calendar").fullCalendar('refetchEvents');
        },
        error: function (error) {
            alert("Hay un problema:" + error);
        }
    });
}

function borrarRegistro(registro) {
    $.ajax({
        type: 'post',
        url: $('#controlador').val() + '/getCalendario?a=borrar',
        data: {
            _token: $('#token').val(),
            registro: registro
        },
        success: function (msg) {
            $("#calendar").fullCalendar('refetchEvents');
        },
        error: function (error) {
            alert("Hay un problema:" + error);
        }
    });
}
