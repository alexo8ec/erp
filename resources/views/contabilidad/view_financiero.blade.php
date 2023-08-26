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
                <div class="ibox-content">
                    <div class="table-responsive" style="font-size:12px;">
                        <table style="width: 100%;">
                            <tr>
                                <td valign="top" style="width: 49%;">
                                    <table>
                                        <tr>
                                            <td colspan="3">ACTIVOS</td>
                                        </tr>
                                        <?php
                                        $totalActivos = 0;
                                        foreach (config('data.activos') as $row) {
                                            $totalActivos += $row->debe;
                                            echo '<tr class="lineaA" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->debe . '\');">
                                                <td style="width:10%;">' . $row->codigo_contable_plan . '</td>
                                                <td style="width:80%;">' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right" style="width:10%;">$ ' . number_format($row->debe, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                    </table>
                                </td>
                                <td style="width: 2%;">&nbsp;</td>
                                <td valign="top" style="width: 49%;">
                                    <table>
                                        <tr>
                                            <td>PASIVOS</td>
                                        </tr>
                                        <?php
                                        $totalPasivos = 0;
                                        foreach (config('data.pasivos') as $row) {
                                            $totalPasivos += $row->haber;
                                            echo '<tr class="lineaPv" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->haber . '\');">
                                                <td style="width:10%;">' . $row->codigo_contable_plan . '</td>
                                                <td style="width:80%;">' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right" style="width:10%;">$ ' . number_format($row->haber, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td>PATRIMONIO</td>
                                        </tr>
                                        <?php
                                        $totalPatrimonio = 0;
                                        foreach (config('data.patrimonio') as $row) {
                                            $totalPatrimonio += $row->haber;
                                            echo '<tr class="lineaPt" onclick="verMayor(\'' . $row->codigo_contable_plan . '\',\'' . $row->haber . '\');">
                                                <td style="width:10%;">' . $row->codigo_contable_plan . '</td>
                                                <td style="width:80%;">' . $row->nombre_cuenta_plan . '</td>
                                                <td align="right" style="width:10%;">$ ' . number_format($row->haber, 2) . '</td>
                                            </tr>';
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 49%;background-color: #f8ac59;color: black;font-size: 12px;font-weight: bold;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                TOTAL ACTIVOS
                                            </td>
                                            <td align="right">$ {{number_format($totalActivos,2)}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 2%;">&nbsp;</td>
                                <td style="width: 49%;background-color: #f8ac59;color: black;font-size: 12px;font-weight: bold;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                TOTAL PASIVOS+PATRIMONIO
                                            </td>
                                            <td align="right">$ {{number_format(($totalPasivos+$totalPatrimonio),2)}}</td>
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