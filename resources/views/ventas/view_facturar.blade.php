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
    <form id="frm_factura">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{config('data.titulo_tabla')}}</h5>
                    </div>
                    <input type="hidden" id="id_venta" name="id_venta" />
                    <input type="hidden" id="idC" value="{{config('data.idC')}}" />
                    <input type="hidden" id="valor_compra_cliente" name="valor_compra_cliente" />
                    <input type="hidden" id="action" value="{{url('/')}}/{{config('data.controlador')}}/saveVenta" />
                    <input type="hidden" id="d" name="d" />
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="font-normal">Cliente:</label>
                                <select class="form-control" style="width: 100%;" id="id_cliente" name="id_cliente">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-normal">Telefono</label>
                                <input type="text" class="form-control" id="telefono" />
                            </div>
                            <div class="col-md-4">
                                <label class="font-normal">E-mail</label>
                                <input type="text" class="form-control" id="email" />
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-4">
                                <label class="font-normal">Vendedor:</label>
                                <input type="text" class="form-control" id="vendedor" value="<?= config('data.usuario')->usuario; ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="font-normal">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?= date('Y-m-d'); ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="font-normal">Pago</label>
                                <select class="select2_demo_1 form-control" id="id_forma_pago" name="id_forma_pago[]" multiple="multiple">
                                    <?php
                                    if (config('data.formapago') != '' && count(config('data.formapago')) > 0) {
                                        foreach (config('data.formapago') as $row) {
                                            $select = '';
                                            if ($row->cod_sri_metodo_pago == '01')
                                                $select = 'selected';
                                            echo '<option value="' . $row->id_metodo_pago . '" ' . $select . '>' . $row->cod_sri_metodo_pago . ' | ' . $row->metodo_pago . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-4">
                                <a href="javascript:;" id="agregarLinea" class="btn btn-danger"><i class="fa-solid fa-circle-plus"></i> Agregar línea</a>
                                <a href="javascript:;" id="agregarCliente" class="btn btn-success"><i class="fa-solid fa-user-plus"></i> Agregar cliente</a>
                                <a href="javascript:;">
                                    <label> Pagada <input type="checkbox" value="0" id="pagado" name="pagado" checked=""> <i></i></label>
                                </a>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row" id="cuentasFactura">
                                    <label for="id_cuenta_venta_cabecera">Cuentas</label>
                                    <select class="select2_demo_1 form-control" id="id_cuenta_venta_cabecera" name="id_cuenta_venta_cabecera">
                                        <option value="">--Seleccionar cuenta--</option>
                                        <?php
                                        if (config('data.cuentas') != '' && count(config('data.cuentas'))) {
                                            foreach (config('data.cuentas') as $row) {
                                                echo '<option value="' . $row->id_cuenta . '">' . $row->nombre_cuenta . ' | ' . $row->numero_cuenta . ' | ' . $row->tipo_cuenta . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Cantidad</th>
                                            <th>Descripción</th>
                                            <th>Precio unitario</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalleFactura"></tbody>
                                </table>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 60%;" valign="top">
                                            <a href="javascript:;" class="btn btn-danger" onclick="window.history.back();"><i class="fa-solid fa-circle-xmark"></i> Cancelar</a>
                                            <a href="javascript:;" class="btn btn-primary" id="btnagregarAdicional"><i class="fa-solid fa-plus-large"></i> Adicional</a>
                                            <a href="javascript:;" class="btn btn-success" id="btnguardarFactura"><i class="fa-solid fa-save"></i> Guardar</a>
                                        </td>
                                        <td style="width: 40%;" align="right">
                                            <table cellspacing="0">
                                                <tr>
                                                    <td>Subtotal 12%</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" id="subtotal12" name="subtotal12" readonly /></td>
                                                </tr>
                                                <tr>
                                                    <td>Subtotal 0%</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" readonly id="subtotal0" name="subtotal0" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Descuento</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" readonly id="descuento" name="descuento" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Total</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" readonly id="total" name="total" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Impuesto</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" readonly id="iva" name="iva" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Propina</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" id="propina" name="propina" onblur="calcular();" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Total a apagar</td>
                                                    <td><input type="text" class="form-control text-right" value="0.00" readonly id="total_pagar" name="total_pagar" /></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td colspan="3">
                                            <div style="position: relative;">
                                                <div style="position: absolute;top:-230px;">
                                                    <table width="100%" cellspacing="0" cellspacing="0">
                                                        <tr>
                                                            <td align="left">
                                                                <table id="tablaAdicionales" width="100%" cellspacing="0" cellspacing="0"></table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
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
            </div>
        </div>
    </form>
</div>
<div class="modal inmodal fade" id="myModalCliente" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-hdd-o"></i> Establecimiento</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="frm_" method="post">
                                <div class="tab-content">
                                    <input type="hidden" id="id_establecimiento" name="id_establecimiento" />
                                    <input type="hidden" id="id_usuario_creacion_establecimiento" name="id_usuario_creacion_establecimiento" />
                                    <input type="hidden" id="id_usuario_modificacion_establecimiento" name="id_usuario_modificacion_establecimiento" />
                                    <input type="hidden" id="estado_establecimiento" name="estado_establecimiento" value="1" />
                                    <input type="hidden" id="idProvincia" />
                                    <input type="hidden" id="idCiudad" />
                                    <input type="hidden" id="d" name="d" />
                                    <input type="hidden" id="action" value="{{config('data.controlador')}}/saveEstablecimiento" />
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Nombre</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Establecimiento</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="establecimiento" name="establecimiento" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Emisión</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="emision_establecimiento" name="emision_establecimiento" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal"># inicial</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                                                <input type="text" id="num_inicial_establecimiento" name="num_inicial_establecimiento" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">País</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-earth-americas"></i></span>
                                                                <select class="form-control select2_demo_1" id="pais">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.paises') != '' && count(config('data.paises'))) {
                                                                        foreach (config('data.paises') as $row) {
                                                                            echo '<option value="' . $row->id_pais . '">' . $row->nombre_pais . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Provincia</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-earth-americas"></i></span>
                                                                <select class="form-control select2_demo_1" id="provincia">
                                                                    <option value="">--Seleccionar--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Ciudad</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-sharp fa-regular fa-earth-americas"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_ciudad" name="id_ciudad_establecimiento">
                                                                    <option value="">--Seleccionar--</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Dirección</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                <input type="text" id="direccion_establecimiento" name="direccion_establecimiento" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Teléfono</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                <input type="text" id="telefono_establecimiento" name="telefono_establecimiento" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Movil</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                                <input type="text" id="celular_establecimiento" name="celular_establecimiento" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">E-mail</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                                <input type="text" id="email_establecimiento" name="email_establecimiento" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Leyenda</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <textarea class="form-control" id="leyenda_establecimiento" rows="3" name="leyenda_establecimiento"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Formato factura</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="formatofact_establecimiento" name="formatofact_establecimiento" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Formato cotización:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="formatocoti_establecimiento" name="formatocoti_establecimiento" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Formato nota: </label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="formatonota_establecimiento" name="formatonota_establecimiento" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Estado</label>
                                                            <div class="switch">
                                                                <div class="onoffswitch">
                                                                    <input type="checkbox" checked class="onoffswitch-checkbox" id="cheestado">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerra</button>
                <button type="button" class="btn btn-primary" id="btnguardarEstablecimiento"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>