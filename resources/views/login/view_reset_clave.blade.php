<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="public/image/png" href="{{ url('/') }}/images/logo_sentinelsin.png" />
    <link REL="SHORTCUT ICON" HREF="{{ url('/') }}/public/images/logo_sentinelsin.png" />
    <link href="{{asset('/')}}public/css/bootstrap.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{asset('/')}}public/font-awesome/css/font-awesome.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{asset('/')}}public/css/animate.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{asset('/')}}public/css/style.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

</head>

<body class="gray-bg">
    <div class="passwordBox animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h2 class="font-bold">Has olvidado tu contraseña</h2>
                    <p>
                        Ingrese su dirección de correo electrónico y su contraseña se restablecerá y se le enviará por correo electrónico.
                    </p>
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="post" action="{{ url('/') }}/inicio/enviarclave">
                                @csrf
                                <div class="form-group">
                                    <input type="email" id="email_usuario" name="email_usuario" class="form-control" placeholder="E-mail" required="">
                                </div>
                                <button type="submit" class="btn btn-primary block full-width m-b">Enviar nueva contraseña</button>
                                <a href="{{ url('/') }}">Ya tengo mis credenciales</a>
                            </form>
                            <?php
                            if (session('message')) {
                                $msn = explode('|', session('message'));
                                echo '<div class="alert alert-' . $msn[0] . ' text-center">
                                    ' . $msn[1] . '
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">
                Copyright AmsGroup
            </div>
            <div class="col-md-6 text-right">
                <small>© 2004-{{ date('Y') }}</small>
            </div>
        </div>
    </div>
</body>

</html>