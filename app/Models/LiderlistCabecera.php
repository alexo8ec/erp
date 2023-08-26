<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LiderlistCabecera extends Model
{
    protected $table = 'db_cabecera_liderlist';
    protected $primaryKey  = 'id_liderlist_cabecera';
    const CREATED_AT = 'created_at_liderlist_cabecera';
    const UPDATED_AT = 'updated_at_liderlist_cabecera';

    private static $modelo = 'LiderlistCabecera';
    public static function getLiderlist($id = '')
    {
        $liderlist = LiderlistCabecera::where(function ($q) use ($id) {
            if ($id != '')
                $q->where('id_liderlist_cabecera', $id);
            else
                $q->where('id_empresa_liderlist_cabecera', session('idEmpresa'));
        });
        if ($id == '')
            $liderlist = $liderlist->get();
        else
            $liderlist = $liderlist->first();
        return $liderlist;
    }
    public static function saveLiderlist($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $datos['id_empresa_liderlist_cabecera']=session('idEmpresa');
            if ($datos['id_liderlist_cabecera'] != '') {
                $cont++;
                $cat = LiderlistCabecera::where('id_liderlist_cabecera', $datos['id_liderlist_cabecera'])->first(['uuid_liderlist_cabecera']);
                if ($cat == '') {
                    $datos['uuid_liderlist_cabecera'] = Uuid::uuid1();
                    $datos['id_usuario_modificacion_liderlist_cabecera'] = session('idUsuario');
                }
                LiderlistCabecera::where('id_liderlist_cabecera', $datos['id_liderlist_cabecera'])->update($datos);
                $r->c = 'CatalogoCampana';
                $r->s = 'saveLiderlist';
                $r->d = $origin['d'];
                $r->m = LiderlistCabecera::$modelo;
                $r->o = 'Se actualizo el liderlist_cabecera No.: ' . $datos['id_liderlist_cabecera'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_liderlist_cabecera'] = Uuid::uuid1();
                $datos['created_at_liderlist_cabecera'] = date('Y-m-d H:i:s');
                $datos['id_usuario_creacion_liderlist_cabecera'] = session('idUsuario');
                $datos['id_usuario_modificacion_liderlist_cabecera'] = session('idUsuario');
                LiderlistCabecera::insert($datos);
                $r->c = 'CatalogoCampana';
                $r->s = 'saveLiderlist';
                $r->d = $origin['d'];
                $r->m = LiderlistCabecera::$modelo;
                $r->o = 'Se creo un nuevo liderlist_cabecera';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
}
