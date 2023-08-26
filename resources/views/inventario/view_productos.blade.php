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
                        <a class="btn btn-primary btn-xs" id="btnagregarProducto">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaProductos" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.descripcion')}}</th>
                                    <th>Stock</th>
                                    <th>{{trans('tabla.presentacion')}}</th>
                                    <th>{{trans('tabla.categoria')}}</th>
                                    <th>{{trans('tabla.subcategoria')}}</th>
                                    <th>Iva</th>
                                    <th>Ice</th>
                                    <th>{{trans('tabla.costo')}}</th>
                                    <th>{{trans('tabla.valor')}} 1</th>
                                    <th>{{trans('tabla.valor')}} 2</th>
                                    <th>{{trans('tabla.valor')}} 3</th>
                                    <th>{{trans('tabla.valor')}} 4</th>
                                    <th>Ref</th>
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
                                    <th>{{trans('tabla.descripcion')}}</th>
                                    <th>Stock</th>
                                    <th>{{trans('tabla.presentacion')}}</th>
                                    <th>{{trans('tabla.categoria')}}</th>
                                    <th>{{trans('tabla.subcategoria')}}</th>
                                    <th>Iva</th>
                                    <th>Ice</th>
                                    <th>{{trans('tabla.costo')}}</th>
                                    <th>{{trans('tabla.valor')}} 1</th>
                                    <th>{{trans('tabla.valor')}} 2</th>
                                    <th>{{trans('tabla.valor')}} 3</th>
                                    <th>{{trans('tabla.valor')}} 4</th>
                                    <th>Ref</th>
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
                <h4 class="modal-title"><i class="fa fa-archive"></i> Producto</h4>
            </div>
            <div class="modal-body">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li><a class="nav-link active" data-toggle="tab" href="#tab-gen"> <i class="fa fa-laptop"></i> General</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-desc"><i class="fa fa-minus-square-o"></i> Descuentos</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-img"><i class="fa fa-file-image-o"></i> Imágenes</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-arc"><i class="fa fa-file-text"></i> Archivos</a></li>
                                </ul>
                                <form id="frm_" method="post">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="estado_producto" id="estado_producto" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/saveProducto" />
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Producto: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="descripcion_producto" name="descripcion_producto" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Código: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil" id="readCodigo"></i></span>
                                                                        <input type="text" id="codigo_producto" name="codigo_producto" class="form-control text-right" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Tipo contable: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                                                        <select class="form-control select2_demo_1" id="tipo_contable_producto" name="tipo_contable_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <option value="activo">1 | Activos</option>
                                                                            <option value="pasivo">2 | Pasivos</option>
                                                                            <option value="ingreso">4 | Ingresos</option>
                                                                            <option value="costo">5.01 | Costos</option>
                                                                            <option value="gasto">5.02 | Gastos</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Característica: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_tipo_producto" name="id_tipo_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.tipoProducto') != '' && count(config('data.tipoProducto')) > 0) {
                                                                                if (isset(config('data.tipoProducto')['mensaje'])) {
                                                                                    echo '<option value="">' . config('data.tipoProducto')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.tipoProducto') as $row) {
                                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Categoría</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_categoria_producto" name="id_categoria_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.categorias') != '' && count(config('data.categorias')) > 0) {
                                                                                foreach (config('data.categorias') as $row) {
                                                                                    echo '<option value="' . $row->id_categoria . '">' . $row->nombre_categoria . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Marca</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_marca_producto" name="id_marca_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.marcas') != '' && count(config('data.marcas')) > 0) {
                                                                                if (isset(config('data.marcas')['mensaje']) != '') {
                                                                                    echo '<option value="">' . config('data.marcas')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.marcas') as $row) {
                                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Color: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_color_producto" name="id_color_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.colores') != '' && count(config('data.colores')) > 0) {
                                                                                if (isset(config('data.colores')['mensaje']) != '') {
                                                                                    echo '<option value="">' . config('data.colores')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.colores') as $row) {
                                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Presentación</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_presentacion_producto" name="id_presentacion_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.presentacion') != '' && count(config('data.presentacion')) > 0) {
                                                                                if (isset(config('data.presentacion')['mensaje']) != '') {
                                                                                    echo '<option value="">' . config('data.presentacion')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.presentacion') as $row) {
                                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">ICE: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_ice_producto" name="id_ice_producto">
                                                                            <?php
                                                                            if (config('data.ice') != '' && count(config('data.ice')) > 0) {
                                                                                foreach (config('data.ice') as $row) {
                                                                                    echo '<option value="' . $row->id_catalogo . '">(' . $row->id_catalogo . ') ' . $row->nombre_catalogo . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Deducible: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_deducible_producto" name="id_deducible_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.deducibles') != '' && count(config('data.deducibles')) > 0) {
                                                                                if (isset(config('data.deducibles')['mensaje']) != '') {
                                                                                    echo '<option value="">' . config('data.deducibles')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.deducibles') as $row) {
                                                                                        $select = '';
                                                                                        if ($row->id_catalogo == 732)
                                                                                            $select = 'selected';
                                                                                        echo '<option value="' . $row->id_catalogo . '" ' . $select . '>' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Min stock: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="min_stock_producto" name="min_stock_producto" class="form-control text-right" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Precio 1: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="valor1_producto" name="valor1_producto" class="form-control text-right">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Precio 3: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="valor3_producto" name="valor3_producto" class="form-control text-right">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Id interno: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="id_producto" name="id_producto" class="form-control text-right" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Código externo: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                                                        <input type="text" id="codigo_externo_producto" name="codigo_externo_producto" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Cuenta contable: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_plan_cuenta_producto" name="id_plan_cuenta_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">QR: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-qrcode"></i></span>
                                                                        <input type="text" id="qr_producto" name="qr_producto" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Subcategoría: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_subcategoria_producto" name="id_subcategoria_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Modelo: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="modelo_producto" name="modelo_producto" class="form-control" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Talla: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_talla_producto" name="id_talla_producto">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.tallas') != '' && count(config('data.tallas')) > 0) {
                                                                                if (isset(config('data.tallas')['mensaje']) != '') {
                                                                                    echo '<option value="">' . config('data.tallas')['mensaje'] . '</option>';
                                                                                } else {
                                                                                    foreach (config('data.tallas') as $row) {
                                                                                        echo '<option value="' . $row->id_catalogo . '">' . $row->nombre_catalogo . '</option>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">IVA: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_iva_producto" name="id_iva_producto">
                                                                            <?php
                                                                            if (config('data.iva') != '' && count(config('data.iva')) > 0) {
                                                                                foreach (config('data.iva') as $row) {
                                                                                    echo '<option value="' . $row->id_catalogo . '">(' . $row->id_catalogo . ') ' . $row->nombre_catalogo . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">IRBPNR: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_irbpnr_producto" name="id_irbpnr_producto">
                                                                            <?php
                                                                            if (config('data.irbpnr') != '' && count(config('data.irbpnr')) > 0) {
                                                                                foreach (config('data.irbpnr') as $row) {
                                                                                    echo '<option value="' . $row->id_catalogo . '">(' . $row->id_catalogo . ') ' . $row->nombre_catalogo . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Costo: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="costo_producto" name="costo_producto" class="form-control text-right" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Precio 2: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="valor2_producto" name="valor2_producto" class="form-control text-right">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Precio 4: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="valor4_producto" name="valor4_producto" class="form-control text-right" value="">
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
                                        <div id="tab-desc" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Dirección</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="direccion_matriz_empresa" name="" class="form-control">
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
                                                                        <select class="form-control select2_demo_1" id="id_ciudad_empresa" name="">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Urbanización</label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
                                                                        <input type="text" id="urbanizacion_empresa" name="" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab-img" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <table id="tablaIMagenes" class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center;">Vista previa</th>
                                                                            <th style="text-align: center;">Archivo</th>
                                                                            <th style="text-align: center;">Editar</th>
                                                                            <th style="text-align: center;">Orden</th>
                                                                            <th style="text-align: center;"><a href="javascript:;" class="btn btn-link" id="agregarImagenProducto"><i class="fa fa-plus-square-o fa-2x"></i></a></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="clonar"></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab-arc" class="tab-pane">
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
                <button type="button" class="btn btn-primary" id="btnguardarProducto"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModalCrop" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeCrop"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Editar imágen</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="docs-demo text-center">
                                <div class="img-container">
                                    <img src="{{url('/')}}/public/img/img_nodisp.gif" alt="Picture">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="docs-preview clearfix">
                                <div class="img-preview preview-lg"></div>
                                <div class="img-preview preview-md"></div>
                                <div class="img-preview preview-sm"></div>
                                <div class="img-preview preview-xs"></div>
                            </div>
                            <div class="docs-data">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataX">X</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataX" placeholder="x" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text">px</span>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataY">Y</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataY" placeholder="y" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text">px</span>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataWidth">Width</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataWidth" placeholder="width" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text">px</span>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataHeight">Height</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataHeight" placeholder="height" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text">px</span>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataRotate">Rotate</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataRotate" placeholder="rotate" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text">deg</span>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataScaleX">ScaleX</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataScaleX" placeholder="scaleX" readonly>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <label class="input-group-text" for="dataScaleY">ScaleY</label>
                                    </span>
                                    <input type="text" class="form-control" id="dataScaleY" placeholder="scaleY" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="actions">
                        <div class="col-md-12 docs-buttons">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Mover">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Mover">
                                        <span class="fa fa-arrows-alt"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Cortar">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Cortar">
                                        <span class="fa fa-crop"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Zoom In">
                                        <span class="fa fa-search-plus"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Zoom Out">
                                        <span class="fa fa-search-minus"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Mover a la izquierda">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Mover a la izquierda">
                                        <span class="fa fa-arrow-left"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Mover a la derecha">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Mover a la derecha">
                                        <span class="fa fa-arrow-right"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Mover hacia arriba">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Mover hacia arriba">
                                        <span class="fa fa-arrow-up"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Mover hacia abajo">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Mover hacia abajo">
                                        <span class="fa fa-arrow-down"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Girar a la izquierda">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Girar a la izquierda">
                                        <span class="fa fa-undo"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Girar a la derecha">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Girar a la derecha">
                                        <span class="fa fa-repeat"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Voltear horizontalmente">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Voltear horizontalmente">
                                        <span class="fa fa-arrows-h"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Voltear verticalmente">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Voltear verticalmente">
                                        <span class="fa fa-arrows-v"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
                                        <span class="fa fa-check"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="clear" title="Limpiar">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Limpiar">
                                        <span class="fa fa-times"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" data-method="disable" title="Deshabilitar">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Deshabilitar">
                                        <span class="fa fa-lock"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="enable" title="Habilitar">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Habilitar">
                                        <span class="fa fa-unlock"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" data-method="resert" title="Resetear">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Resetear">
                                        <span class="fa fa-refresh"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="btn-group">
                                <div style="padding-top: 8px;">
                                    <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                                        <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                                        <span class="docs-tooltip" data-toggle="tooltip" title="Importar imágen">
                                            <span class="fa fa-upload"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="btn-group btn-group-crop">
                                <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="Descargar lienzo actual">
                                        <i class="fa fa-download"></i> Actual
                                    </span>
                                </button>
                                <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 160, height: 90 })">
                                        <i class="fa fa-download"></i> 160&times;90
                                    </span>
                                </button>
                                <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 320, height: 180 })">
                                        <i class="fa fa-download"></i> 320&times;180
                                    </span>
                                </button>
                            </div>
                            <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="getCroppedCanvasTitle"><i class="fa fa-crop"></i> Cropped</h5>
                                            <button type="button" class="close" aria-label="Close" onclick="cerrarDownload();">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" onclick="cerrarDownload();"><i class="fa fa-times-circle"></i> Cerrar</button>
                                            <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg"><i class="fa fa-download"></i> Descargar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" data-method="moveTo" data-option="0">
                                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.moveTo(0)">
                                    Move to [0,0]
                                </span>
                            </button>
                            <button type="button" class="btn btn-secondary" data-method="zoomTo" data-option="1">
                                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
                                    Zoom to 100%
                                </span>
                            </button>
                            <button type="button" class="btn btn-secondary" data-method="rotateTo" data-option="180">
                                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotateTo(180)">
                                    Rotate 180°
                                </span>
                            </button>
                            <button type="button" class="btn btn-secondary" data-method="scale" data-option="-2" data-second-option="-1">
                                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-2, -1)">
                                    Scale (-2, -1)
                                </span>
                            </button>
                        </div>
                        <div class="col-md-3 docs-toggles">
                            <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 16 / 9">
                                        16:9
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 4 / 3">
                                        4:3
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 1 / 1">
                                        1:1
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 2 / 3">
                                        2:3
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: NaN">
                                        Libre
                                    </span>
                                </label>
                            </div>
                            <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
                                    <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 0">
                                        VM0
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 1">
                                        VM1
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 2">
                                        VM2
                                    </span>
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
                                    <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 3">
                                        VM3
                                    </span>
                                </label>
                            </div>
                            <div class="dropdown dropup docs-options">
                                <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown" aria-expanded="true">
                                    Opciones
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="toggleOptions">
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="responsive" type="checkbox" name="responsive" checked>
                                            <label class="form-check-label" for="responsive">responsive</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="restore" type="checkbox" name="restore" checked>
                                            <label class="form-check-label" for="restore">restore</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="checkCrossOrigin" type="checkbox" name="checkCrossOrigin" checked>
                                            <label class="form-check-label" for="checkCrossOrigin">checkCrossOrigin</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="checkOrientation" type="checkbox" name="checkOrientation" checked>
                                            <label class="form-check-label" for="checkOrientation">checkOrientation</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="modal" type="checkbox" name="modal" checked>
                                            <label class="form-check-label" for="modal">modal</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="guides" type="checkbox" name="guides" checked>
                                            <label class="form-check-label" for="guides">guides</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="center" type="checkbox" name="center" checked>
                                            <label class="form-check-label" for="center">center</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="highlight" type="checkbox" name="highlight" checked>
                                            <label class="form-check-label" for="highlight">highlight</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="background" type="checkbox" name="background" checked>
                                            <label class="form-check-label" for="background">background</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="autoCrop" type="checkbox" name="autoCrop" checked>
                                            <label class="form-check-label" for="autoCrop">autoCrop</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="movable" type="checkbox" name="movable" checked>
                                            <label class="form-check-label" for="movable">movable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="rotatable" type="checkbox" name="rotatable" checked>
                                            <label class="form-check-label" for="rotatable">rotatable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="scalable" type="checkbox" name="scalable" checked>
                                            <label class="form-check-label" for="scalable">scalable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="zoomable" type="checkbox" name="zoomable" checked>
                                            <label class="form-check-label" for="zoomable">zoomable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="zoomOnTouch" type="checkbox" name="zoomOnTouch" checked>
                                            <label class="form-check-label" for="zoomOnTouch">zoomOnTouch</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="zoomOnWheel" type="checkbox" name="zoomOnWheel" checked>
                                            <label class="form-check-label" for="zoomOnWheel">zoomOnWheel</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="cropBoxMovable" type="checkbox" name="cropBoxMovable" checked>
                                            <label class="form-check-label" for="cropBoxMovable">cropBoxMovable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="cropBoxResizable" type="checkbox" name="cropBoxResizable" checked>
                                            <label class="form-check-label" for="cropBoxResizable">cropBoxResizable</label>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="form-check">
                                            <input class="form-check-input" id="toggleDragModeOnDblclick" type="checkbox" name="toggleDragModeOnDblclick" checked>
                                            <label class="form-check-label" for="toggleDragModeOnDblclick">toggleDragModeOnDblclick</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger closeCrop"><i class="fa fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModalMovimientos" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-random"></i> Rango de fecha</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox ">
                                    <div class="ibox-content">
                                        <div class="form-group" id="data_1">
                                            <input type="hidden" id="id_producto_seleccionado" />
                                            <label class="font-normal">Desde</label>
                                            <div class="input-group" data-autoclose="true">
                                                <input type="date" id="desde" name="desde" class="form-control" value="" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group" id="data_1">
                                            <label class="font-normal">Hasta</label>
                                            <div class="input-group" data-autoclose="true">
                                                <input type="date" id="hasta" name="hasta" class="form-control" value="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarBuscarMovimiento"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>
    </div>
</div>