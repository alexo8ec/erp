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
                        <a class="btn btn-primary btn-xs" id="btnagregarAsiento">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                </div>
                <div class="ibox-content" align="center">
                    <div class="table-responsive">
                        <table style="width: 80%;">
                            <tr>
                                <td style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td colspan="2">INGRESOS</td>
                                        </tr>
                                        <?php

                                        use App\Models\PlanCuenta;

                                        $totalIngresos = 0;
                                        foreach (config('data.ingresos') as $row) {
                                            $totalIngresos += $row->haber;
                                            echo '<tr class="lineaI" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->haber . '\');">
                                                <td>' . $row->codigo_contable_plan . '</td>
                                                <td>' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right">$ ' . number_format($row->haber, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                TOTAL INGRESOS
                                            </td>
                                            <td>&nbsp;</td>
                                            <td align="right">$ {{number_format($totalIngresos,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td colspan="2">COSTOS</td>
                                        </tr>
                                        <?php
                                        $totalCostos = 0;
                                        foreach (config('data.costos') as $row) {
                                            $totalCostos += $row->debe;
                                            echo '<tr class="lineaC" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->debe . '\');">
                                                <td>' . $row->codigo_contable_plan . '</td>
                                                <td>' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right">$ ' . number_format($row->debe, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                TOTAL COSTOS
                                            </td>
                                            <td>&nbsp;</td>
                                            <td align="right">$ {{number_format($totalCostos,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td colspan="2">GASTOS</td>
                                        </tr>
                                        <?php
                                        $totalGastos = 0;
                                        foreach (config('data.gastos') as $row) {
                                            $totalGastos += $row->debe;
                                            echo '<tr class="lineaG" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->debe . '\');">
                                                <td>' . $row->codigo_contable_plan . '</td>
                                                <td>' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right">$ ' . number_format($row->debe, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                TOTAL GASTOS
                                            </td>
                                            <td>&nbsp;</td>
                                            <td align="right">$ {{number_format($totalGastos,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100%;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>Utilidad/Perdida bruta</td>
                                            <?php
                                            $utilidadBruta = $totalIngresos - ($totalCostos + $totalGastos);
                                            ?>
                                            <td align="right" style="color:<?= $utilidadBruta < 0 ? 'red' : ''; ?>">$ {{number_format($utilidadBruta,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>15% Participación trabajadores</td>
                                            <?php
                                            $participacion = $utilidadBruta <= 0 ? 0 : ($utilidadBruta * 15) / 100;
                                            ?>
                                            <td align="right" style="color:<?= $participacion < 0 ? 'red' : ''; ?>">$ {{number_format($participacion,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad/Perdida antes de impuestos</td>
                                            <?php
                                            $utilidadNeta = $utilidadBruta <= 0 ? 0 : ($utilidadBruta - $participacion);
                                            ?>
                                            <td align="right" style="color:<?= $utilidadNeta < 0 ? 'red' : ''; ?>">$ {{number_format($utilidadNeta,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Gastos no deducibles</td>
                                            <?php
                                            $utilidadContable = $utilidadNeta - PlanCuenta::getGastosNoDeducibles();
                                            ?>
                                            <td align="right" style="color:<?= $utilidadContable < 0 ? 'red' : ''; ?>">$ {{number_format($utilidadContable,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Impuesto causado</td>
                                            <?php
                                            $impuestoCausado =  PlanCuenta::getImpuestoCausado($totalIngresos, $totalCostos, $totalGastos);
                                            ?>
                                            <td align="right" style="color:<?= $impuestoCausado < 0 ? 'red' : ''; ?>">$ {{number_format($impuestoCausado,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad/Perdida del ejercicio</td>
                                            <?php
                                            $utilidadPerdida = $utilidadNeta - $impuestoCausado;
                                            ?>
                                            <td align="right" style="color:<?= $utilidadPerdida < 0 ? 'red' : ''; ?>">$ {{number_format($utilidadPerdida,2)}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-list-ol"></i> Mayor</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tablaMayor" class="table table-striped table-bordered table-hover dataTables-example tablaDatos" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>{{trans('tabla.fecha')}}</th>
                                <th>{{trans('tabla.codigo')}}</th>
                                <th>{{trans('tabla.nombre')}}</th>
                                <th>{{trans('tabla.glosa')}}</th>
                                <th>{{trans('tabla.debe')}}</th>
                                <th>{{trans('tabla.haber')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>{{trans('tabla.fecha')}}</th>
                                <th>{{trans('tabla.codigo')}}</th>
                                <th>{{trans('tabla.nombre')}}</th>
                                <th>{{trans('tabla.glosa')}}</th>
                                <th>{{trans('tabla.debe')}}</th>
                                <th>{{trans('tabla.haber')}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>