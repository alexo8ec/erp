<?php
$saludo = '';
$today = getdate();
$hora = $today["hours"];
if ($hora < 6) {
    $saludo = 'Hoy has madrugado mucho... ';
} elseif ($hora < 12) {
    $saludo = 'Buenos días';
} elseif ($hora <= 18) {
    $saludo = 'Buenas tardes';
} else {
    $saludo = 'Buenas noches';
}
?>
<div class="toast toast toast-bootstrap hide" role="alert" aria-live="assertive" aria-atomic="true" style="position: absolute; top:20px; right:20px">
    <div class="toast-header">
        <i class="fa fa-square text-navy"> </i>
        <strong class="mr-auto m-l-sm">{{$saludo}}</strong>
        <small>{{date('d.m.Y H:i:s')}}</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Bienvenido a <strong>{{config('data.info')->nombre_info}} v{{config('data.info')->mayor_info}}.{{config('data.info')->menor_info}}</strong> - {{config('data.usuario')->nombre_usuario}} {{config('data.usuario')->apellido_usuario}}
    </div>
</div>