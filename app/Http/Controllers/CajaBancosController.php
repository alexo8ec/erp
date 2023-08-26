<?php

namespace App\Http\Controllers;

use App\Models\Cuentas;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class CajaBancosController extends Controller
{
    private $controlador = 'cajabancos';
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
            if ($r->submodulo == 'cajas') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Lista de cajas';
                $data['contenido'] = $this->controlador . '.view_cajas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'saveCaja') {
                return Cuentas::saveCaja($r);
            } elseif ($r->submodulo == 'cajasjs') {
                $data = Cuentas::getCajas();
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
}
