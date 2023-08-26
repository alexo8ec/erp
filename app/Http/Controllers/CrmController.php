<?php

namespace App\Http\Controllers;

use App\Models\Catalogos;
use App\Models\Clientes;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\Paises;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    private $controlador = 'crm';
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
            if ($r->submodulo == 'clientes') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Lista de clientes';
                $data['tipoIdentificacion'] = Catalogos::traerCatalogo('tipoIdentificacion');
                $data['generos'] = Catalogos::traerCatalogo('generos');
                $data['tipoCliente'] = Catalogos::traerCatalogo('tipocliente');
                $data['paises'] = Paises::getPaises();
                $data['contenido'] = $this->controlador . '.view_clientes';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'clientesjs') {
                $data = Clientes::getClientes();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'estadoCliente') {
                return Clientes::estadoCliente($r);
            } elseif ($r->submodulo == 'getCliente') {
                return Clientes::getClientes($r->id);
            } elseif ($r->submodulo == 'saveCliente') {
                return Clientes::saveCliente($r);
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
