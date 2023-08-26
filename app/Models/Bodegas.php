<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Bodegas extends Model
{
    protected $table = 'db_bodegas';
    protected $primaryKey  = 'id_bodega';
    const CREATED_AT = 'created_at_bodega';
    const UPDATED_AT = 'updated_at_bodega';

    private static $modelo = 'Bodegas';
    public static function saveBodega($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            if ($datos['id_bodega'] != '') {
                $cont++;
                $cat = Bodegas::where('id_bodega', $datos['id_bodega'])->first();
                if ($cat == '') {
                    $datos['uuid_bodega'] = Uuid::uuid1();
                }
                $datos['id_usuario_modificacion_bodega'] = session('idUsuario');
                Bodegas::where('id_bodega',  $datos['id_bodega'])
                    ->update($datos);
                $r->c = 'Bodegas';
                $r->s = 'saveBodega';
                $r->d = $origin['d'];
                $r->m = Bodegas::$modelo;
                $r->o = 'Se actualizo la bodega No.: ' . $datos['id_bodega'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_bodega'] = Uuid::uuid1();
                $datos['id_usuario_modificacion_bodega'] = session('idUsuario');
                Bodegas::insert($datos);
                $r->c = 'Bodegas';
                $r->s = 'saveBodega';
                $r->d = $origin['d'];
                $r->m = Bodegas::$modelo;
                $r->o = 'Se creo una nueva bodega';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
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
    public static function getBodegas($id = '')
    {
        return Bodegas::join('db_usuarios','id_usuario_creacion_bodega','id_usuario')
        ->join('db_establecimientos','id_establecimiento_bodega','id_establecimiento')
        ->where('id_empresa_bodega', session('idEmpresa'))
            ->get([
                'id_bodega',
                'nombre_bodega',
                'establecimiento as establecimiento_bodega',
                'created_at_bodega',
                'usuario as usuario_bodega',
                'estado_bodega'
            ]);
    }
}
