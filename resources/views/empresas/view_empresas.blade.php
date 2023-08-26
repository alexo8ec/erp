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
                        <a class="btn btn-primary btn-xs" id="btnagregarEmpresa">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaEmpresas" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.nombres')}}</th>
                                    <th>RUC</th>
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
                                    <th>{{trans('tabla.nombres')}}</th>
                                    <th>RUC</th>
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
                <h4 class="modal-title"><i class="fa fa-building-o"></i> Empresa</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li><a class="nav-link active" data-toggle="tab" href="#tab-gen"> <i class="fa fa-laptop"></i> General</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-ubi"><i class="fa-solid fa-location-dot"></i> Ubicación</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-con"><i class="fa fa-book"></i> Cont & Factura</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-doc"><i class="fa fa-file-text"></i> Documentos</a></li>
                                </ul>
                                <form id="frm_" method="post">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="id_empresa" id="id_empresa" />
                                        <input type="hidden" name="estado_empresa" id="estado_empresa" />
                                        <input type="hidden" name="contabilidad_empresa" id="contabilidad_empresa" />
                                        <input type="hidden" name="agente_retencion_empresa" id="agente_retencion_empresa" />
                                        <input type="hidden" name="id_usuario_creacion_empresa" id="id_usuario_creacion_empresa" />
                                        <input type="hidden" name="id_usuario_modificacion_empresa" id="id_usuario_modificacion_empresa" value="{{session('idUsuario')}}" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/saveEmpresa" />
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Razon social</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="razon_social_empresa" name="razon_social_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Ruc</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="ruc_empresa" name="ruc_empresa" class="form-control text-right">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Nombre comercial</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="nombre_comercial_empresa" name="nombre_comercial_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">E-mail</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                                                        <input type="text" id="email_empresa" name="email_empresa" class="form-control">
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
                                                                        <input type="text" id="telefono_empresa" name="telefono_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Movil</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                                        <input type="text" id="celular_empresa" name="celular_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Nombre corto</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="nombre_corto_empresa" name="nombre_corto_empresa" class="form-control" value="">
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
                                        <div id="tab-ubi" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Dirección</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="direccion_matriz_empresa" name="direccion_matriz_empresa" class="form-control">
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
                                                                        <select class="form-control select2_demo_1" id="id_ciudad_empresa" name="id_ciudad_empresa">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Urbanización</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="urbanizacion_empresa" name="urbanizacion_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Etapa</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="etapa_empresa" name="etapa_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Manzana</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="mz_empresa" name="mz_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Villa</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="villa_empresa" name="villa_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Referencia dirección</label>
                                                                    <textarea id="referencia_direccion_empresa" name="referencia_direccion_empresa" class="form-control" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab-con" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Fecha de inicio</label>
                                                                    <div class="input-group date">
                                                                        <input type="date" id="fecha_inicio_empresa" name="fecha_inicio_empresa" class="form-control" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Representante legal</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="representante_empresa" name="representante_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Identificación representante legal</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                                                        <input type="text" id="identificacion_representante_empresa" name="identificacion_representante_empresa" class="form-control text-rigth">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Contador</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="contador_empresa" name="contador_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Identificación contador</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                                                        <input type="text" id="identificacion_contador_empresa" name="identificacion_contador_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal"># Resolución</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="num_resolucion_empresa" name="num_resolucion_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Tipo de regimen</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-keyboard-o"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_tipo_regimen_empresa" name="id_tipo_regimen_empresa">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.tiporegimen') != '' && count(config('data.tiporegimen')) > 0) {
                                                                                foreach (config('data.tiporegimen') as $row) {
                                                                                    echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
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
                                                                    <label class="font-normal"># Contribuyente especial</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="num_contribuyente_especial_empresa" name="num_contribuyente_especial_empresa" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Modeda</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_moneda_empresa" name="id_moneda_empresa">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.moneda') != '' && count(config('data.moneda')) > 0) {
                                                                                foreach (config('data.moneda') as $row) {
                                                                                    echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Emisión</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-paper-plane-o"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_emision_empresa" name="id_emision_empresa">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.emisiones') != '' && count(config('data.emisiones'))) {
                                                                                foreach (config('data.emisiones') as $row) {
                                                                                    echo '<option value="' . $row->id_emision . '">' . $row->nombre_emision . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Ambiente</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-paper-plane-o"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_ambiente_empresa" name="id_ambiente_empresa">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.ambientes') != '' && count(config('data.ambientes'))) {
                                                                                foreach (config('data.ambientes') as $row) {
                                                                                    echo '<option value="' . $row->id_ambiente . '">' . $row->nombre_ambiente . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Actividad empresa</label>
                                                                    <textarea id="actividad_empresa" name="actividad_empresa" class="form-control" rows="5"></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <label class="font-normal">Contabilidad</label>
                                                                            <div class="switch">
                                                                                <div class="onoffswitch">
                                                                                    <input type="checkbox" checked class="onoffswitch-checkbox" id="checontabilidad">
                                                                                    <label class="onoffswitch-label" for="checontabilidad">
                                                                                        <span class="onoffswitch-inner"></span>
                                                                                        <span class="onoffswitch-switch"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <label class="font-normal">Agen. Rete</label>
                                                                            <div class="switch">
                                                                                <div class="onoffswitch">
                                                                                    <input type="checkbox" checked class="onoffswitch-checkbox" id="cheretencion">
                                                                                    <label class="onoffswitch-label" for="cheretencion">
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
                                        <div id="tab-doc" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label id="lblruc" class="font-normal">Ruc: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen del RUC de la compañia" for="entidadRuc" class="ladda-button ladda-button-demo-ruc btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadRuc" id="entidadRuc" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label id="lblrcias" class="font-normal">Registro CIAS: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen del registo de compañias" for="entidadRegistroCias" class="ladda-button ladda-button-demo-cias btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadRegistroCias" id="entidadRegistroCias" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label id="lblrestatutos" class="font-normal">Estatutos: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen de los estatutos de la compañia" for="entidadEstatutos" class="ladda-button ladda-button-demo-estat btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadEstatutos" id="entidadEstatutos" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label id="lblacta" class="font-normal">Acta de accionistas: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen del acta de accionistas de la compañia" for="entidadActa" class="ladda-button ladda-button-demo-acta btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadActa" id="entidadActa" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label id="lblcedula" class="font-normal">Cédula representante: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen de la cedula del representante" for="entidadCedula" class="ladda-button ladda-button-demo-ced btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadCedula" id="entidadCedula" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label id="lblvotacion" class="font-normal">Certificado de votación: </label>
                                                                    <div>
                                                                        <label title="Archivo pdf o imagen del certificado de votacion del representante" for="entidadVotacion" class="ladda-button ladda-button-demo-cer btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadVotacion" id="entidadVotacion" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group" id="btnfirma">
                                                                    <label id="lblvotacion" class="font-normal">Firma dígital: </label>
                                                                    <div>
                                                                        <label title="Archivo .p12 de la compañia" for="entidadFirma" class="ladda-button ladda-button-demo-fir btn btn-primary" data-style="zoom-in">
                                                                            <input type="file" name="entidadFirma" onchange="subirFirma();" id="entidadFirma" style="display:none">
                                                                            <i class="fa fa-paperclip"></i> Añadir archivo
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Clave firma</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                                                        <input type="password" id="clave_token_empresa" name="clave_token_empresa" class="form-control">
                                                                        <button title="Validar Firma" type="button" class="btn btn-outline btn-info  dim" onclick="validarFirma();"><i class=" fa fa-puzzle-piece"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content text-center">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Logo</label>
                                                                    <div>
                                                                        <center>
                                                                            <div style="width: 200px;height: 200px;">
                                                                                <div id="logoEntidad" style="text-align: center;width: 200px;height: 250px;margin:0px auto;display:flex; justify-content: center; align-items: center;">
                                                                                    <img src="{{ asset('/public') }}/images/sinfoto.png" class="img-fluid">
                                                                                </div>
                                                                            </div>
                                                                        </center>
                                                                        <br /><br />
                                                                        <div>
                                                                            <label title="Logo de la compañia" for="entidadLogo" class="ladda-button ladda-button-demo-logo btn btn-primary" data-style="zoom-in">
                                                                                <input type="file" name="entidadLogo" id="entidadLogo" style="display:none">
                                                                                <i class="fa fa-file-image-o"></i> Logo
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
                <button type="button" class="btn btn-primary" id="btnguardarEmpresa"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>