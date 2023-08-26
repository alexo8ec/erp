<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <?php

                    use Illuminate\Support\Facades\DB;

                    $imagen = config('data.usuario')->archivo != '' ? 'data:image/' . str_replace('.', '', config('data.usuario')->ext_archivo) . ';base64,' . config('data.usuario')->archivo : 'public/images/sinfoto.png';
                    ?>
                    <img alt="image" class="rounded-circle" src="{{$imagen}}" style="width: 48px;height: 48px;" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ config('data.usuario')->nombre_usuario }} {{ config('data.usuario')->apellido_usuario }}</span>
                        <span class="text-muted text-xs block">{{ config('data.usuario')->cargo_usuario }} <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                        <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                        <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="pregunta()">Salir</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    FA+
                </div>
            </li>
            <?php
            if (session('idEmpresa') != '' && session('estab') != '') {
            ?>
                <li align="center" style="border-radius: 5px;">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa-duotone fa-magnifying-glass"></i></span>
                                <input type="text" class="form-control" id="buscar" autocomplete="off" autofocus="false" />
                            </div>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>
            <li class="<?= config('data.controlador') == 'inicio' ? 'active' : ''; ?>">
                <a href="{{ url('/') }}"><i class="fa fa-home"></i> <span class="nav-label">Inicio</span></a>
            </li>
            <?php
            if (session('idEmpresa') != '' && session('estab') != '') {
                if (config('data.modulos') != null && count(config('data.modulos')) > 0) {
                    $sub = [];
                    foreach (config('data.modulos') as $row) {
                        if (config('data.controlador') == strtolower(str_replace(' ', '', $row->controlador_modulo))) {
                            $clase = 'active';
                        } else
                            $clase = '';
                        if (isset(config('data.permisos')->id_submodulos_permiso)) {
                            $sub =  $row->submodulos->where('visible_submodulo', 1);
                            $sub = $sub->whereIn('id_submodulo', json_decode(config('data.permisos')->id_submodulos_permiso));
                        }
                        if (count($sub) > 0) {
                            echo '<li class="lista ' . $clase . '"><a href="#"><i class="' . $row->icono_modulo . '"></i> <span class="nav-label">' . trans('modulos.' . $row->controlador_modulo) . '</span><span class="fa arrow"></span></a><ul class="nav nav-second-level collapse">';
                            foreach ($row->submodulos as $rows) {
                                if (config('data.submodulo') == strtolower(str_replace(' ', '', $rows->funcion_submodulo))) {
                                    $clase = 'active';
                                } else
                                    $clase = '';
                                echo '<li class="lista ' . $clase . '"><a href="' . $row->controlador_modulo . '/' . $rows->funcion_submodulo . '">' . trans('submodulos.' . $rows->funcion_submodulo) . '</a></li>';
                            }
                            echo '</ul></li>';
                        }
                    }
                }
            } elseif (config('data.tipoUso') != null && config('data.tipoUso')->codigo_catalogo == 'billeteras') {
                if (config('data.modulos') != null && count(config('data.modulos')) > 0) {
                    $sub = [];
                    DB::enableQueryLog();
                    foreach (config('data.modulos') as $row) {
                        if (config('data.controlador') == strtolower(str_replace(' ', '', $row->controlador_modulo))) {
                            $clase = 'active';
                        } else {
                            $clase = '';
                        }
                        $sub =  $row->submodulos->where('visible_submodulo', 1);
                        $idSUb = ["56", "57", "58", "60"];
                        if (config('data.usuario')->crea_cuentas_usuario == 1) {
                            $idSUb = ["56", "57", "58", "59", "60"];
                        }
                        $sub = $sub->whereIn('id_submodulo', $idSUb);
                        if (count($sub) > 0) {
                            echo '<li class="lista ' . $clase . '"><a href="#"><i class="' . $row->icono_modulo . '"></i> <span class="nav-label">' . trans('modulos.' . $row->controlador_modulo) . '</span><span class="fa arrow"></span></a><ul class="nav nav-second-level collapse">';
                            foreach ($sub as $rows) {
                                if (config('data.submodulo') == strtolower(str_replace(' ', '', $rows->funcion_submodulo))) {
                                    $clase = 'active';
                                } else
                                    $clase = '';
                                echo '<li class="lista ' . $clase . '"><a href="' . $row->controlador_modulo . '/' . $rows->funcion_submodulo . '">' . trans('submodulos.' . $rows->funcion_submodulo) . '</a></li>';
                            }
                            echo '</ul></li>';
                        }
                    }
                }
            }
            ?>
        </ul>
    </div>
</nav>