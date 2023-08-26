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
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <?php
    if (config('data.anios') != '' && count(config('data.anios')) > 0) {
        $cont = 0;
        $anios = '';
        foreach (config('data.anios') as $row) {
    ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5><?= ucwords(strtolower(config('data.titulo'))); ?> | <?= $row->anios; ?></h5>
                        </div>
                        <div class="ibox-content">
                            <div>
                                <canvas id="barChart<?= $cont; ?>" data-columns="<?= $row->anios; ?>" data-titulo="<?= config('data.titulo'); ?>" height="60"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
            $cont++;
        }
    }
    ?>
    <input type="hidden" id="numBar" name="numBar" value="<?= $cont; ?>" />
    <input type="hidden" id="anios" name="anios" value="<?= $anios; ?>" />
</div>