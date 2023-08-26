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
<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="FormularioEventos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_calendario" name="id_calendario">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Título del evento:</label>
                        <input type="text" id="title" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de inicio:</label>
                        <div class="input-group" data-autoclose="true">
                            <input type="date" id="start" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="TituloHoraInicio">
                        <label>Hora de inicio:</label>

                        <div class="input-group clockpicker" data-autoclose="true">
                            <input type="time" id="HoraInicio" value="" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de fin:</label>
                        <div class="input-group" data-autoclose="true">
                            <input type="date" id="end" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="TituloHoraFin">
                        <label>Hora de fin:</label>

                        <div class="input-group clockpicker" data-autoclose="true">
                            <input type="time" id="HoraFin" value="" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripci&oacute;n:</label>
                    <textarea id="descripcion" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Color de fondo:</label>
                    <input type="color" value="#3788D8" id="colorFondo" class="form-control" style="height:36px;">
                </div>
                <div class="form-group">
                    <label>Color de texto:</label>
                    <input type="color" value="#ffffff" id="colorTexto" class="form-control" style="height:36px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="BotonAgregar" class="btn btn-success"><i class="fa fa-plus"></i> Agregar</button>
                <button type="button" id="BotonModificar" class="btn btn-primary"><i class="fa-duotone fa-pen-to-square"></i> Modificar</button>
                <button type="button" id="BotonBorrar" class="btn btn-info"><i class="fa-duotone fa-eraser"></i> Borrar</button>
            </div>
        </div>
    </div>
</div>