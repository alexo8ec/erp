<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use App\Models\VentasCabecera;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    private $controlador = 'estadisticas';
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
                $data['estadistica'] = true;
                $data['titulo'] = 'clientes';
                $data['anios'] = Clientes::aniosClientes();
                $data['contenido'] = $this->controlador . '.view_estadisticas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'ventas') {
                $data['estadistica'] = true;
                $data['titulo'] = 'ventas';
                $data['anios'] = VentasCabecera::aniosVentas();
                $data['contenido'] = $this->controlador . '.view_estadisticas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'clientesMes') {
                echo Clientes::clientesMes($r->anio);
            } elseif ($r->submodulo == 'ventasMes') {
                echo VentasCabecera::ventasMes($r->anio);
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
