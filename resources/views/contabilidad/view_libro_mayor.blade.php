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
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaLibroMayor" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.valor')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.valor')}}</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-list-ol"></i> LiderList</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <form id="frm_" method="post">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="id_liderlist_cabecera" id="id_liderlist_cabecera" />
                                        <input type="hidden" name="estado_liderlist_cabecera" id="estado_liderlist_cabecera" value="1" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/saveLiderlist" />
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Catálogo: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="catalogo_liderlist_cabecera" name="catalogo_liderlist_cabecera" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Descripción: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="descripcion_liderlist_cabecera" name="descripcion_liderlist_cabecera" class="form-control">
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarLiderlist"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>