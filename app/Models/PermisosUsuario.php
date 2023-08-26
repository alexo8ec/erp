<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PermisosUsuario extends Model
{
    protected $table = 'db_permisos_usuario';
    protected $primaryKey  = 'id_permiso';
    const CREATED_AT = 'created_at_permiso';
    const UPDATED_AT = 'updated_at_permiso';

    private static $modelo = 'PermisosUsuario';

    public static function getPermisos($id)
    {
        return PermisosUsuario::where('id_usuario_permiso', $id)
            ->where('id_empresa_permiso', session('idEmpresa'))
            ->first();
    }
    public static function savePermisos($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $datos['id_submodulos_permiso'] = json_encode($datos['id_submodulos_permiso']);
            $datos['id_empresa_permiso'] = session('idEmpresa');
            if ($datos['id_permiso'] != '') {
                $cont++;
                $cat = PermisosUsuario::where('id_permiso', $datos['id_permiso'])
                    ->first(['uuid_permiso']);
                if ($cat != '') {
                    $datos['uuid_permiso'] = Uuid::uuid1();
                }
                PermisosUsuario::where('id_permiso', $datos['id_permiso'])
                    ->update($datos);
                $r->c = 'opciones';
                $r->s = 'savePermisos';
                $r->d = $origin['d'];
                $r->m = PermisosUsuario::$modelo;
                $r->o = 'Se actualiaron los permisos del usuario No.: ' . $datos['id_usuario_permiso'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_permiso'] = Uuid::uuid1();
                PermisosUsuario::insert($datos);
                $r->c = 'opciones';
                $r->s = 'savePermisos';
                $r->d = $origin['d'];
                $r->m = PermisosUsuario::$modelo;
                $r->o = 'Se creo los permisos del usuario';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $r->c = 'opciones';
            $r->s = 'savePermisos';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = PermisosUsuario::$modelo;
            $r->o = 'Error al guardarlos permisos: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
}
