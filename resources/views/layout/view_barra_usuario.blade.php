<?php

use App\Models\Empresas;
use App\Models\Utilidades;

if (session('idEmpresa') != '' && session('estab') != '') {
    $datos = Empresas::getDatosFirma(session('idEmpresa'));
?>
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">&nbsp;</div>
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Días restantes de la firma electrónica">
                        <i class="fa-sharp fa-regular fa-calendar-days"></i> <span class="label label-danger">{{$datos['Expiracion']}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <div id=""></div>
                        <li>
                            <table>
                                <?php
                                foreach ($datos as $tex => $val) {
                                    echo '<tr>
                                        <td>' . $tex . ': </td>
                                        <td>' . $val . '</td>
                                    </tr>';
                                }
                                ?>
                            </table>
                        </li>
                    </ul>
                </li>
                <?php
                $cont = 0;
                $conte = '<table width="100%">';
                $docGe = Utilidades::getDocGenerados();
                if (isset($docGe) && count($docGe) > 0) {
                    foreach ($docGe as $row) {
                        $cont += $row->total;
                        $conte .= '<tr>
							<td><b>' . $row->tipo . ': </b></td>
							<td align="right">' . $row->total . '</td>
							</tr>';
                    }
                }
                $conte .= '</table>';
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Documentos electrónicos emitidos">
                        <i class="fa-solid fa-abacus"></i> <span class="label label-success">{{$cont}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <div id=""></div>
                        <li>
                            <center>
                                <?= $conte; ?>
                            </center>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{ url('/') }}/public/img/a7.jpg">
                                </a>
                                <div>
                                    <small class="float-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{ url('/') }}/public/img/a4.jpg">
                                </a>
                                <div>
                                    <small class="float-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{ url('/') }}/public/img/profile.jpg">
                                </a>
                                <div>
                                    <small class="float-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html" class="dropdown-item">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="profile.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="float-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="grid_options.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html" class="dropdown-item">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        {{config('data.empresa')->nombre_corto_empresa}} | {{session('periodo')}} | {{session('estab')}} | {{session('emisi')}} | {{session('tipo_establecimiento')}}
                    </a>
                </li>
                <li>
                    <a class="right-sidebar-toggle">&nbsp;&nbsp;&nbsp;</a>
                </li>
            </ul>
        </nav>
    </div>
<?php
}
?>