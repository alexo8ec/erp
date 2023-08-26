<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ url('/') }}/public/images/logo_sentinelsin.png" />
    <link REL="SHORTCUT ICON" HREF="{{ url('/') }}/public/images/logo_sentinelsin.png" />
    <link href="{{ url('/') }}/public/css/bootstrap.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{ url('/') }}/public/font-awesome/css/font-awesome.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{ url('/') }}/public/css/animate.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{ url('/') }}/public/css/style.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

</head>

<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">{{ $info->nombre_info }} V{{ $info->mayor_info }}.{{ $info->menor_info }}</h2>
                <p>
                    {{ $info->descripcion_info }}
                </p>
                <p>
                    <a href="javascript:;"><i class="fa fa-info-circle"></i> Información</a>
                </p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" method="post" action="{{ url('/') }}/inicio/login">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" id="clave_usuario" name="clave_usuario" class="form-control" placeholder="Contraseña" required="">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Ingresar</button>
                        <a href="{{ url('/') }}/inicio/resetclave"><small>Olvido su contraseña?</small></a>
                    </form>
                    <?php
                    if (session('message')) {
                        $msn = explode('|', session('message'));
                        echo '<div class="alert alert-' . $msn[0] . ' text-center">' . $msn[1] . '</div>';
                    }
                    ?>
                    <p class="m-t">
                        <small>Facturalgo v3.0 creado con <i class="fa fa-heart fa-1x" style="color: red;"></i> by AmsGroup &copy; {{ date('Y') }}</small>
                    </p>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">Copyright AmsGroup</div>
            <div class="col-md-6 text-right"><small>© 2004-{{ date('Y') }}</small></div>
        </div>
    </div>
</body>

</html>