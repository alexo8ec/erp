<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <base href="{{url('./')}}" />
    @yield('css')
    <script src="{{ url('/') }}/public/js/jquery-3.1.1.min.js"></script>
</head>
<body>
    <div id="wrapper">
        @yield('menu')
        <div id="page-wrapper" class="gray-bg">
            @yield('barra_empresa')
            @yield('barra_usuario')
            @yield('contenido')
            @yield('chat')
            @yield('footer')
        </div>
        @yield('opciones')
        @yield('variables')
        @yield('notificaciones')
    </div>
    @yield('js')
</body>
</html>