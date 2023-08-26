<?php

use App\Models\Utilidades;
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{trans('submodulos.'.config('data.submodulo'))}}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/')}}">Inicio</a>
            </li>
            <li class="breadcrumb-item">
                <a>{{trans('modulos.'.config('data.controlador'))}}</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>{{trans('submodulos.'.config('data.submodulo'))}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{config('data.titulo_tabla')}}</h5>
                    <div class="ibox-tools">
                        <a class="btn btn-primary btn-xs" id="btnagregarBilletera">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaBilleteras" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.identificacion')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>IBAN</th>
                                    <th>{{trans('tabla.cuenta')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
                                    <th>E-mail</th>
                                    <th>{{trans('tabla.direccion')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.identificacion')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>IBAN</th>
                                    <th>{{trans('tabla.cuenta')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
                                    <th>E-mail</th>
                                    <th>{{trans('tabla.direccion')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade modalTotal" id="myModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-wallet"></i> Billetera</h4>
            </div>
            <div class="modal-body">
                <form id="frm_">
                    @csrf
                    <input type="hidden" id="id_billetera" name="id_billetera">
                    <input type="hidden" id="estado" name="estado_billetera" value="1">
                    <input type="hidden" id="id_usuario_creacion_billetera" name="id_usuario_creacion_billetera">
                    <input type="hidden" id="id_usuario_modificacion_billetera" name="id_usuario_modificacion_billetera">
                    <input type="hidden" id="d" name="d">
                    <input type="hidden" id="action" value="{{config('data.controlador')}}/saveBilletera" />
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <div class="ibox ">
                                    <div class="ibox-content">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">Cliente</label>
                                                    <div class="input-group date">
                                                        <input type="hidden" id="id_cliente_billetera" name="id_cliente_billetera" value="" />
                                                        <select class="form-control" style="width: 100%;" id="id_cliente">
                                                            <option value="">--Seleccione un cliente--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-normal">Identificacion</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-drivers-license-o"></i></span>
                                                        <input type="text" id="identificacion" name="identificacion" placeholder="0999999999" class="form-control text-right" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">Código país</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa-solid fa-earth-americas"></i></span>
                                                        <input type="text" id="codigo_pais_billetera" name="codigo_pais_billetera" placeholder="Identificación del cliente" class="form-control text-right" value="EC" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-normal">Control 1</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                                        <input type="text" id="digito_control_1_billetera" name="digito_control_1_billetera" value="<?= rand(0, 9) . Utilidades::generateVerifyNumber(date('YmdHis')) ?>" class="form-control text-right input-number" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">Entidad</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa-solid fa-building-columns"></i></span>
                                                        <input type="text" id="entidad_billetera" name="entidad_billetera" class="form-control text-right input-number" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-normal">Oficina</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa-solid fa-building-un"></i></span>
                                                        <input type="text" id="oficina_billetera" name="oficina_billetera" class="form-control text-right input-number" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">Control 2</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                                        <input type="text" id="digito_control_2_billetera" name="digito_control_2_billetera" value="<?= rand(0, 9) . Utilidades::generateVerifyNumber(date('YmdHis')) ?>" class="form-control text-right input-number" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-normal"># cuenta</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa-thin fa-hashtag"></i></span>
                                                        <input type="text" id="numero_cuenta_billetera" name="numero_cuenta_billetera" class="form-control text-right input-number" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">IBAN</label>
                                                    <div class="input-group date">
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-thin fa-piano-keyboard"></i></span>
                                                            <input type="text" id="IBAN" class="form-control text-right input-number" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-normal">Creación</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa-sharp fa-regular fa-calendar"></i></span>
                                                        <input type="date" id="created_at_billetera" name="created_at_billetera" class="form-control input-number" value="<?= date('Y-m-d'); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="font-normal">Observación</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <textarea class="form-control" id="observacion_billetera" rows="3" name="observacion_billetera" placeholder="Observación"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-normal">Estado</label>
                                                    <div class="switch">
                                                        <div class="onoffswitch">
                                                            <input type="checkbox" checked class="onoffswitch-checkbox" id="cheestado" cheched>
                                                            <label class="onoffswitch-label" for="cheestado">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarBilletera"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>