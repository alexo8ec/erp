<input type="hidden" id="id_empresa_" value="{{ session('idEmpresa') }}" />
<input type="hidden" id="id_catalogo_pertenece_" value="{{ config('data.id')!=''?config('data.id'):'' }}" />
<input type="hidden" id="id_cabecera_liderlist_detalle_" value="{{ config('data.id')!=''?config('data.id'):'' }}" />
<input type="hidden" id="id_usuario_" value="{{ session('idUsuario') }}" />
<input type="hidden" id="token" value="{{ csrf_token() }}" />
<input type="hidden" id="controlador" value="{{config('data.controlador')}}" />
<input type="hidden" id="submodulo" value="{{config('data.submodulo')}}" />

<input type="hidden" id="emptyTable" value="{{ trans('tabla.emptyTable') }}" />
<input type="hidden" id="search" value="{{ trans('tabla.search') }}" />
<input type="hidden" id="mostrando" value="{{ trans('tabla.mostrando') }}" />
<input type="hidden" id="entradas" value="{{ trans('tabla.entradas') }}" />
<input type="hidden" id="a" value="{{ trans('tabla.a') }}" />
<input type="hidden" id="de" value="{{ trans('tabla.de') }}" />
<input type="hidden" id="mostrar" value="{{ trans('tabla.mostrar') }}" />
<input type="hidden" id="first" value="{{ trans('tabla.first') }}" />
<input type="hidden" id="last" value="{{ trans('tabla.last') }}" />
<input type="hidden" id="next" value="{{ trans('tabla.next') }}" />
<input type="hidden" id="previous" value="{{ trans('tabla.previous') }}" />
<input type="hidden" id="loadingRecords" value="{{ trans('tabla.loadingRecords') }}" />
<input type="hidden" id="processing" value="{{ trans('tabla.processing') }}" />
<input type="hidden" id="zeroRecords" value="{{ trans('tabla.zeroRecords') }}" />
<input type="hidden" id="filtrado" value="{{ trans('tabla.filtrado') }}" />

<input type="hidden" id="ver" value="{{ trans('tabla.ver') }}" />
<input type="hidden" id="editar" value="{{ trans('tabla.editar') }}" />
<input type="hidden" id="imprimir" value="{{ trans('tabla.imprimir') }}" />
<input type="hidden" id="detalle" value="{{ trans('tabla.detalle') }}" />
<input type="hidden" id="historial" value="{{ trans('tabla.historial') }}" />
<input type="hidden" id="ficha" value="{{ trans('tabla.ficha') }}" />
<input type="hidden" id="desactivar" value="{{ trans('tabla.desactivar') }}" />
<input type="hidden" id="activar" value="{{ trans('tabla.activar') }}" />
<input type="hidden" id="anular" value="{{ trans('tabla.anular') }}" />
<input type="hidden" id="datofirma" value="{{ trans('tabla.datofirma') }}" />
<input type="hidden" id="definirEmpresas" value="{{ trans('tabla.definirEmpresas') }}" />
<input type="hidden" id="submodulos" value="{{ trans('tabla.submodulos') }}" />
<input type="hidden" id="subcatalogo" value="{{ trans('tabla.subcatalogo') }}" />
<input type="hidden" id="ingresar" value="{{ trans('tabla.ingresar') }}" />

<input type="hidden" id="lunes" value="{{ trans('dias.lunes') }}" />
<input type="hidden" id="martes" value="{{ trans('dias.martes') }}" />
<input type="hidden" id="miercoles" value="{{ trans('dias.miercoles') }}" />
<input type="hidden" id="jueves" value="{{ trans('dias.jueves') }}" />
<input type="hidden" id="viernes" value="{{ trans('dias.viernes') }}" />
<input type="hidden" id="sabado" value="{{ trans('dias.sabado') }}" />
<input type="hidden" id="domingo" value="{{ trans('dias.domingo') }}" />

<input type="hidden" id="lun" value="{{ trans('dias.lun') }}" />
<input type="hidden" id="mar" value="{{ trans('dias.mar') }}" />
<input type="hidden" id="mie" value="{{ trans('dias.mie') }}" />
<input type="hidden" id="jue" value="{{ trans('dias.jue') }}" />
<input type="hidden" id="vie" value="{{ trans('dias.vie') }}" />
<input type="hidden" id="sab" value="{{ trans('dias.sab') }}" />
<input type="hidden" id="dom" value="{{ trans('dias.dom') }}" />

<input type="hidden" id="enero" value="{{ trans('meses.enero') }}" />
<input type="hidden" id="febrero" value="{{ trans('meses.febrero') }}" />
<input type="hidden" id="marzo" value="{{ trans('meses.marzo') }}" />
<input type="hidden" id="abril" value="{{ trans('meses.abril') }}" />
<input type="hidden" id="mayo" value="{{ trans('meses.mayo') }}" />
<input type="hidden" id="junio" value="{{ trans('meses.junio') }}" />
<input type="hidden" id="julio" value="{{ trans('meses.julio') }}" />
<input type="hidden" id="agosto" value="{{ trans('meses.agosto') }}" />
<input type="hidden" id="septiembre" value="{{ trans('meses.septiembre') }}" />
<input type="hidden" id="octubre" value="{{ trans('meses.octubre') }}" />
<input type="hidden" id="noviembre" value="{{ trans('meses.noviembre') }}" />
<input type="hidden" id="diciembre" value="{{ trans('meses.diciembre') }}" />

<input type="hidden" id="ene" value="{{ trans('meses.ene') }}" />
<input type="hidden" id="feb" value="{{ trans('meses.feb') }}" />
<input type="hidden" id="mar" value="{{ trans('meses.mar') }}" />
<input type="hidden" id="abr" value="{{ trans('meses.abr') }}" />
<input type="hidden" id="may" value="{{ trans('meses.may') }}" />
<input type="hidden" id="jun" value="{{ trans('meses.jun') }}" />
<input type="hidden" id="jul" value="{{ trans('meses.jul') }}" />
<input type="hidden" id="ago" value="{{ trans('meses.ago') }}" />
<input type="hidden" id="sep" value="{{ trans('meses.sep') }}" />
<input type="hidden" id="oct" value="{{ trans('meses.oct') }}" />
<input type="hidden" id="nov" value="{{ trans('meses.nov') }}" />
<input type="hidden" id="dic" value="{{ trans('meses.dic') }}" />