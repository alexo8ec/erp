<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\Categorias;
use App\Models\Ciudades;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Productos;
use App\Models\Provincias;
use App\Models\SubCategorias;
use App\Models\Usuarios;
use App\Models\Utilidades;
use App\Models\VentasCabecera;
use Illuminate\Http\Request;

class UtilidadesController extends Controller
{
    private $controlador = 'utilidades';
    public function index(Request $r)
    {
        if (session('idUsuario') == '')
            return redirect('/');
        config(['data' => []]);
        $info = Info::getInfo();
        $data['info'] = $info;
        $data['controlador'] = $this->controlador;
        $data['submodulo'] = $r->submodulo;
        $data['empresa'] = Empresas::getEmpresas(session('idEmpresa'));
        $data['usuario'] = Usuarios::getUsuario(session('idUsuario'));
        $data['modulos'] = Modulos::getModulos();
        $data['permisos'] = PermisosUsuario::getPermisos(session('idUsuario'));
        $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
        if ($r->submodulo == 'provincias') {
            return Provincias::getProvincias($r->id);
        } elseif ($r->submodulo == 'crearLineaProducto') {
            return Utilidades::crearLineaProducto($r);
        } elseif ($r->submodulo == 'ciudades') {
            return Ciudades::getCiudades($r->id);
        } elseif ($r->submodulo == 'guardarArchivo') {
            return Utilidades::guardarArchivo($r);
        } elseif ($r->submodulo == 'viewPdf') {
            $data['title'] = 'Visualizador de archivos | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
            $data['archivo'] = Utilidades::getarchivoView($r->id, $r->t);
            return view('utils/view_pdf', $data);
        } elseif ($r->submodulo == 'getArchivo') {
            return Utilidades::getArchivo($r->tipo_archivo, $r->id_usuario, $r->clase, $r->w, $r->h, $r->archivo, $r->id_empresa, $r->id_producto);
        } elseif ($r->submodulo == 'validarFirma') {
            return Utilidades::validarFirma($r);
        } elseif ($r->submodulo == 'administrararchivos') {
            $data['titulo_tabla'] = 'Administrador de archivos';
            Utilidades::crearCarpeta(base_path() . '/storage/archivos/' . session('idEmpresa') . '/' . session('idUsuario'));
            $data['contenido'] = $this->controlador . '.view_adminstrar_archivos';
            config(['data' => $data]);
            return view('view_child');
        } elseif ($r->submodulo == 'calendario') {
            $data['titulo_tabla'] = 'Calendario';
            $data['calendario'] = true;
            $data['contenido'] = $this->controlador . '.view_calendario';
            config(['data' => $data]);
            return view('layout.view_child');
            //return view($this->controlador.'.view_calendario');
        } elseif ($r->submodulo == 'getCalendario') {
            return Calendario::getCalendario($r);
        } elseif ($r->submodulo == 'base') {
            Utilidades::db_odbc();
        } elseif ($r->submodulo == 'importarproductos') {
            return Productos::importarproductos();
        } elseif ($r->submodulo == 'importarentidades') {
            return Empresas::importarentidades();
        } elseif ($r->submodulo == 'importarcategorias') {
            return Categorias::importarcategorias();
        } elseif ($r->submodulo == 'importarsubcategorias') {
            return SubCategorias::importarsubcategorias();
        } elseif ($r->submodulo == 'importarventas') {
            $ventas = VentasCabecera::importarventas();
            echo '<pre>';
            print_r($ventas);
            exit;
        } else {
            return view('errors/401', $data);
        }
    }
}
