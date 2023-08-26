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
                        <a href="{{url('/')}}/configuraciones/catalogo" style="color:white !important;" class="btn btn-danger btn-xs">
                            <i class="fa fa-arrow-left"></i> volver
                        </a>
                        <a class="btn btn-primary btn-xs" id="btnagregarSubCatalogo">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <input type="hidden" id="id_subcatalogo_" value="<?= config('data.id') != '' ? config('data.id') : ''; ?>" />
                        <table id="tablaCatalogo" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.nombres')}}</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.valor')}}</th>
                                    <th>{{trans('tabla.orden')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.nombres')}}</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.valor')}}</th>
                                    <th>{{trans('tabla.orden')}}</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-newspaper-o"></i> Catálogo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="frm_" method="post">
                            <input type="hidden" name="d" />
                            <input type="hidden" name="id_catalogo" id="id_catalogo" />
                            <input type="hidden" name="id_catalogo_pertenece" id="id_catalogo_pertenece" />
                            <input type="hidden" name="estado_catalogo" id="estado_catalogo" value="1" />
                            <input type="hidden" id="action" value="{{config('data.controlador')}}/saveCatalogo" />
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="ibox ">
                                            <div class="ibox-content">
                                                <div class="form-group">
                                                    <label class="font-normal">Nombre: </label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <input type="text" id="nombre_catalogo" name="nombre_catalogo" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-normal">Código: </label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <input type="text" id="codigo_catalogo" name="codigo_catalogo" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-normal">Orden: </label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-sort"></i></span>
                                                        <input type="text" id="orden_catalogo" name="orden_catalogo" class="form-control text-right">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-normal">Valor: </label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <input type="text" id="valor_catalogo" name="valor_catalogo" class="form-control">
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
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarCatalogo"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>