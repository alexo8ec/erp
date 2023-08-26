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
                        <?php
                        if (config('data.plan') != '' && count(config('data.plan')) == 0) {
                        ?>
                            <a href="javascript:;" class="btn btn-success btn-xs" id="btncopiarPlan" style="color:white !important;"><i class="fa fa-copy"></i> Copiar</a>
                        <?php
                        }
                        ?>
                        <a class="btn btn-primary btn-xs" id="btnagregarPlanCuenta">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaPlanCuentas" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('plancuenta.codigo')}}</th>
                                    <th>{{trans('plancuenta.nombre_cuenta')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('plancuenta.codigo')}}</th>
                                    <th>{{trans('plancuenta.nombre_cuenta')}}</th>
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
<div class="modal inmodal fade" id="myModal" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-list-ol"></i> Nueva cuenta</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <form id="frm_" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="tabs-container">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="id_plan" id="id_plan" />
                                        <input type="hidden" name="estado_plan" id="estado_plan" value="1" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/savePlanCuenta" />
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Clase:</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="clase_contable_plan" name="clase_contable_plan">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.clase') != '' && count(config('data.clase')) > 0) {
                                                                                foreach (config('data.clase') as $row) {
                                                                                    echo '<option value="' . $row->clase_contable_plan . '">' . $row->codigo_contable_plan . ' | ' . $row->nombre_cuenta_plan . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Grupo:</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="grupo_contable_plan" name="grupo_contable_plan">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Cuenta:</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="cuenta_contable_plan" name="cuenta_contable_plan">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Nombre: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="nombre_cuenta_plan" name="nombre_cuenta_plan" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Estado:</label>
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="tabs-container">
                                    <div class="tab-content">
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Código: </label>
                                                                    <div class="input-group date">
                                                                        <input type="hidden" id="auxiliar_contable_plan" name="auxiliar_contable_plan" />
                                                                        <input type="hidden" id="cuenta_contable_plan_" name="cuenta_contable_plan_" />
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="codigo_contable_plan" name="codigo_contable_plan" class="form-control" readonly>
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarPlanCuenta"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>