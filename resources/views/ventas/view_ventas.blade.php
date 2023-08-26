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
                        <a class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> Facturar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaVentas" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.num_factura')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>{{trans('tabla.estado_sri')}}</th>
                                    <th>{{trans('tabla.total')}}</th>
                                    <th>{{trans('tabla.vendedor')}}</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.num_factura')}}</th>
                                    <th>{{trans('tabla.cliente')}}</th>
                                    <th>{{trans('tabla.estado_sri')}}</th>
                                    <th>{{trans('tabla.total')}}</th>
                                    <th>{{trans('tabla.vendedor')}}</th>
                                    <th>{{trans('tabla.fecha')}}</th>
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
                <h4 class="modal-title"><i class="fa-solid fa-cash-register"></i> Cajas</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="frm_" method="post">
                                <div class="tab-content">
                                    @csrf
                                    <input type="hidden" name="d" />
                                    <input type="hidden" id="id_cuenta" name="id_cuenta" />
                                    <input type="hidden" id="id_usuario_creacion_cuenta" name="id_usuario_creacion_cuenta" value="{{session('idUsuario')}}" />
                                    <input type="hidden" id="id_usuario_modificacion_cuenta" name="id_usuario_modificacion_cuenta" value="{{session('idUsuario')}}" />
                                    <input type="hidden" id="id_empresa_cuenta" name="id_empresa_cuenta" value="{{session('idEmpresa')}}" />
                                    <input type="hidden" id="action" value="{{url('/')}}/{{config('data.controlador')}}/saveCaja" />
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="form-group">
                                                            <label class="font-normal">Nombre de cuenta</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="nombre_cuenta" name="nombre_cuenta" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">No de cuenta</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                                                <input type="text" id="numero_cuenta" name="numero_cuenta" class="form-control text-right">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="font-normal">Tipo de cuenta</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa-solid fa-list-check"></i></span>
                                                                <select class="form-control select2_demo_1" id="tipo_cuenta" name="tipo_cuenta">
                                                                    <option value="">--Seleccionar--</option>
                                                                    <option value="ahorro">Ahorro</option>
                                                                    <option value="corriente">Corriente</option>
                                                                    <option value="efectivo">Efectivo</option>
                                                                    <option value="tarjeta">Tarjeta</option>
                                                                </select>
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
                <button type="button" class="btn btn-primary" id="btnguardarCaja"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>