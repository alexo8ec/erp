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
                        <a class="btn btn-primary btn-xs" id="btnagregarEstablecimiento">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaEstablecimientos" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.establecimiento')}}</th>
                                    <th>{{trans('tabla.emision')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.tipo')}}</th>
                                    <th>{{trans('tabla.inicio')}}</th>
                                    <th>Email</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
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
                                    <th>{{trans('tabla.establecimiento')}}</th>
                                    <th>{{trans('tabla.emision')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.tipo')}}</th>
                                    <th>{{trans('tabla.inicio')}}</th>
                                    <th>Email</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
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
<div class="modal inmodal fade" id="myModal" role="dialog" aria-hidden="true">
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
                                                            <label class="font-normal">Tipo establecimiento</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                <input type="text" id="tipo_establecimiento" name="tipo_establecimiento" class="form-control">
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