<?php

namespace App\Http\Controllers;

use App\Models\Billeteras;
use App\Models\Catalogos;
use App\Models\Clientes;
use App\Models\Cobros;
use App\Models\ComprasCabecera;
use App\Models\Empresas;
use App\Models\Establecimientos;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\PlanCuenta;
use App\Models\Proveedores;
use App\Models\Usuarios;
use App\Models\VentasCabecera;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $controlador = 'admin';
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
        $tipoUsuario = Catalogos::where('id_catalogo', $data['usuario']->id_tipo_uso_usuario)->first();
        $data['tipoUso'] = $tipoUsuario;
        if (session('idEmpresa') != '' && $tipoUsuario->codigo_catalogo == 'empresas') {
            $data['empresa'] = Empresas::getEmpresas(session('idEmpresa'));
            $planCuenta = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))->first();
            if ($planCuenta == '') {
                $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                $data['tabla'] = true;
                $data['plan'] = PlanCuenta::getPlan();
                $data['contenido'] = 'contabilidad.view_plan_cuentas';
            }
        }
        if ($r->submodulo == 'cambiarClave') {
            $mensaje = Usuarios::cambiarClaveUsuario($r);
            return redirect('/admin')->with(['message' => $mensaje]);
        } elseif ($data['usuario']->clave_cambiada_usuario == 0) {
            $data['title'] = 'Cambiar contraseña | ' . $info->nombre_info . ' v' . $info->mayor_info . '.' . $info->menor_info;
            return view($this->controlador . '.view_cambiar_clave', $data);
        } elseif ($r->submodulo == 'logout') {
            Usuarios::updateLogin(0);
            session(['idUsuario' => '', 'periodo' => '', 'idEmpresa' => '', 'estab' => '', 'emisi' => '', 'tipo_establecimiento' => '']);
            return redirect('/');
        } elseif ($r->submodulo == 'sesion_empresa') {
            session(['idEmpresa' => $r->id]);
        } elseif ($r->submodulo == 'session_establecimiento') {
            $esta = Establecimientos::find($r->id);
            session(['estab' => $esta->establecimiento, 'emisi' => $esta->emision_establecimiento, 'tipo_establecimiento' => $esta->tipo_establecimiento]);
        } elseif ($r->submodulo == 'monthCharges') {
            return VentasCabecera::ventasDiariasEstadistico();
        } else {
            if ($tipoUsuario->codigo_catalogo == 'empresas') {
                if (session('idEmpresa') != '') {
                    if (session('estab') == '') {
                        if (count($data['empresa']->establecimientos) > 1) {
                            $data['establecimientos'] = $data['empresa']->establecimientos;
                            $data['title'] = 'Seleccion de establecimiento | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                            $data['contenido'] = 'layout.view_seleccionar_establecimiento';
                        } else {
                            if (count($data['empresa']->establecimientos) == 0)
                                return redirect('empresas/establecimientos');
                            else {
                                session(['estab' => $data['empresa']->establecimientos[0]->establecimiento, 'emisi' => $data['empresa']->establecimientos[0]->emision_establecimiento, 'tipo_establecimiento' => $data['empresa']->establecimientos[0]->tipo_establecimiento]);
                                $data['title'] = 'Bienvenid@ | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                                $data['contenido'] = 'layout.view_contenido';
                            }
                        }
                    } else {
                        $data['totalVentas'] = VentasCabecera::totalVentas();
                        $data['totalCompras'] = ComprasCabecera::totalCompras();
                        $data['totalClientes'] = Clientes::totalClientes();
                        $data['totalProveedores'] = Proveedores::totalProveedores();
                        $data['ventas_mensuales'] = VentasCabecera::ventasDiariasEstadistico();
                        //$data['cobros_mensuales'] = Cobros::cobroDiarioEstadistico();
                        /*$dato['total_ventas_mensual'] = Sales::where('status_sale', '1')->where('id_company_sale', $r->id_company)->whereYear('date_issue_sale', $r->periodo)->whereMonth('date_issue_sale',  date('m'))->sum('net_sale');
                        $dato['total_cobros_mensual'] = Charges::where('status_charge', '1')->where('id_company_charge', $r->id_company)->whereYear('date_issue_charge', $r->periodo)->whereMonth('date_issue_charge',  date('m'))->sum('value_charge');*/
                        $data['title'] = 'Bienvenid@ | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                        $data['contenido'] = 'layout.view_contenido';
                    }
                } else {
                    $data['title'] = 'Seleccion de empresa | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                    $data['empresas'] = Empresas::getEmpresas('', 'seleccionar', $data['usuario']->id_empresas_usuario);
                    $data['contenido'] = 'layout.view_seleccionar_empresa';
                }
            } elseif ($tipoUsuario->codigo_catalogo == 'billeteras') {
                $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
                $data['estadistica'] = true;
                $data['saldos'] = Billeteras::getSaldos();
                $data['contenido'] = $this->controlador . '.view_inicio_billetera';
            }
            config(['data' => $data]);
            return view('layout.view_child');
        }
    }
}
