<?php

namespace App\Http\Controllers;

use App\Models\Catalogos;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
    private $controlador = 'configuraciones';
    public function index(Request $r)
    {
        if (session('idUsuario') == '')
            return redirect('/');
        config(['data' => []]);
        $info = Info::getInfo();
        $data['info'] = $info;
        $data['controlador'] = $this->controlador;
        $data['submodulo'] = $r->submodulo;
        $data['usuario'] = Usuarios::getUsuario(session('idUsuario'));
        $data['modulos'] = Modulos::getModulos();
        $data['permisos'] = PermisosUsuario::getPermisos(session('idUsuario'));
        $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
        if (session('idEmpresa') != '')
            $data['empresa'] = Empresas::getEmpresas(session('idEmpresa'));
        if ($r->submodulo == 'catalogo') {
            $data['tabla'] = true;
            $data['titulo_tabla'] = 'Catálogo';
            $data['contenido'] = $this->controlador . '.view_catalogo';
            config(['data' => $data]);
            return view('layout.view_child');
        } elseif ($r->submodulo == 'subcatalogo') {
            $data['tabla'] = true;
            $data['id'] = $r->id;
            $data['titulo_tabla'] = 'Catálogo: ' . $r->n;
            $data['contenido'] = $this->controlador . '.view_subcatalogo';
            config(['data' => $data]);
            return view('layout.view_child');
        } elseif ($r->submodulo == 'usuarios') {
            $data['tabla'] = true;
            $data['titulo_tabla'] = 'Lista de usuarios';
            $data['empresas'] = Empresas::getEmpresas();
            $data['contenido'] = $this->controlador . '.view_usuarios';
            config(['data' => $data]);
            return view('layout.view_child');
        } elseif ($r->submodulo == 'asignarEmpresas') {
            return Empresas::asignarEmpresas($r);
        } elseif ($r->submodulo == 'saveCatalogo') {
            return Catalogos::saveCatalogo($r);
        } elseif ($r->submodulo == 'usuariosjs') {
            $data = Usuarios::getUsuario();
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            return json_encode($results);
        } elseif ($r->submodulo == 'subcatalogojs') {
            $data = Catalogos::getSubCatalogos($r->id);
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            return json_encode($results);
        } elseif ($r->submodulo == 'catalogojs') {
            $data = Catalogos::getCatalogos();
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            return json_encode($results);
        } else {
            return view('errors/401', $data);
        }
    }
}
