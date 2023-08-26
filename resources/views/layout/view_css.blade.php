<link rel="icon" type="image/png" href="{{ url('/') }}/public/images/logo_sentinelsin.png" />
<link REL="SHORTCUT ICON" HREF="{{ url('/') }}/public/images/logo_sentinelsin.png" />
<link href="{{ url('/') }}/public/css/bootstrap.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/font-awesome/css/font-awesome.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<link href="{{ url('/') }}/public/css/plugins/toastr/toastr.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/select2/select2.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/select2/select2-bootstrap4.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/datapicker/datepicker3.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/cropper/cropper.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/plugins/iCheck/custom.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/all.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

<?php
if (config('data.calendario') != '' && config('data.calendario') == true) {
?>
    <link href="{{ url('/') }}/public/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="{{ url('/') }}/public/css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'>
<?php
}
if (config('data.tabla') != '' && config('data.tabla') == true) {
?>
    <link href="{{ url('/') }}/public/css/plugins/dataTables/datatables.min.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" rel="stylesheet">
<?php
}
?>
<link href="{{ url('/') }}/public/css/animate.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link href="{{ url('/') }}/public/css/style.css" rel="stylesheet" media="none" onload="if(media!='all')media='all'">