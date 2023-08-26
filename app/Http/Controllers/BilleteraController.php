<?php

namespace App\Http\Controllers;

use App\Models\Billeteras;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\MovimientoProducto;
use App\Models\MovimientosBilletera;
use App\Models\PermisosUsuario;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class BilleteraController extends Controller
{
    private $controlador = 'billetera';
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
            if ($r->submodulo == 'crearcuentas') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Crear cuentas de billeteras';
                $data['contenido'] = $this->controlador . '.view_crear_cuentas';
            } elseif ($r->submodulo == 'crearcuentasjs') {
                $data = Billeteras::getBilletera();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'numControl') {
                return Billeteras::numControl();
            } elseif ($r->submodulo == 'saveBilletera') {
                return Billeteras::saveBilletera($r);
            } elseif ($r->submodulo == 'asignarUsuarioBilletera') {
                return Billeteras::asignarUsuarioBilletera($r->id);
            } elseif ($r->submodulo == 'transferencias') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Transferencias';
                $data['contenido'] = $this->controlador . '.view_transferencias';
            } elseif ($r->submodulo == 'transferenciasjs') {
                $data = MovimientosBilletera::getTransferencias();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'validarCuenta') {
                return Billeteras::validarCuenta($r);
            } else {
                return view('errors/401', $data);
            }
            config(['data' => $data]);
            return view('layout.view_child');
        }
    }
}
