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
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{config('data.titulo_tabla')}}</h5>
                    <div class="ibox-tools">
                        <a class="btn btn-primary btn-xs" id="btnagregarCliente">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaClientes" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.apellido')}}</th>
                                    <th>{{trans('tabla.identificacion')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
                                    <th>E-mail</th>
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
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.apellido')}}</th>
                                    <th>{{trans('tabla.identificacion')}}</th>
                                    <th>{{trans('tabla.telefono')}}</th>
                                    <th>{{trans('tabla.celular')}}</th>
                                    <th>E-mail</th>
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
<div class="modal inmodal fade modalTotal" id="myModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-user-circle-o"></i> Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="frm_">
                    @csrf
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <input type="hidden" id="id_persona" name="id_persona">
                    <input type="hidden" id="estado" name="estado_cliente">
                    <input type="hidden" id="id_usuario_creacion_persona" name="id_usuario_creacion_persona">
                    <input type="hidden" id="id_usuario_modificacion_persona" name="id_usuario_modificacion_persona">
                    <input type="hidden" id="d" name="d">
                    <input type="hidden" id="action" value="{{config('data.controlador')}}/saveCliente" />
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li><a class="nav-link active" data-toggle="tab" href="#tab-1"> <i class="fa fa-home"></i> General</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-2"><i class="fa-solid fa-earth-americas"></i> Geográficos</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-3"><i class="fa fa-address-card-o"></i> Laborales</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-4"><i class="fa fa-folder"></i> Documentación</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Nombre</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="nombre_persona" name="nombre_persona" placeholder="Nombre del cliente" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Apellido</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="apellido_persona" name="apellido_persona" placeholder="Apellido del cliente" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Tipo de identificación</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1" id="id_tipo_identificacion_persona" name="id_tipo_identificacion_persona">
                                                                <option value="">--Seleccione un tipo de identificación--</option>
                                                                <?php
                                                                if (config('data.tipoIdentificacion') != '' && count(config('data.tipoIdentificacion')) > 0) {
                                                                    foreach (config('data.tipoIdentificacion') as $row) {
                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Identificación</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-drivers-license-o"></i></span>
                                                            <input type="text" id="identificacion_persona" name="identificacion_persona" placeholder="Identificación del cliente" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Teléfono</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                            <input type="text" id="telefono_persona" name="telefono_persona" placeholder="Teléfono del cliente" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Celular</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-mobile-screen"></i></span>
                                                            <input type="text" id="celular_persona" name="celular_persona" placeholder="Celular del cliente" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">E-mail</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                            <input type="email" id="email_persona" name="email_persona" placeholder="E-mail del cliente" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Fecha de nacimiento</label>
                                                        <input type="date" id="fecha_nacimiento_persona" name="fecha_nacimiento_persona" placeholder="Fecha de nacimiento del cliente" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Género</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1" id="id_genero_persona" name="id_genero_persona">
                                                                <option value="">--Seleccione un género--</option>
                                                                <?php
                                                                if (config('data.generos') != '' && count(config('data.generos')) > 0) {
                                                                    foreach (config('data.generos') as $row) {
                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Tipo de cliente</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1" id="id_tipo_cliente" name="id_tipo_cliente">
                                                                <option value="">--Seleccione un tipo--</option>
                                                                <?php
                                                                if (config('data.tipoCliente') != '' && count(config('data.tipoCliente')) > 0) {
                                                                    foreach (config('data.tipoCliente') as $row) {
                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="font-normal">Observación</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                            <textarea class="form-control" id="observacion_cliente" rows="3" name="observacion_cliente" placeholder="Observación del cliente"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Valor de compra</label>
                                                        <div class="input-group date">
                                                            <table style="width: 100%;">
                                                                <tr>
                                                                    <td>
                                                                        <label>Valor 1: <input type="radio" value="1" id="chevalor1" name="valor_compra_cliente"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Valor 2: <input type="radio" value="2" id="chevalor2" name="valor_compra_cliente"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Valor 3: <input type="radio" value="3" id="chevalor3" name="valor_compra_cliente"> <i></i></label>
                                                                    </td>
                                                                    <td>
                                                                        <label>Valor 4: <input type="radio" value="4" id="chevalor4" name="valor_compra_cliente"> <i></i></label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
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
                                    <div id="tab-2" class="tab-pane">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">País</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 pais" id="pais">
                                                                <option value="">--Seleccione un país--</option>
                                                                <?php
                                                                if (config('data.paises') != '' && count(config('data.paises')) > 0) {
                                                                    foreach (config('data.paises') as $row) {
                                                                        echo '<option value="' . $row->id_pais . '">' . ucwords(strtolower(Utilidades::sanear_string_tildes($row->nombre_pais))) . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Provincia</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 provincia" id="provincia">
                                                                <option value="">--Seleccione una provincia--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Ciudad</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 ciudad" id="id_ciudad" name="id_ciudad_persona">
                                                                <option value="">--Seleccione una ciudad--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Dirección</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="direccion_persona" name="direccion_persona" placeholder="Dirección del cliente" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Urbanización</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="urbanizacion_persona" name="urbanizacion_persona" placeholder="Nombre de la urbanización" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Etapa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="etapa_persona" name="etapa_persona" placeholder="Etapa de la urbanización" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Manzana</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="mz_persona" name="mz_persona" placeholder="Manzana/No" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Villa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="villa_persona" name="villa_persona" placeholder="Villa/No" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="font-normal">Referencia</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <textarea class="form-control" id="referencia_domicilio_persona" rows="3" name="referencia_domicilio_persona" placeholder="Referencia del cliente"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-3" class="tab-pane">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Nombre empresa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="nombre_empresa_persona" name="nombre_empresa_persona" placeholder="Nombre de la empresa" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Ruc empresa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="ruc_empresa_persona" name="ruc_empresa_persona" placeholder="0999999999001" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Ingreso</label>
                                                        <div class="input-group date">
                                                            <input type="date" id="fecha_ingreso_empresa_persona" name="fecha_ingreso_empresa_persona" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Sueldo</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                            <input type="text" id="sueldo_empresa_persona" name="sueldo_empresa_persona" placeholder="Celular del trabajo" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Teléfono</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                            <input type="text" id="telefono_empresa_persona" name="telefono_empresa_persona" placeholder="Teléfono del trabajo" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Celular</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-mobile-screen"></i></span>
                                                            <input type="text" id="celular_empresa_persona" name="celular_empresa_persona" placeholder="Celular del trabajo" class="form-control text-right input-number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">País</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 pais" id="pais_empresa">
                                                                <option value="">--Seleccione un país--</option>
                                                                <?php
                                                                if (config('data.paises') != '' && count(config('data.paises')) > 0) {
                                                                    foreach (config('data.paises') as $row) {
                                                                        echo '<option value="' . $row->id_pais . '">' . ucwords(strtolower(Utilidades::sanear_string_tildes($row->nombre_pais))) . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Provincia</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 provincia" id="provincia_empresa">
                                                                <option value="">--Seleccione una provincia--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Ciudad</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa-sharp fa-regular fa-list"></i></span>
                                                            <select class="form-control select2_demo_1 ciudad" id="ciudad_empresa" name="id_ciudad_empresa_persona">
                                                                <option value="">--Seleccione una ciudad--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Dirección</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="direccion_empresa_persona" name="direccion_empresa_persona" placeholder="Dirección del trabajo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Urbanización</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="urbanizacion_empresa_persona" name="urbanizacion_empresa_persona" placeholder="Urbanización del trabajo" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Etapa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="etapa_empresa_persona" name="etapa_empresa_persona" placeholder="Etapa de la urbanización del trabajo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Manzana</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="mz_empresa_persona" name="mz_empresa_persona" placeholder="Manzana/No" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="font-normal">Villa</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <input type="text" id="villa_empresa_persona" name="villa_empresa_persona" placeholder="Villa/No" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="font-normal">Referencia</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                            <textarea class="form-control" id="referencia_empresa_direccion_persona" rows="3" name="referencia_empresa_direccion_persona" placeholder="Referencia del trabajo"></textarea>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCliente"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>