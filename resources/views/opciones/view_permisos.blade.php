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
    <form id="frm_">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <input type="hidden" id="id_permiso" name="id_permiso" />
                <input type="hidden" id="id_usuario_permiso" name="id_usuario_permiso" />
                <input type="hidden" id="id_usuario_creacion_permiso" name="id_usuario_creacion_permiso" value="<?= session('idUsuario') ?>" />
                <input type="hidden" id="id_usuario_modificacion_permiso" name="id_usuario_modificacion_permiso" value="<?= session('idUsuario') ?>" />
                <input type="hidden" id="d" name="d" />
                <div class="ibox">
                    <div class="ibox-title">Usuarios</div>
                    <div class="ibox-content">
                        <div class="col-md-12 form-group">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select class="form-control select2_demo_2" id="usuarios">
                                    <option value="">--Seleccionar--</option>
                                    <?php
                                    if (config('data.usuarios') != '' && count(config('data.usuarios')) > 0) {
                                        foreach (config('data.usuarios') as $row) {
                                            echo '<option value="' . $row->id_usuario . '">' . $row->usuario . ' | ' . $row->nombre_usuario . ' ' . $row->apellido_usuario . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <a href="javascript:;" class="btn btn-primary" id="guardarPermisos"><i class="fa fa-save"></i> Guardar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">Permisos</div>
                    <div class="ibox-content">
                        <div class="col-md-12 form-group">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-check-square-o"></i></span>
                                <select class="select2_demo_2 form-control" id="id_submodulos_permiso" name="id_submodulos_permiso[]" multiple="multiple" data-placeholder="--Seleccionar--">
                                    <?php
                                    if (config('data.modulos') != '' && count(config('data.modulos')) > 0) {
                                        $combo = '';
                                        foreach (config('data.modulos') as $rowm) {
                                            $combo .= '<optgroup label="' . $rowm->nombre_modulo . '">';
                                            if (count($rowm->submodulos) > 0) {
                                                foreach ($rowm->submodulos as $rows) {
                                                    $combo .= '<option value="' . $rows->id_submodulo . '">' . $rows->nombre_submodulo . '</option>';
                                                }
                                            }
                                            $combo .= '</optgroup>';
                                        }
                                        echo $combo;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>