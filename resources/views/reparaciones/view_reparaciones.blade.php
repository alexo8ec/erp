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
                        <a class="btn btn-success btn-xs" style="color:white !important;" id="btnNotaPresupuesto">
                            <i class="fa-solid fa-money"></i> Presupuesto
                        </a>
                        <a class="btn btn-primary btn-xs" style="color:white !important;" id="btnNotaRepuestos">
                            <i class="fa-solid fa-repeat-1"></i> Repuestos
                        </a>
                        <a class="btn btn-info btn-xs" style="color:white !important;" id="btnNotaReparadas">
                            <i class="fa-brands fa-steam-symbol"></i></i> Reparados
                        </a>
                        <a class="btn btn-danger btn-xs" style="color:white !important;" id="btnNotaAnuladas">
                            <i class="fa-sharp fa-solid fa-hexagon-xmark"></i> Anuladas
                        </a>
                        <a class="btn btn-default btn-xs" id="btnNotaNHR">
                            <i class="fa-solid fa-do-not-enter"></i> NHR
                        </a>
                        <a class="btn btn-warning btn-xs" style="color:white !important;" id="btnNotaEntregadas">
                            <i class="fa-solid fa-hand-holding-box"></i> Entregadas
                        </a>
                        <a class="btn btn-primary btn-xs" id="btnagregarNota">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaReparaciones" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>{{trans('tabla.num_nota')}}</th>
                                    <th>{{trans('tabla.articulo')}}</th>
                                    <th>{{trans('tabla.tipo')}}</th>
                                    <th>{{trans('tabla.presupuesto')}}</th>
                                    <th>{{trans('tabla.repuesto')}}</th>
                                    <th>{{trans('tabla.reparado')}}</th>
                                    <th>{{trans('tabla.entregado')}}</th>
                                    <th>{{trans('tabla.total')}}</th>
                                    <th>{{trans('tabla.abono')}}</th>
                                    <th>{{trans('tabla.saldo')}}</th>
                                    <th>{{trans('tabla.vendedor')}}</th>
                                    <th>{{trans('tabla.fnota')}}</th>
                                    <th>{{trans('tabla.fentrega')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{trans('tabla.num_nota')}}</th>
                                    <th>{{trans('tabla.articulo')}}</th>
                                    <th>{{trans('tabla.tipo')}}</th>
                                    <th>{{trans('tabla.presupuesto')}}</th>
                                    <th>{{trans('tabla.repuesto')}}</th>
                                    <th>{{trans('tabla.reparado')}}</th>
                                    <th>{{trans('tabla.entregado')}}</th>
                                    <th>{{trans('tabla.total')}}</th>
                                    <th>{{trans('tabla.abono')}}</th>
                                    <th>{{trans('tabla.saldo')}}</th>
                                    <th>{{trans('tabla.vendedor')}}</th>
                                    <th>{{trans('tabla.fnota')}}</th>
                                    <th>{{trans('tabla.fentrega')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
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
<div class="modal inmodal fade" id="myModalNota" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa-brands fa-steam-symbol"></i> Nota de reparación</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <form id="frm_" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <input type="hidden" id="id_nota" name="id_nota" />
                                    <input type="hidden" id="d" name="d" />
                                    <input type="hidden" id="nhr" name="nhr" value="0" />
                                    <input type="hidden" id="repos" name="repos" value="0" />
                                    <input type="hidden" id="autorizado" name="autorizado" value="0" />
                                    <input type="hidden" id="id_modelo" />
                                    <input type="hidden" id="doc_entregado" name="doc_entregado" value="0" />
                                    <input type="hidden" id="fecha_hoy" name="fecha_hoy" value="<?= date('Y-m-d') ?>" />
                                    <input type="hidden" id="idC" />
                                    <input type="hidden" id="id_usuario_creacion_nota" name="id_usuario_creacion_nota" value="<?= session('idUsuario'); ?>" />
                                    <input type="hidden" id="action" value="{{config('data.controlador')}}/saveNotaReparacion" />
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Fecha emisión</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa-solid fa-calendar"></i></span>
                                                                <input type="date" id="fecha_nota" name="fecha_nota" class="form-control text-right" value="<?= date('Y-m-d'); ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Cliente:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_cliente" name="id_cliente">
                                                                    <option value="">--Seleccionar--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Obser. del cliente</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <textarea class="form-control" rows="3" id="observacion_del_cliente" name="observacion_del_cliente"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Fecha entrega</label>
                                                            <div class="input-group">
                                                                <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Marca:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_marca" name="id_marca">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.marcas') != '' && count(config('data.marcas')) > 0) {
                                                                        if (isset(config('data.marcas')['mensaje'])) {
                                                                            echo '<option value="">' . config('data.marcas')['mensaje'] . '</option>';
                                                                        } else {
                                                                            foreach (config('data.marcas') as $row) {
                                                                                echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Accesorio</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-pencil"></i></span>
                                                                <input type="text" id="accesorio" name="accesorio" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <table style="width: 100%;">
                                                                <tr>
                                                                    <td>
                                                                        <label>NHR: <input type="checkbox" value="" id="chenhr"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Repos.: <input type="checkbox" value="" id="cherepos"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Autori.: <input type="checkbox" value="" id="cheautorizado"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Dod. Ent.: <input type="checkbox" value="" id="chedoc_entregado"> <i></i></label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="form-group oculto">
                                                            <label class="font-normal">Lugar de compra: </label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-pencil"></i></span>
                                                                <input type="text" id="lugar_compra" name="lugar_compra" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group oculto">
                                                            <label class="font-normal">No. factura compra: </label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-pencil"></i></span>
                                                                <input type="text" id="factura_compra" name="factura_compra" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">No. nota</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-hashtag"></i></span>
                                                                <input type="text" id="numero_nota" name="numero_nota" class="form-control text-right input-number" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Punto de venta</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="punto_venta" name="punto_venta">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.puntos') != '' && count(config('data.puntos')) > 0) {
                                                                        foreach (config('data.puntos') as $row) {
                                                                            $select = '';
                                                                            if (session('estab') == $row->establecimiento && session('emisi') == $row->emision_establecimiento)
                                                                                $select = 'selected';
                                                                            echo '<option value="' . $row->establecimiento . '|' . $row->emision_establecimiento  . '" ' . $select . '>' . $row->establecimiento . '|' . $row->emision_establecimiento . '|' . $row->nombre_establecimiento . '|' . $row->tipo_establecimiento . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Obser. al cliente</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <textarea class="form-control" rows="3" id="observacion_al_cliente" name="observacion_al_cliente"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Artículo:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_articulo" name="id_articulo">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.articulos') != '' && count(config('data.articulos')) > 0) {
                                                                        foreach (config('data.articulos') as $row) {
                                                                            echo '<option value="' . $row->id_subcategoria . '">' . $row->nombre_subcategoria . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Modelo:</label>
                                                            <div class="input-group date" id="cambioModelo">
                                                                <span class="input-group-addon modeloCombo" id="modeloIcon">
                                                                    <i class="fa-sharp fa-regular fa-list"></i>
                                                                </span>
                                                                <select class="form-control select2_demo_1 modeloCombo" id="modelo" name="modelo">
                                                                    <option value="">--Seleccionar--</option>
                                                                </select>
                                                                <span class="input-group-addon modelo_" style="display: none;">
                                                                    <i class="fa-solid fa-pencil"></i>
                                                                </span>
                                                                <input type="text" id="modelo_" name="modelo_" placeholder="Agregar modelo" class="form-control modelo_" style="display: none;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Tipo nota:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_tipo_nota" name="id_tipo_nota">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.tipoNota') != '' && count(config('data.tipoNota')) > 0) {
                                                                        if (isset(config('data.tipoNota')['mensaje'])) {
                                                                            echo '<option value="">' . config('data.tipoNota')['mensaje'] . '</option>';
                                                                        } else {
                                                                            foreach (config('data.tipoNota') as $row) {
                                                                                echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Inst. al taller</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <textarea class="form-control" rows="3" id="instrucciones_al_taller" name="instrucciones_al_taller"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group oculto">
                                                            <label class="font-normal">Fecha compra</label>
                                                            <div class="input-group">
                                                                <input type="date" id="fecha_compra" name="fecha_compra" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                                            <thead>
                                                <tr>
                                                    <th style="width:40px">Código</th>
                                                    <th style="width:200px">Descripción</th>
                                                    <th style="width:40px">Cantidad</th>
                                                    <th style="width:40px">Precio</th>
                                                    <th style="width:40px">Descu</th>
                                                    <th style="width:40px">Total</th>
                                                    <th style="text-align: center;width:30px;"><a href="javascript:;" id="btnAgregarLineaNota"><i class="fa-solid fa-square-plus fa-2x"></i></a></th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpo"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" align="right">
                                                        <table style="width: 40%;" cellspacing="0">
                                                            <tr>
                                                                <th colspan="8">Tarifa 0%: </th>
                                                                <th style="text-align:right"><input type="text" id="tarifa0" name="tarifa0" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">Tarifa <label class="tarifaIva"></label>%: <input type="hidden" class="tarifaIva" id="tarifaIva" name="tarifaIva" /></th>
                                                                <th style="text-align:right"><input type="text" id="tarifa12" name="tarifa12" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">Subtotal: </th>
                                                                <th style="text-align:right"><input type="text" id="subtotal" name="subtotal" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">Descuento: </th>
                                                                <th style="text-align:right"><input type="text" id="totaldescuento" name="totaldescuento" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">Total: </th>
                                                                <th style="text-align:right"><input type="text" id="subtotal" name="subtotal" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">Iva <label class="tarifaIva"></label>%: </th>
                                                                <th style="text-align:right"><input type="text" id="totaliva" name="totaliva" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="8">A pagar: </th>
                                                                <th style="text-align:right"><input type="text" id="total_pagar" name="total_pagar" style="text-align: right;" value="0.00" class="form-control" readonly /></th>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <table>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2"><?= Utilidades::mostarvaloresventa(); ?></div>
                                                    <div class="form-group" style="display: none;" id="infotarjeta">
                                                        <label class="col-sm-2 control-label">Tarjeta</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="tarjetas" name="tarjetas">
                                                                <option value="">----------</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerra</button>
                <button type="button" class="btn btn-primary" id="btnguardarNota"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModalRepuesto" textindex="0" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa-solid fa-code-pull-request"></i> Pedido de repuestos</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <form id="frm_repuesto" method="post">
                        <input type="hidden" id="id_nota_reparacion" name="id_nota" />
                        <input type="hidden" id="actionRepuestos" value="{{config('data.controlador')}}/asignarRepuestos" />
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                                    <tr>
                                        <td>Nota de reparación: </td>
                                        <td id="numNotaReparacion"></td>
                                    </tr>
                                    <tr>
                                        <td>Cliente: </td>
                                        <td id="nombreCliente"></td>
                                    </tr>
                                    <tr>
                                        <td>Fecha de ingreso: </td>
                                        <td id="fechaIngreso"></td>
                                    </tr>
                                    <tr>
                                        <td>Bodega: </td>
                                        <td>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa-solid fa-list"></i></span>
                                                <select class="form-control select2_demo_1" id="id_bodega" name="id_bodega">
                                                    <option value="">--Seleccionar--</option>
                                                    <?php
                                                    if (config('data.bodegas') != '' && count(config('data.bodegas')) > 0) {
                                                        foreach (config('data.bodegas') as $row) {
                                                            echo '<option value="' . $row->id_bodega  . '" >' . $row->establecimiento_bodega . '|' . $row->nombre_bodega .  '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Reparado: </td>
                                        <td>
                                            <label>Marque solo si ya estaria reparado el artículo. <input type="checkbox" value="" id="chenhr"> <i></i></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Técnico: </td>
                                        <td>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa-solid fa-list"></i></span>
                                                <select class="form-control select2_demo_1" id="id_tecnico" name="id_tecnico">
                                                    <option value="">--Seleccionar--</option>
                                                    <?php
                                                    if (config('data.tecnicos') != '' && count(config('data.tecnicos')) > 0) {
                                                        foreach (config('data.tecnicos') as $row) {
                                                            echo '<option value="' . $row->id_usuario  . '" >' . $row->id_usuario . '|' . $row->usuario .  '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <div class="ibox-content">
                                    <div class="form-group">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                                            <thead>
                                                <tr>
                                                    <th style="width:40px">No</th>
                                                    <th style="width:40px">Código</th>
                                                    <th style="width:200px">Descripción</th>
                                                    <th style="width:40px">Cantidad</th>
                                                    <th style="width:40px">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpoRepuestos"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerra</button>
                <button type="button" class="btn btn-primary" id="btnguardarNotaRepuestos"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModalReparar" textindex="0" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa-solid fa-user"></i> Seleccione su usuario</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <form id="frm_reparar" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <input type="hidden" id="id_notaReparar" name="id_nota" />
                                    <input type="hidden" id="actionReparar" value="{{config('data.controlador')}}/repararNora" />
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="font-normal">Usuarios:</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                <select class="form-control select2_demo_1" id="id_tecnicoReparacion" name="id_tecnico">
                                                    <option value="">--Seleccionar--</option>
                                                    <?php
                                                    if (config('data.tecnicos') != '' && count(config('data.tecnicos')) > 0) {
                                                        foreach (config('data.tecnicos') as $row)
                                                            echo '<option value="' . $row->id_usuario . '">' . $row->id_usuario . ' | ' . $row->usuario . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerra</button>
                <button type="button" class="btn btn-primary" id="btnguardarNotaReparar"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>