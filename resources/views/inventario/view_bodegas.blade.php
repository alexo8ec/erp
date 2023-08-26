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
                        <a class="btn btn-primary btn-xs" id="btnagregarBodegas">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaBodegas" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.creacion')}}</th>
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
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.creacion')}}</th>
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
<div class="modal inmodal fade" id="myModalBodega" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa-solid fa-truck-ramp-box"></i> Bodegas</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="frm_" method="post">
                                <div class="tab-content">
                                    <input type="hidden" id="id_bodega" name="id_bodega" />
                                    <input type="hidden" id="id_usuario_creacion_bodega" name="id_usuario_creacion_bodega" value="<?= session('idUsuario'); ?>" />
                                    <input type="hidden" id="id_empresa_bodega" name="id_empresa_bodega" value="<?= session('idEmpresa'); ?>" />
                                    <input type="hidden" id="estado_bodega" name="estado_bodega" value="1" />
                                    <input type="hidden" id="d" name="d" />
                                    <input type="hidden" id="action" value="{{config('data.controlador')}}/saveBodega" />
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Nombre</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="nombre_bodega" name="nombre_bodega" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Establecimiento</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-list"></i></span>
                                                                <select class="form-control select2_demo_1" id="id_establecimiento_bodega" name="id_establecimiento_bodega">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <?php
                                                                    if (config('data.puntos') != '' && count(config('data.puntos')) > 0) {
                                                                        foreach (config('data.puntos') as $row) {
                                                                            echo '<option value="' . $row->id_establecimiento  . '" >' . $row->establecimiento . '|' . $row->emision_establecimiento . '|' . $row->nombre_establecimiento . '|' . $row->tipo_establecimiento . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
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
                <button type="button" class="btn btn-primary" id="btnguardarBodega"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>