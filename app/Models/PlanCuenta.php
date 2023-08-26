<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PlanCuenta extends Model
{
    protected $table = 'db_plan_cuentas';
    protected $primaryKey  = 'id_plan';
    const CREATED_AT = 'created_at_plan';
    const UPDATED_AT = 'updated_at_plan';

    private static $modelo = 'PlanCuentas';
    public static function getImpuestoCausado($ingreso, $costo, $gasto)
    {
        $impuestoCausado = 0;
        $empresa = Empresas::getEmpresas(session('idEmpresa'));
        if ($empresa->tipoRegimen == 'Rimpe Emprendedor') {
            $datosRimpe = TiposRimpe::where('anio_impuesto', session('periodo'))
                ->where('inicio_impuesto', '<=', $ingreso)
                ->where('final_impuesto', '>=', $ingreso)
                ->first();
            if ($datosRimpe != '') {
                if ($datosRimpe->inicio_impuesto == 0) {
                    $impuestoCausado = $datosRimpe->fraccion_basica_impuesto;
                }
            }
        }
        return $impuestoCausado;
    }
    public static function getGastosNoDeducibles()
    {
        return 0;
    }
    public static function getidPlan($cod)
    {
        $cod = substr($cod, 0, 8);
        return PlanCuenta::where('codigo_contable_plan', $cod)
            ->where('id_empresa_plan', session('idEmpresa'))
            ->first(['id_plan']);
    }
    public static function traerCodigoContable($id)
    {
        $codigo = '';
        $codigo = PlanCuenta::find($id);
        if ($codigo != '')
            $codigo = $codigo->codigo_contable_plan;
        return $codigo;
    }
    public static function resultados()
    {
        $fecha_desde = date('Y-m-d', strtotime(session('periodo') . '-01-01'));
        $fecha_hasta = date('Y-m-d', strtotime(session('periodo') + 1 . '-01-01'));
        return PlanCuenta::selectRaw(' GROUP_CONCAT(codigo_contable_plan limit 1) as codigo_contable_plan,
        GROUP_CONCAT(nombre_cuenta_plan limit 1) as nombre_cuenta_plan,
        SUM(IFNULL(debe_asiento_detalle,0)) as debe,
        SUM(IFNULL(haber_asiento_detalle,0)) AS haber,
        IF(SUBSTRING(codigo_contable_plan,1,2)=\'4.\',\'INGRESO\',IF(SUBSTRING(codigo_contable_plan,1,4)=\'5.01\',\'COSTO\',\'GASTO\')) AS tipo')
            ->leftjoin('db_detalle_asientos_contable as da', function ($q) use ($fecha_desde, $fecha_hasta) {
                $q->on('codigo_contable_plan', 'codigo_cuenta_detalle_asiento')
                    ->where('fecha_asiento_detalle', '>=', $fecha_desde)
                    ->where('fecha_asiento_detalle', '<', $fecha_hasta);;
            })
            ->where('id_empresa_plan', session('idEmpresa'))
            ->where(function ($q) {
                $q->where('codigo_contable_plan', 'LIKE', '4%')
                    ->orWhere('codigo_contable_plan', 'LIKE', '5%');
            })
            ->whereRaw('LENGTH(codigo_contable_plan)=8')
            ->groupBy('tipo')
            ->orderBy('codigo_contable_plan')
            ->get();
    }
    public static function estadoFinanciero($n)
    {
        $idEmpresa = session('idEmpresa');
        $fecha_desde = date('Y-m-d', strtotime(session('periodo') . '-01-01'));
        $fecha_hasta = date('Y-m-d', strtotime(session('periodo') + 1 . '-01-01'));
        $queryDesde = '';
        if ($n != 1 && $n != 2 && $n != 3)
            $queryDesde = "AND fecha_asiento_detalle >= '$fecha_desde'";

        $sql = "SELECT  GROUP_CONCAT(b.id_empresa_plan LIMIT 1) AS id_empresa_plan,
        GROUP_CONCAT(b.id_plan LIMIT 1) AS id_plan,
        GROUP_CONCAT(b.codigo_contable_plan LIMIT 1) AS codigo_contable_plan,
        GROUP_CONCAT(b.nombre_cuenta_plan LIMIT 1) AS nombre_cuenta_plan,
        SUM(IFNULL(b.debe,0)) AS debe,
        SUM(IFNULL(b.haber,0)) AS haber,
        IF(SUBSTRING(b.codigo_contable_plan,1,1)='1','ACTIVOS',IF(SUBSTRING(b.codigo_contable_plan,1,1)='2','PASIVOS','PATRIMONIO')) AS tipo FROM ((SELECT 
        GROUP_CONCAT(id_empresa_plan LIMIT 1) AS id_empresa_plan,
        GROUP_CONCAT(id_plan LIMIT 1) AS id_plan,
        GROUP_CONCAT(codigo_contable_plan LIMIT 1) AS codigo_contable_plan,
        GROUP_CONCAT(nombre_cuenta_plan LIMIT 1) AS nombre_cuenta_plan,
        SUM(IFNULL(debe_asiento_detalle,0)) AS debe,
        SUM(IFNULL(haber_asiento_detalle,0)) AS haber,
        IF(SUBSTRING(codigo_contable_plan,1,1)='1','ACTIVOS',IF(SUBSTRING(codigo_contable_plan,1,1)='2','PASIVOS','PATRIMONIO')) AS tipo
        FROM db_detalle_asientos_contable 
        INNER JOIN db_cabecera_asientos_contable ON id_asiento_cabecera_asiento_detalle = id_asiento_cabecera 
        LEFT JOIN db_plan_cuentas ON db_detalle_asientos_contable.codigo_cuenta_detalle_asiento = codigo_contable_plan AND id_empresa_asiento_cabecera = id_empresa_plan 
        WHERE fecha_asiento_detalle < '$fecha_hasta'
        $queryDesde
        AND codigo_cuenta_detalle_asiento LIKE '$n%' 
        GROUP BY codigo_contable_plan) 
        UNION ALL 
        (SELECT id_empresa_plan,id_plan,codigo_contable_plan,nombre_cuenta_plan,0 AS debe, 0 AS haber,IF(SUBSTRING(codigo_contable_plan,1,1)='1','ACTIVOS',IF(SUBSTRING(codigo_contable_plan,1,1)='2','PASIVOS','PATRIMONIO')) AS tipo FROM db_plan_cuentas 
        WHERE codigo_contable_plan LIKE '$n%')";
        if ($n == 3) {
            $sql .= "UNION ALL
            (
                SELECT GROUP_CONCAT(id_empresa_plan LIMIT 1) AS id_empresa_plan,
                GROUP_CONCAT(id_plan LIMIT 1) AS id_plan,
                IF(IFNULL(SUM(haber_asiento_detalle),0)-IFNULL(SUM(debe_asiento_detalle),0)>0,'3.01.517.000001','3.01.519.000001') AS codigo_contable_plan,
                IF(IFNULL(SUM(haber_asiento_detalle),0)-IFNULL(SUM(debe_asiento_detalle),0)>0,'(-) UTILIDAD DEL EJERCICIO ACTUAL','(-) PERDIDA DEL EJERCICIO ACTUAL') AS nombre_cuenta_plan,
                0 AS debe,
                IFNULL(SUM(haber_asiento_detalle),0)-IFNULL(SUM(debe_asiento_detalle),0) AS haber,
                'PATRIMONIO' AS tipo
                FROM db_detalle_asientos_contable 
                INNER JOIN db_cabecera_asientos_contable ON id_asiento_cabecera_asiento_detalle = id_asiento_cabecera 
                LEFT JOIN db_plan_cuentas ON db_detalle_asientos_contable.codigo_cuenta_detalle_asiento = codigo_contable_plan AND id_empresa_asiento_cabecera = id_empresa_plan 
                WHERE fecha_asiento_detalle < '$fecha_hasta' 
                AND (codigo_cuenta_detalle_asiento LIKE '4%' OR codigo_cuenta_detalle_asiento LIKE '5.01%' OR codigo_cuenta_detalle_asiento LIKE '5.02%')
                
            )";
        }
        $sql .= ") AS b 
        WHERE b.id_empresa_plan=$idEmpresa
        GROUP BY b.codigo_contable_plan
        ORDER BY b.codigo_contable_plan";
        /*if ($n == 3) {
            echo $sql;
            exit;
        }*/
        return DB::select($sql);
    }
    public static function estadoResultado()
    {
        $fecha_desde = date('Y-m-d', strtotime(session('periodo') . '-01-01'));
        $fecha_hasta = date('Y-m-d', strtotime(session('periodo') + 1 . '-01-01'));
        return PlanCuenta::selectRaw(' GROUP_CONCAT(codigo_contable_plan limit 1) as codigo_contable_plan,
        GROUP_CONCAT(nombre_cuenta_plan limit 1) as nombre_cuenta_plan,
        SUM(IFNULL(debe_asiento_detalle,0)) as debe,
        SUM(IFNULL(haber_asiento_detalle,0)) AS haber,
        IF(SUBSTRING(codigo_contable_plan,1,2)=\'4.\',\'INGRESO\',IF(SUBSTRING(codigo_contable_plan,1,4)=\'5.01\',\'COSTO\',\'GASTO\')) AS tipo')
            ->leftjoin('db_detalle_asientos_contable as da', function ($q) use ($fecha_desde, $fecha_hasta) {
                $q->on('codigo_contable_plan', 'codigo_cuenta_detalle_asiento')
                    ->where('fecha_asiento_detalle', '>=', $fecha_desde)
                    ->where('fecha_asiento_detalle', '<', $fecha_hasta);;
            })
            ->where('id_empresa_plan', session('idEmpresa'))
            ->where(function ($q) {
                $q->where('codigo_contable_plan', 'LIKE', '4%')
                    ->orWhere('codigo_contable_plan', 'LIKE', '5%');
            })
            ->whereRaw('LENGTH(codigo_contable_plan)=8')
            ->groupBy('codigo_contable_plan')
            ->orderBy('codigo_contable_plan')
            ->get();
    }
    public static function traerCuenta($idPlan)
    {
        return PlanCuenta::find($idPlan);
    }
    public static function crearCuentaContable($dato)
    {
        echo '<pre>';
        print_r($dato);
        exit;
    }
    public static function copiarPlan()
    {
        $cont = 0;
        DB::beginTransaction();
        try {
            $plan = PlanCuenta::where('id_empresa_plan', 1)
                ->whereNull('uuid_plan')
                ->get();
            if (count($plan) > 0) {
                foreach ($plan as $row) {
                    $arrayPlan = [
                        'uuid_plan' => Uuid::uuid1(),
                        'codigo_contable_plan' => $row->codigo_contable_plan,
                        'nombre_cuenta_plan' => $row->nombre_cuenta_plan,
                        'clase_contable_plan' => $row->clase_contable_plan,
                        'grupo_contable_plan' => $row->grupo_contable_plan,
                        'cuenta_contable_plan' => $row->cuenta_contable_plan,
                        'valor_cuenta_plan' => 0,
                        'estado_plan' => 1,
                        'id_empresa_plan' => session('idEmpresa'),
                        'id_usuario_creacion_plan' => session('idUsuario'),
                        'id_usuario_modificacion_plan' => session('idUsuario')
                    ];
                    PlanCuenta::insert($arrayPlan);
                    $cont++;
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function setIdPlanCuenta($producto, $tipo)
    {
        $id = 0;
        $plan = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))->where('nombre_cuenta_plan', $producto)->first();
        if ($plan == '') {
            if ($tipo == 'inventario') {
                $codigo_contable_plan = '1.01.327';
            } elseif ($tipo == 'activo') {
                $codigo_contable_plan = '1.01.327';
            } elseif ($tipo == 'gasto') {
                $codigo_contable_plan = '5.02';
            }
        } else {
            $id = $plan->id_plan;
        }
        return $id;
    }
    public static function verplan($r)
    {
        $tipo = $r->tipo;
        return PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
            ->where(function ($q) use ($tipo) {
                if ($tipo == 'activo')
                    $q->where('clase_contable_plan', 1);
                elseif ($tipo == 'pasivo')
                    $q->where('clase_contable_plan', 2);
                elseif ($tipo == 'ingreso')
                    $q->where('clase_contable_plan', 4);
                elseif ($tipo == 'costo') {
                    $q->where('clase_contable_plan', 5);
                    $q->where('grupo_contable_plan', 1);
                } elseif ($tipo == 'gasto') {
                    $q->where('clase_contable_plan', 5);
                    $q->where('grupo_contable_plan', 2);
                }
            })
            ->where(DB::raw('LENGTH(codigo_contable_plan)'), 8)
            ->get();
    }
    public static function getAuxiliar($r)
    {
        $numCuenta = 1;
        if ($r->cuenta == 0) {
            $numCuenta = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->where('clase_contable_plan', $r->clase)
                ->where('grupo_contable_plan', $r->grupo)
                ->where('cuenta_contable_plan', '!=', 0)
                ->whereNull('auxiliar_contable_plan')
                ->orderBy('cuenta_contable_plan', 'DESC')
                ->first(['cuenta_contable_plan']);
            if ($numCuenta != '') {
                $numCuenta = $numCuenta->cuenta_contable_plan + 1;
            }
            return $numCuenta;
        } else {
            $numAuxiliar = 1;
            DB::enableQueryLog();
            $auxiliar = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->where('clase_contable_plan', $r->clase)
                ->where('grupo_contable_plan', $r->grupo)
                ->where('cuenta_contable_plan', $r->cuenta)
                ->orderBy('auxiliar_contable_plan', 'desc')
                ->first(['auxiliar_contable_plan']);
            if ($auxiliar != '') {
                $numAuxiliar = $auxiliar->auxiliar_contable_plan + 1;
            }
            return $numAuxiliar;
        }
    }
    public static function getCuentaPlan($r)
    {
        if ($r->grupo == 0) {
            $grupo = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->where('clase_contable_plan', $r->clase)
                ->whereNull('auxiliar_contable_plan')
                ->get();
            return $grupo;
        } else {
            return  PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->where('clase_contable_plan', $r->clase)
                ->where('grupo_contable_plan', $r->grupo)
                ->where('cuenta_contable_plan', '!=', 0)
                ->whereNull('auxiliar_contable_plan')
                ->get();
        }
    }
    public static function getGrupoPlan($r)
    {
        return  PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
            ->where('clase_contable_plan', $r->clase)
            ->where('grupo_contable_plan', '!=', 0)
            ->where('cuenta_contable_plan', 0)
            ->get();
    }
    public static function savePlanCuenta($r)
    {
        $cont = 0;
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $cuentaContable = $r->cuenta_contable_plan != 0 ? $r->cuenta_contable_plan : (isset($r->cuenta_contable_plan_) ? $r->cuenta_contable_plan_ : 0);
            $arrayCuenta = [
                'uuid_plan' => Uuid::uuid1(),
                'codigo_contable_plan' => $r->codigo_contable_plan,
                'clase_contable_plan' => $r->clase_contable_plan,
                'grupo_contable_plan' => $r->grupo_contable_plan,
                'cuenta_contable_plan' => $cuentaContable,
                'auxiliar_contable_plan' => $r->auxiliar_contable_plan,
                'nombre_cuenta_plan' => $r->nombre_cuenta_plan,
                'id_usuario_creacion_plan' => session('idUsuario'),
                'id_usuario_modificacion_plan' => session('idUsuario'),
                'id_empresa_plan' => session('idEmpresa'),
                'created_at_plan' => date('Y-m-d H:i:s'),
            ];
            PlanCuenta::insert($arrayCuenta);
            $cont++;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $r->c = 'plancuenta';
            $r->s = 'savePlanCuenta';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = PlanCuenta::$modelo;
            $r->o = 'Error al guardar la cuenta: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function getCodigoGrupoContable($clase)
    {
        $numGrupo = 1;
        $clase = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
            ->where('clase_contable_plan', $clase)
            ->latest()
            ->first(['grupo_contable_plan']);
        if ($clase != '') {
            $numGrupo = $clase->grupo_contable_plan + 1;
        }
        return $numGrupo;
    }
    public static function getCodigoClaseContable()
    {
        $numClase = 1;
        $clase = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
            ->latest()
            ->first(['clase_contable_plan']);
        if ($clase != '') {
            $numClase = $clase->clase_contable_plan + 1;
        }
        return $numClase;
    }
    public static function getClasePlan($id = '')
    {
        if ($id == '') {
            return PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->whereNull('auxiliar_contable_plan')
                ->where('cuenta_contable_plan', 0)
                ->where('grupo_contable_plan', 0)
                ->get();
        } else {
        }
    }
    public static function getPlan($id = '')
    {
        if ($id == '') {
            return PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->orderBy('codigo_contable_plan')
                ->get();
        } else {
            return PlanCuenta::where('id_plan', $id)->first();
        }
    }
}
