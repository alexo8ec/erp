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
                        <label style="margin: 0px;cursor: pointer;" title="Importar archivo txt" for="docSri" class="ladda-button ladda-button-demo-ruc btn btn-primary btn-xs" data-style="zoom-in">
                            <input type="file" name="docSri" id="docSri" style="display:none">
                            <i class="fa-solid fa-file-import"></i> Importar
                        </label>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaDocumentos" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>{{trans('tabla.comprobante')}}</th>
                                    <th>{{trans('tabla.numero')}}</th>
                                    <th>RUC {{trans('tabla.emisor')}}</th>
                                    <th>{{trans('tabla.razon_social')}}</th>
                                    <th>{{trans('tabla.fecha_emision')}}</th>
                                    <th>{{trans('tabla.fecha_autorizacion')}}</th>
                                    <th>{{trans('tabla.clave_acceso')}}</th>
                                    <th>{{trans('tabla.autorizacion')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>{{trans('tabla.comprobante')}}</th>
                                    <th>{{trans('tabla.numero')}}</th>
                                    <th>RUC {{trans('tabla.emisor')}}</th>
                                    <th>{{trans('tabla.razon_social')}}</th>
                                    <th>{{trans('tabla.fecha_emision')}}</th>
                                    <th>{{trans('tabla.fecha_autorizacion')}}</th>
                                    <th>{{trans('tabla.clave_acceso')}}</th>
                                    <th>{{trans('tabla.autorizacion')}}</th>
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
<div class="modal inmodal fade" id="myModalCompra" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-file-code"></i> Compra XML</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <form id="frm_" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tabs-container">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="id_empresa" id="id_empresa" value="{{session('idEmpresa')}}" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/saveCompraXml" />
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Fecha emisión:</label>
                                                            <div class="input-group date">
                                                                <input type="date" id="fecha_emision_compra_cebacera" name="fecha_emision_compra_cebacera" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Tipo de documento</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                <select id="id_tipo_documento_compra_cabecera" name="id_tipo_documento_compra_cabecera" class="form-control select2_demo_1">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.tipodocumento') != '' && count(config('data.tipodocumento')) > 0) {
                                                                        foreach (config('data.tipodocumento') as $row) {
                                                                            echo '<option value="' . $row->id_tipo_doc . '">' . $row->codigo_sri . ' | ' . $row->tipo . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Serie</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="serie_compra_cabecera" name="serie_compra_cabecera" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Autorización</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="clave_acceso_compra_cabecera" name="clave_acceso_compra_cabecera" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Días de crédito</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                <select id="dias_credito_compra_cabecera" name="dias_credito_compra_cabecera" class="form-control select2_demo_1">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <option value="0">Contado</option>
                                                                    <option value="15">15 días</option>
                                                                    <option value="30">30 días</option>
                                                                    <option value="45">45 días</option>
                                                                    <option value="60">60 días</option>
                                                                    <option value="90">90 días</option>
                                                                    <option value="120">120 días</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Proveedor: </label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                <select id="id_proveedor_compra_cabecera" name="id_proveedor_compra_cabecera" class="form-control select2_demo_1">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.proveedores') != '' && count(config('data.proveedores')) > 0) {
                                                                        foreach (config('data.proveedores') as $row) {
                                                                            $proveedor = $row->razon_social_persona != null ? $row->razon_social_persona : ($row->nombre_comercial_persona != null ? $row->nombre_comercial_persona : ($row->nombre_persona != null ? $row->nombre_persona . ' ' . $row->apellido_persona : ''));
                                                                            echo '<option value="' . $row->id_proveedor . '">' . $proveedor . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Sustento tributario</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                <select id="id_sustento_tributario_compra_cabecera" name="id_sustento_tributario_compra_cabecera" class="form-control select2_demo_1">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.sustentoTributario') != '' && count(config('data.sustentoTributario')) > 0) {
                                                                        foreach (config('data.sustentoTributario') as $row) {
                                                                            echo '<option value="' . $row->id_sustento . '">' . $row->codigo_sustento . ' ' . $row->nombre_sustento . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Secuencial</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="secuencial_tributario_compra_cabecera" name="secuencial_tributario_compra_cabecera" class="form-control text-right" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Forma de pago</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                <select id="id_forma_pago_compra_cabecera" name="id_forma_pago_compra_cabecera[]" placeholder="--Seleccionar--" class="form-control select2_demo_1" multiple>
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.formapago') != '' && count(config('data.formapago')) > 0) {
                                                                        foreach (config('data.formapago') as $row) {
                                                                            echo '<option value="' . $row->id_metodo_pago . '">' . $row->cod_sri_metodo_pago . ' | ' . $row->metodo_pago . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Concepto</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="glosa_asiento_cabecera" name="glosa_asiento_cabecera" class="form-control" value="">
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
                                <div class="tabs-container">
                                    <div class="ibox-content">
                                        <div class="form-group">
                                            <table style="width: 100%;" class="table table-bordered dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40px">Código</th>
                                                        <th style="width:40px">Cantidad</th>
                                                        <th style="width:300px">Descripción</th>
                                                        <th style="width:100px">Tipo</th>
                                                        <th style="width:100px">Cuenta</th>
                                                        <th style="width:30px">Precio</th>
                                                        <th style="width:30px">Descuento</th>
                                                        <th style="width:30px">Total</th>
                                                        <!--<th style="text-align: center;"><a href="javascript:;"><i class="fa-thin fa-square-plus fa-2x"></i></a></th>-->
                                                    </tr>
                                                </thead>
                                                <tbody id="cuerpo"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Tarifa 0%: <input type="hidden" id="subtotal_0_compra_cabecera" name="subtotal_0_compra_cabecera" /></th>
                                                        <th style="text-align:right" id="tarifa0"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Tarifa <label class="tarifaIva"></label>%: <input type="hidden" class="tarifaIva" id="tarifaIva" name="tarifaIva" /><input type="hidden" id="subtotal_12_compra_cabecera" name="subtotal_12_compra_cabecera" /></th>
                                                        <th style="text-align:right" id="tarifa12"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Subtotal: </th>
                                                        <th style="text-align:right" id="subtotal"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Descuento: <input type="hidden" id="descuento_compra_cabecera" name="descuento_compra_cabecera" /></th>
                                                        <th style="text-align:right" id="totalDescuento"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Total: </th>
                                                        <th style="text-align:right" id="total"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">Iva <label class="tarifaIva"></label>%: <input type="hidden" id="iva_compra_cabecera" name="iva_compra_cabecera" /></th>
                                                        <th style="text-align:right" id="iva"></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="2">A pagar: <input type="hidden" id="total_compra_cabecera" name="total_compra_cabecera" /></th>
                                                        <th style="text-align:right" id="total_pagar"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarCompraXml"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>