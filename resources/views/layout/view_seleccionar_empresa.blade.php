<div class="wrapper wrapper-content">
    <div class="row">
        <?php
        $colores = array('0' => 'default', '1' => 'primary', '2' => 'success', '3' => 'info', '4' => 'warning', '5' => 'danger', '6' => 'link');
        if (config('data.empresas') != null && count(config('data.empresas')) > 0) {
            foreach (config('data.empresas') as $row) {
                $d = rand(0, 6);
                $estado = '<h1 class="no-margins text-info">Activado</h1>';
                $verificado = '<div class="stat-percent font-bold text-success">&nbsp;</div>';
                if ($row->estado_empresa == 0)
                    $estado = '<h1 class="no-margins text-danger">Desactivado</h1>';
                if ($row->verificacion_empresa == 1)
                    $verificado = '<small><img src="public/img/verificado.png" style="width:32px;height:32px;" title="Verificado" /></small>';
                echo '<div class="col-lg-3">
                        <a href="Javascript:;" onClick="designarempresa(\'' . $row->id_empresa . '\',\'' . $row->estado_empresa . '\');">
                            <div class="ibox ">
                                <div class="ibox-title">
                                    <div class="ibox-tools">
                                        <span class="label label-' . $colores[$d] . ' float-right">' . $row->id_empresa . '</span>
                                    </div>
                                    <h5>' . $row->nombre_corto_empresa . '</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins">' . $estado . '</h1>
                                    <div class="stat-percent font-bold text-success">' . $verificado . '</div>
                                    <small>' . $row->razon_social_empresa . '</small>
                                </div>
                            </div>
                        </a>
                    </div>';
            }
        }
        ?>
    </div>
</div>