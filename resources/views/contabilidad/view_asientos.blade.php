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
                        <a class="btn btn-primary btn-xs" id="btnagregarAsiento">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaAsientos" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.glosa')}}</th>
                                    <th>{{trans('tabla.debe')}}</th>
                                    <th>{{trans('tabla.haber')}}</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                    <th>{{trans('tabla.usuario')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.glosa')}}</th>
                                    <th>{{trans('tabla.debe')}}</th>
                                    <th>{{trans('tabla.haber')}}</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                    <th>{{trans('tabla.usuario')}}</th>
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
<div class="modal inmodal fade" id="myModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Asientos</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="frm_">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="ibox">
                                            <input type="hidden" id="id_asiento_cabecera" name="id_asiento_cabecera" />
                                            <input type="hidden" id="action" value="{{url('/')}}/{{config('data.controlador')}}/saveAsientoManual" />
                                            <input type="hidden" id="d" name="d" />
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="font-normal">Fecha: </label>
                                                        <input type="date" class="form-control" id="fecha_asiento_cabecera" name="fecha_asiento_cabecera" value="<?= date('Y-m-d'); ?>" />
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="font-normal">Glosa:</label>
                                                        <textarea rows="3" id="glosa_asiento_cabecera" name="glosa_asiento_cabecera" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="font-normal">Cuenta: </label>
                                                        <select class="select2_demo_1 form-control" id="id_plan" style="width: 100%;">
                                                            <option value="">--Seleccionar--</option>
                                                            <?php
                                                            if (config('data.plan') != '' && count(config('data.plan')) > 0) {
                                                                foreach (config('data.plan') as $row) {
                                                                    echo '<option value="' . $row->id_plan . '|' . $row->codigo_contable_plan . ' | ' . $row->nombre_cuenta_plan . '">' . $row->codigo_contable_plan . ' | ' . $row->nombre_cuenta_plan . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Debe: </label>
                                                        <input type="text" class="form-control text-right input-number" id="debe" value="0.00" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Haber: </label>
                                                        <input type="text" class="form-control text-right input-number" id="haber" value="0.00" />
                                                    </div>
                                                </div><br />
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <a href="javascript:;" id="agregarAsiento" class="btn btn-success"><i class="fa-solid fa-list-tree"></i> Agregar</a>
                                                    </div>
                                                </div>
                                                <br />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%;">Código</th>
                                                                    <th style="width: 50%;">Cuenta</th>
                                                                    <th style="width: 15%;">Debe</th>
                                                                    <th style="width: 15%;">Haber</th>
                                                                    <th style="width: 5%;">...</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="detalleAsiento"></tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td>
                                                                        Estado:
                                                                    </td>
                                                                    <td align="center" id="estadoconte">
                                                                        <div id="estado"></div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control text-right" id="debe_asiento_cabecera" name="debe_asiento_cabecera" value="0.00" readonly />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control text-right" id="haber_asiento_cabecera" name="haber_asiento_cabecera" value="0.00" readonly />
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarAsiento"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>