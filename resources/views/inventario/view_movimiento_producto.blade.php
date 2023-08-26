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
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tablaMovimientoProducto" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{trans('tabla.codigo')}}</th>
                                    <th>{{trans('tabla.descripcion')}}</th>
                                    <th>{{trans('tabla.ingreso')}}</th>
                                    <th>{{trans('tabla.egreso')}}</th>
                                    <th>{{trans('tabla.comprobante')}}</th>
                                    <th>{{trans('tabla.fecha')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ingreso = 0;
                                $egreso = 0;
                                if (config('data.movimientos') != '' && count(config('data.movimientos')) > 0) {
                                    foreach (config('data.movimientos') as $row) {
                                        $ingreso += $row->ingreso_movimiento;
                                        $egreso += $row->egreso_movimiento;
                                        echo '<tr>
                                        <td>' . $row->id_movimiento . '</td>
                                        <td align="center">' . $row->codigo_producto . '</td>
                                        <td>' . $row->descripcion_producto . ' ' . $row->modelo_producto . '</td>
                                        <td align="right">' . $row->ingreso_movimiento . '</td>
                                        <td align="right">' . $row->egreso_movimiento . '</td>
                                        <td align="center">' . $row->comprobante . '</td>
                                        <td align="center">' . $row->fecha_movimiento . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Totales</th>
                                    <td align="right"><b>{{$ingreso}}</b></td>
                                    <td align="right"><b>{{$egreso}}</b></td>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>