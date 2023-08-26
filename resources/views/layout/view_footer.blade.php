<?php

use App\Models\Utilidades;

$de = Utilidades::detect();
$ip = Utilidades::getRealIP();
?>
<div class="footer">
    <div class="float-right miequipo" style="cursor: pointer;">
        SO: <strong>{{$de['os']}}</strong> |
        NV: <strong>{{$de['browser']}}</strong> |
        V: <strong>{{$de['version']}}</strong>
        &nbsp; <strong>&nbsp;</strong>
        &nbsp; <strong>&nbsp;</strong>
    </div>
    <div class="miequipo" style="cursor: pointer;">
        <strong>{{config('data.info')->nombre_info}}</strong> v.{{config('data.info')->mayor_info}}.{{config('data.info')->menor_info}}.{{config('data.info')->parche_info}} &copy; {{date('Y')}}
    </div>
</div>
<div class="modal inmodal fade" id="myModalFooter" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-desktop"></i> Mi equipo</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox ">
                                <table width="100%">
                                    <tr>
                                        <td>Sistema operativo: </td>
                                        <td><i class="fa-brands <?= $de['os'] == 'WIN' ? 'fa-windows' : ($de['os'] == 'ANDROID' ? 'fa-android' : ($de['os'] == 'MAC' ? 'fa-apple' : ($de['os'] == 'IPHONE' ? 'fa-apple' : ''))); ?>"></i> {{$de['os']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Navegador: </td>
                                        <td><i class="fa-brands <?= $de['browser'] == 'EDG' ? 'fa-edge' : ($de['browser'] == 'CHROME' ? 'fa-chrome' : ($de['browser'] == 'FIREFOX' ? 'fa-firefox-browser' : ($de['browser'] == 'OPERA' ? 'fa-opera' : ''))); ?>"></i> {{$de['browser']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Versión: </td>
                                        <td><i class="fa-solid fa-code-pull-request-draft"></i> {{$de['version']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Ip: </td>
                                        <td><i class="fa-duotone fa-earth-americas"></i> {{$ip}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>