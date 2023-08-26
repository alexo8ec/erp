<?php

use Illuminate\Support\Facades\App;
?>
<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <img id="header-lang-img" src="{{asset('/')}}public/images/flags/<?= App::getLocale(); ?>.jpg" alt="Header Language" height="16">
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <?php
                    $leng = '';
                    if (config('locale.status') && count(config('locale.languages')) > 1) {

                        foreach (array_keys(config('locale.languages')) as $lang) {
                            if ($lang != App::getLocale()) {
                                $leng = $lang == 'en' ? 'Ingles' : ($lang == 'es' ? 'Español' : '');
                                /*echo '<li>
                                    <div class="dropdown-messages-box">
                                        <a class="dropdown-item float-left" href="' . route("lang.swap", $lang) . '">
                                            <img alt="image" class="rounded-circle" src="' . url('/') . '/public/images/flags/' . $lang . '.jpg">
                                        </a>
                                        <strong><a class="dropdown-item float-left" href="' . route("lang.swap", $lang) . '">' . $leng . '</a></strong>
                                    </div>
                                </li>';*/
                                echo '<li>
                                    <a href="' . route("lang.swap", $lang) . '" class="dropdown-item">
                                        <img alt="image" class="" height="16" src="' . url('/') . '/public/images/flags/' . $lang . '.jpg"> ' . $leng . '
                                    </a>
                                </li>';
                            }
                        }
                    }
                    ?>
                </ul>
            </li>
            <li>
                <span class="m-r-sm text-muted welcome-message">Último acceso: {{date('d.m.Y H:i',strtotime(config('data.usuario')->fecha_login_usuario))}}</span>
            </li>
            <li>
                <a href="javascript:;" onclick="pregunta()">
                    <i class="fa fa-sign-out"></i> Salir
                </a>
            </li>
            <li>
                <a class="right-sidebar-toggle">
                    <i class="fa fa-tasks"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>