<script src="{{ url('/') }}/public/js/popper.min.js"></script>
<script src="{{ url('/') }}/public/js/bootstrap.js"></script>
<script src="{{ url('/') }}/public/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="{{ url('/') }}/public/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<?php
if (config('data.controlador') != null && config('data.controlador') == 'admin') {
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/select2/select2.full.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/toastr/toastr.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<script src="{{ url('/') }}/public/js/plugins/ladda/spin.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/ladda/ladda.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/ladda/ladda.jquery.min.js"></script>

<script src="{{ url('/') }}/public/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{ url('/') }}/public/js/inspinia.js"></script>
<script src="{{ url('/') }}/public/js/plugins/pace/pace.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="{{ url('/') }}/public/js/plugins/iCheck/icheck.min.js"></script>

<?php
if (config('data.calendario') != '' && config('data.calendario') == true) {
?>

    <script src="{{ url('/') }}/public/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<?php
}
if (config('data.img') != '' && config('data.img') == true) {
?>

    <script src="{{ url('/') }}/public/js/plugins/cropper/cropper.js"></script>
    <script src="{{ url('/') }}/public/js/plugins/cropper/cropper.min.js"></script>
    <script src="{{ url('/') }}/public/js/plugins/cropper/main.js"></script>
<?php
}
if (config('data.tabla') != '' && config('data.tabla') == true) {
?>
    <script src="{{ url('/') }}/public/js/plugins/dataTables/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<?php
}
if (config('data.estadistica') != '' && config('data.estadistica') == true) {
?>
    <script src="{{ url('/') }}/public/js/plugins/chartJs/Chart.min.js"></script>
    <script src="{{ url('/') }}/public/js/demo/chartjs-demo.js"></script>
<?php
}
?>

<script src="{{ url('/') }}/public/js/sistema/function.js"></script>
<script src="{{ url('/') }}/public/js/sistema/function_{{config('data.controlador')}}.js"></script>