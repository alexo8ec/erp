<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DispositivosExterno extends Model
{
    private static $modelo = 'DispositivosExternos';
    public static function getDispositivos()
    {
        return DB::connection('mysql_mo')->table('bswa_dispositivo')->get();
    }
    public static function cambiarEstadoDispositivo($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $arrayDispositivo = [
                'estado_dispositivo' => $r->estado
            ];
            DB::connection('mysql_mo')->table('bswa_dispositivo')->where('id_dispositivo', $r->id)->update($arrayDispositivo);
            $r->c = 'Dispositivos';
            $r->s = 'cambiarEstadoDispositivo';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = DispositivosExterno::$modelo;
            $r->o = 'Se actualizo correctamente el dispositivo';
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            DB::commit();
            $cont++;
        } catch (Exception $e) {
            DB::rollback();
            $r->c = 'Dispositivos';
            $r->s = 'cambiarEstadoDispositivo';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = DispositivosExterno::$modelo;
            $r->o = 'Error al actualizar el dispositivo: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'ok|Datos guardados correctamente...'
            ];
            return json_encode($result);
        }
    }
}
