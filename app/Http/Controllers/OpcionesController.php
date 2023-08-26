<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Productos;
use App\Models\Usuarios;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class OpcionesController extends Controller
{
    private $controlador = 'opciones';
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
            if ($r->submodulo == 'permisos') {
                /*if (!Utilidades::verificarPermisos($r))
                    return view('errors/401', $data);*/
                $data['titulo_tabla'] = 'Opciones';
                $data['usuarios'] = Usuarios::getUsuario();
                $data['contenido'] = $this->controlador . '.view_permisos';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'getPermisos') {
                return PermisosUsuario::getPermisos($r->id);
            } elseif ($r->submodulo == 'savePermisos') {
                return PermisosUsuario::savePermisos($r);
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
