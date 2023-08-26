<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <base href="{{url('./')}}" />
    <link rel="icon" type="image/png" href="{{ url('/') }}/public/images/logo_sentinelsin.png" />
    <link REL="SHORTCUT ICON" HREF="{{ url('/') }}/public/images/logo_sentinelsin.png" />
    <link href="{{url('/')}}/public/css/bootstrap.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{url('/')}}/public/font-awesome/css/font-awesome.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{url('/')}}/public/css/animate.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{url('/')}}/public/css/style.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">FA</h1>
            </div>
            <p>Ingrese su nueva contraseña</p>
            <form class="m-t" method="post" role="form" action="{{url('/')}}/admin/cambiarClave">
                @csrf
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Nueva contraseña" id="clave_usuario" name="clave_usuario" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Repetir contraseña" id="clave_usuario_" name="clave_usuario_" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Cambiar</button>
            </form>
            <br />
            <?php
            if (session('message')) {
                $msn = explode('|', session('message'));
                echo '<div class="alert alert-' . $msn[0] . ' text-center">
                    ' . $msn[1] . '
                </div>';
            }
            ?>
            <p class="m-t"> <small>Facturalgo v3.0 creado con <i class="fa fa-heart fa-1x" style="color: red;"></i> by AmsGroup &copy; {{ date('Y') }}</small> </p>
        </div>
    </div>
    <script src="{{url('/')}}/public/js/jquery-3.1.1.min.js"></script>
    <script src="{{url('/')}}/public/js/popper.min.js"></script>
    <script src="{{url('/')}}/public/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
</body>

</html>