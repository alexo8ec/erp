<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{config('data.controlador')}}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/')}}">Inicio</a>
            </li>
            <li class="breadcrumb-item">
                <a>{{config('data.submodulo')}}</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Selección de establecimiento</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <center>
                        <div class="col-lg-4">
                            <table style="width: 100%;">
                                <tr>
                                    <td align="center">
                                        <select id="cmbestablecimiento" name="cmbestablecimiento" class="form-control select2_demo_1 text-center">
                                            <option value="">--Selecionar--</option>
                                            <?php
                                            if (config('data.establecimientos') != '' && count(config('data.establecimientos')) > 0) {
                                                foreach (config('data.establecimientos') as $row) {
                                                    echo '<option value="' . $row->id_establecimiento . '" class="text-center">' . $row->establecimiento . ' | ' . $row->emision_establecimiento . ' | ' . $row->nombre_establecimiento . ' | ' . $row->tipo_establecimiento . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>