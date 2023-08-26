<?php

namespace App\Http\Controllers;

use App\Models\Ambientes;
use App\Models\Catalogos;
use App\Models\Emisiones;
use App\Models\Empresas;
use App\Models\Establecimientos;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\Paises;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    private $controlador = 'empresas';
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
            if ($r->submodulo == 'empresas') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Lista de empresas';
                $data['paises'] = Paises::getPaises();
                $data['ambientes'] = Ambientes::getAmbientes();
                $data['emisiones'] = Emisiones::getEmisiones();
                $data['tiporegimen'] = Catalogos::traerCatalogo('tiporegimen');
                $data['moneda'] = Catalogos::traerCatalogo('monedas');
                $data['modulos_principales'] = Utilidades::modulosPrincipales();
                $data['contenido'] = $this->controlador . '.view_empresas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'establecimientos') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Lista de establecimientos';
                $data['paises'] = Paises::getPaises();
                $data['contenido'] = $this->controlador . '.view_establecimientos';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'establecimientosjs') {
                $data = Establecimientos::getEstablecimientos();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'verEstablecimiento') {
                return Establecimientos::getEstablecimientos($r->id);
            } elseif ($r->submodulo == 'saveEmpresa') {
                return Empresas::saveEmpresa($r);
            } elseif ($r->submodulo == 'saveEstablecimiento') {
                return Establecimientos::saveEstablecimiento($r);
            } elseif ($r->submodulo == 'empresasjs') {
                $data = Empresas::getEmpresas();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'cambiarEstadoEmpresa') {
                return Empresas::cambiarEstadoEmpresa($r);
            } elseif ($r->submodulo == 'cambiarEstadoEstablecimiento') {
                return Establecimientos::cambiarEstadoEstablecimiento($r);
            } elseif ($r->submodulo == 'verEmpresa') {
                return Empresas::getEmpresas($r->id);
            } elseif ($r->submodulo == 'cambiarempresa') {
                session([
                    'idEmpresa' => '',
                    'estab' => '',
                    'emisi' => ''
                ]);
                return redirect('/');
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
