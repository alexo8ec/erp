    <title><?= isset($title) ? $title : ''; ?></title>
    <base href=" {{ url('./') }}" />
    <link rel="icon" type="{{ asset('public/image/png" href="images/logo_sentinelsin.png') }}" />
    <link REL="SHORTCUT ICON" HREF="{{ asset('public/images/logo_sentinelsin.png') }}" />

    <iframe src="data:application/pdf;base64,<?= isset($archivo->archivo) ? $archivo->archivo : ''; ?>" width=100% height=100% margin="0"></iframe>