<?php

namespace App\Http\Controllers;

use App\Models\AsientosCabecera;
use App\Models\AsientosDetalle;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\PlanCuenta;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class ContabilidadController extends Controller
{
    private $controlador = 'contabilidad';
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
            if ($r->submodulo == 'copiarPlan') {
                return PlanCuenta::copiarPlan();
            } elseif ($r->submodulo == 'asientos') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Asientos';
                $data['plan'] = PlanCuenta::getPlan();
                $data['contenido'] = $this->controlador . '.view_asientos';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'estadofinanciero') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Balance Financiero';
                $data['activos'] = PlanCuenta::estadoFinanciero(1);
                $data['pasivos'] = PlanCuenta::estadoFinanciero(2);
                $data['patrimonio'] = PlanCuenta::estadoFinanciero(3);
                $data['ingresos'] = PlanCuenta::estadoFinanciero(4);
                $data['costos'] = PlanCuenta::estadoFinanciero('5.01');
                $data['gastos'] = PlanCuenta::estadoFinanciero('5.02');
                $data['contenido'] = $this->controlador . '.view_financiero';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'estadoderesultado') {
                $data['titulo_tabla'] = 'Balance Financiero';
                $data['tabla'] = true;
                $data['ingresos'] = PlanCuenta::estadoFinanciero(4);
                $data['costos'] = PlanCuenta::estadoFinanciero('5.01');
                $data['gastos'] = PlanCuenta::estadoFinanciero('5.02');
                $data['contenido'] = $this->controlador . '.view_resultado';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'plandecuentas') {
                $data['titulo_tabla'] = 'Plan de cuentas';
                $data['tabla'] = true;
                $data['clase'] = PlanCuenta::getClasePlan();
                $data['contenido'] = $this->controlador . '.view_plan_cuentas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'getGrupoPlan') {
                return PlanCuenta::getGrupoPlan($r);
            } elseif ($r->submodulo == 'getCuentaPlan') {
                return PlanCuenta::getCuentaPlan($r);
            } elseif ($r->submodulo == 'getAuxiliarPlan') {
                return PlanCuenta::getAuxiliar($r);
            } elseif ($r->submodulo == 'savePlanCuenta') {
                return PlanCuenta::savePlanCuenta($r);
            } elseif ($r->submodulo == 'saveAsientoManual') {
                return AsientosCabecera::saveAsientoManual($r);
            } elseif ($r->submodulo == 'libromayor') {
                $data['titulo_tabla'] = 'Libro mayor';
                $data['tabla'] = true;
                $data['contenido'] = $this->controlador . '.view_libro_mayor';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'getidPlan') {
                return PlanCuenta::getidPlan($r->cod_plan);
            } elseif ($r->submodulo == 'verMayor') {
                $data = AsientosDetalle::getMayor($r->cod);
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'asientosjs') {
                $data = AsientosCabecera::getAsientos();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'plandecuentasjs') {
                $data = PlanCuenta::getPlan();
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
