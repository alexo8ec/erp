<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Cuentas;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\MetodoPago;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Productos;
use App\Models\Usuarios;
use App\Models\VentasCabecera;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    private $controlador = 'ventas';
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
            if ($r->submodulo == 'ventas') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Lista de ventas';
                $data['contenido'] = $this->controlador . '.view_ventas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'facturar') {
                $data['titulo_tabla'] = 'Factura';
                $data['formapago'] = MetodoPago::getMetodoPagos();
                $data['cuentas'] = Cuentas::getCajas();
                $data['contenido'] = $this->controlador . '.view_facturar';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'buscarCliente') {
                return Clientes::clientesLineajs($r);
            } elseif ($r->submodulo == 'agregarLinea') {
                return Productos::agregarLinea($r);
            } elseif ($r->submodulo == 'saveVenta') {
                return VentasCabecera::saveVenta($r);
            } elseif ($r->submodulo == 'asignarCliente') {
                return Clientes::asignarCliente($r->id);
            } elseif ($r->submodulo == 'ventasjs') {
                $data = VentasCabecera::getVentas();
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
