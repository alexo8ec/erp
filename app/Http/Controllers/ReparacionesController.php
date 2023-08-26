<?php

namespace App\Http\Controllers;

use App\Models\Bodegas;
use App\Models\Catalogos;
use App\Models\Categorias;
use App\Models\Clientes;
use App\Models\Empresas;
use App\Models\Establecimientos;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Productos;
use App\Models\ReparacionesCabecera;
use App\Models\ReparacionesDetalle;
use App\Models\Usuarios;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class ReparacionesController extends Controller
{
    private $controlador = 'reparaciones';
    public function index(Request $r)
    {
        config(['data' => []]);
        if (session('idUsuario') == '')
            return redirect('/');
        else {
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
            if ($r->submodulo == 'listar') {
                if (!Utilidades::verificarPermisos($r))
                    return view('errors/401', $data);
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Opciones';
                $data['tipoNota'] = Catalogos::traerCatalogo('tiponota', 'asc');
                $data['marcas'] = Catalogos::traerCatalogo('marcas', 'asc');
                $data['puntos'] = Establecimientos::getPuntos();
                $data['bodegas'] = Bodegas::getBodegas();
                $data['tecnicos'] = Usuarios::getUsuario();
                $data['articulos'] = Categorias::getArticulos();
                $data['contenido'] = $this->controlador . '.view_reparaciones';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'listarjs') {
                $data = ReparacionesCabecera::getListar();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'saveNotaReparacion') {
                return ReparacionesCabecera::saveNotaReparacion($r);
            } elseif ($r->submodulo == 'getNumNotaReparacion') {
                return ReparacionesCabecera::getNumNotaReparacion();
            } elseif ($r->submodulo == 'buscarCliente') {
                return Clientes::clientesLineajs($r);
            } elseif ($r->submodulo == 'getModelosCombo') {
                return Productos::getModelosCombo($r);
            } elseif ($r->submodulo == 'darPresupuesto') {
                return ReparacionesCabecera::getListar($r->id);
            } elseif ($r->submodulo == 'comboClienteSeleccionado') {
                return Clientes::comboClienteSeleccionado($r->id);
            } elseif ($r->submodulo == 'traerProductos') {
                return Productos::getArticulosProductos($r->id);
            } elseif ($r->submodulo == 'traeArticulos') {
                return ReparacionesCabecera::traeArticulos($r->id);
            } elseif ($r->submodulo == 'asignarRepuestos') {
                return ReparacionesCabecera::asignarRepuestos($r);
            } elseif ($r->submodulo == 'repararNora') {
                return ReparacionesCabecera::repararNora($r);
            } elseif ($r->submodulo == 'asignarCliente') {
                return Clientes::asignarCliente($r->id);
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
