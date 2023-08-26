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
                        <a class="btn btn-primary btn-xs" id="btnagregarDetalleLiderlist">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaDetalleLiderlist" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.imagenes')}}</th>
                                    <th>{{trans('tabla.catalogo')}}</th>
                                    <th>{{trans('tabla.pagina')}}</th>
                                    <th>{{trans('tabla.referencia')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.categoria')}}</th>
                                    <th>{{trans('tabla.subcategoria')}}</th>
                                    <th>{{trans('tabla.color')}}</th>
                                    <th>{{trans('tabla.talla')}}</th>
                                    <th>{{trans('tabla.costo')}}</th>
                                    <th>{{trans('tabla.precio')}}</th>
                                    <th>{{trans('tabla.estado')}}</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.imagenes')}}</th>
                                    <th>{{trans('tabla.catalogo')}}</th>
                                    <th>{{trans('tabla.pagina')}}</th>
                                    <th>{{trans('tabla.referencia')}}</th>
                                    <th>{{trans('tabla.nombre')}}</th>
                                    <th>{{trans('tabla.categoria')}}</th>
                                    <th>{{trans('tabla.subcategoria')}}</th>
                                    <th>{{trans('tabla.color')}}</th>
                                    <th>{{trans('tabla.talla')}}</th>
                                    <th>{{trans('tabla.costo')}}</th>
                                    <th>{{trans('tabla.precio')}}</th>
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
                                <ul class="nav nav-tabs">
                                    <li><a class="nav-link active" data-toggle="tab" href="#tab-gen"> <i class="fa fa-laptop"></i> General</a></li>
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-img"><i class="fa fa-file-image-o"></i> Imágenes</a></li>
                                </ul>
                                <form id="frm_" method="post">
                                    <div class="tab-content">
                                        <input type="hidden" name="d" />
                                        <input type="hidden" name="id_liderlist_detalle" id="id_liderlist_detalle" value="" />                                        
                                        <input type="hidden" name="estado_liderlist_detalle" id="estado_liderlist_detalle" value="1" />
                                        <input type="hidden" name="id_cabecera_liderlist_detalle" id="id_cabecera_liderlist_detalle" />
                                        <input type="hidden" id="action" value="{{config('data.controlador')}}/saveDetalleLiderlist" />
                                        <div id="tab-gen" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <div class="form-group">
                                                                    <label class="font-normal">Página: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                                        <input type="text" id="pagina_liderlist_detalle" name="pagina_liderlist_detalle" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Referencia: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                                                        <select class="form-control select2_demo_1" id="referencia_liderlist_detalle" name="referencia_liderlist_detalle">
                                                                            <option value="">--Seleccionar--</option>
                                                                            <?php
                                                                            if (config('data.referencias') != '' && count(config('data.referencias')) > 0) {
                                                                                foreach (config('data.referencias') as $row) {
                                                                                    echo '<option value="' . $row->id_producto . '|' . $row->codigo_producto . '|' . $row->descripcion_producto . '|' . $row->id_categoria_producto . '|' . $row->id_subcategoria_producto . '|' . $row->id_color_producto . '|' . $row->talla_producto . '">' . $row->codigo_producto . ' | ' . $row->descripcion_producto . ' | ' . $row->color_producto  . '</option>';
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
                                                                        <select class="form-control select2_demo_1" id="id_categoria_liderlist_detalle" name="id_categoria_liderlist_detalle">
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
                                                                    <label class="font-normal">Subcategoría: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_subcategoria_liderlist_detalle" name="id_subcategoria_liderlist_detalle">
                                                                            <option value="">--Seleccionar--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Color: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="id_color_liderlist_detalle" name="id_color_liderlist_detalle">
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
                                                                    <label class="font-normal">Talla: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                                                        <select class="form-control select2_demo_1" id="tallas_liderlist_detalle" name="tallas_liderlist_detalle[]" multiple>
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
                                                                    <label class="font-normal">Costo: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="costo_liderlist_detalle" name="costo_liderlist_detalle" class="form-control text-right" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-normal">Precio: </label>
                                                                    <div class="input-group date">
                                                                        <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                                        <input type="text" id="precio_liderlist_detalle" name="precio_liderlist_detalle" class="form-control text-right">
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
                                        <div id="tab-img" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox ">
                                                            <div class="ibox-content">
                                                                <table id="tablaIMagenes" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnguardarDatalleLiderlist"><i class="fa fa-save"></i> Guardar</button>
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