<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-success float-right">{{ session('periodo') }}</span>
                    </div>
                    <h5>Ventas</h5>
                </div>
                <div class="ibox-content">
                    <div style="position: absolute;"><i class="fa fa-money fa-2x" style="color: green;"></i></div>
                    <h1 class="no-margins text-right">$ {{number_format(isset(config('data.totalVentas')->total)?config('data.totalVentas')->total:0,2)}}</h1>
                    <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                    <small>Total de ventas al año</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-info float-right">{{ session('periodo') }}</span>
                    </div>
                    <h5>Compras</h5>
                </div>
                <div class="ibox-content">
                    <div style="position: absolute;"><i class="fa fa-shopping-basket fa-2x" style="color: cadetblue;"></i></div>
                    <h1 class="no-margins text-right">$ {{number_format(isset(config('data.totalCompras')->total)?config('data.totalCompras')->total:0,2)}}</h1>
                    <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                    <small>Total de compras al año</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right">{{ session('periodo') }}</span>
                    </div>
                    <h5>Cliente</h5>
                </div>
                <div class="ibox-content">
                    <div style="position: absolute;"><i class="fa fa-users fa-2x" style="color: chocolate;"></i></div>
                    <h1 class="no-margins text-right">{{number_format(isset(config('data.totalClientes')->total)?config('data.totalClientes')->total:0)}}</h1>
                    <small>Total de clientes</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-danger float-right">{{ session('periodo') }}</span>
                    </div>
                    <h5>Proveedores</h5>
                </div>
                <div class="ibox-content">
                    <div style="position: absolute;"><i class="fa fa-handshake-o fa-2x" style="color: darkslategrey;"></i></div>
                    <h1 class="no-margins text-right">{{number_format(isset(config('data.totalProveedores')->total)?config('data.totalProveedores')->total:0)}}</h1>
                    <small>Total de proveedores</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Orders</h5>
                </div>
                <input type="hidden" id="ventas_mensuales" value='<?= nl2br(config('data.ventas_mensuales')); ?>' />
                <input type="hidden" id="cobros_mensuales" value='<?= nl2br(config('data.cobros_mensuales')); ?>' />
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-dashboard-chart" style="width: 100%;"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins">2,346</h2>
                                    <small>Total orders in period</small>
                                    <div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">4,422</h2>
                                    <small>Orders in last month</small>
                                    <div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: 60%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">9,180</h2>
                                    <small>Monthly income from orders</small>
                                    <div class="stat-percent">22% <i class="fa fa-bolt text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimas ventas</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimas compras</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimas cotizaciones</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimos clientes</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimos proveedores</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Últimos productos</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-hover no-margins">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><small>Pending...</small></td>
                                        <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                        <td>Samantha</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-warning">Canceled</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                        <td>Monica</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                        <td>John</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                        <td>Agnes</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                        <td>Janet</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                    </tr>
                                    <tr>
                                        <td><span class="label label-primary">Completed</span> </td>
                                        <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                        <td>Amelia</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                    </tr>
                                    <tr>
                                        <td><small>Pending...</small> </td>
                                        <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                        <td>Damian</td>
                                        <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>