<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Cuentas extends Model
{
    private static $modelo = 'Cuentas';

    protected $table = 'db_cuentas';
    protected $primaryKey  = 'id_cuenta';
    const CREATED_AT = 'created_at_cuenta';
    const UPDATED_AT = 'updated_at_cuenta';

    public static function getCajas($id = '')
    {
        if ($id == '')
            return Cuentas::where('id_empresa_cuenta', session('idEmpresa'))->get();
        else
            return Cuentas::find($id);
    }
    public static function saveCaja($r)
    {
        $count = 0;
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            if ($datos['id_cuenta'] != '') {
                $cont++;
                $cat = Cuentas::where('id_cuenta', $datos['id_cuenta'])->first(['uuid_cuenta']);
                if ($cat == '') {
                    $datos['uuid_cuenta'] = Uuid::uuid1();
                }
                Cuentas::where('id_cuenta',  $datos['id_cuenta'])
                    ->update($datos);
                $r->c = 'Cuentas';
                $r->s = 'saveCaja';
                $r->d = $origin['d'];
                $r->m = Cuentas::$modelo;
                $r->o = 'Se actualizo la caja No.: ' . $datos['id_cuenta'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
                $count++;
            } else {
                $cont++;
                $secuencialCuenta = Cuentas::setCodigo();
                $codigoContable = '1.01.311.' . $secuencialCuenta;
                $dCodigoContable = explode('.', $codigoContable);
                $arrayPlan = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => $codigoContable,
                    'nombre_cuenta_plan' => $datos['nombre_cuenta'] . ' ' . $datos['numero_cuenta'] . ' ' . $datos['tipo_cuenta'],
                    'clase_contable_plan' => $dCodigoContable[0],
                    'grupo_contable_plan' => (int)$dCodigoContable[1],
                    'cuenta_contable_plan' => (int)$dCodigoContable[2],
                    'auxiliar_contable_plan' => (int)$dCodigoContable[3],
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPlan = PlanCuenta::insertGetId($arrayPlan);
                $datos['codigo_contable_cuenta'] = $codigoContable;
                $datos['secuencial_cuenta'] = $secuencialCuenta;
                $datos['id_plan_cuenta'] = $idPlan;
                $datos['uuid_cuenta'] = Uuid::uuid1();
                Cuentas::insert($datos);
                $r->c = 'Cuentas';
                $r->s = 'saveCaja';
                $r->d = $origin['d'];
                $r->m = Cuentas::$modelo;
                $r->o = 'Se creo una nueva caja';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(), 'linea' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function setCodigo()
    {
        $max = Cuentas::where('id_empresa_cuenta', session('idEmpresa'))->max('secuencial_cuenta');
        $max += 1;
        return str_pad($max, 6, 0, STR_PAD_LEFT);
    }
}
