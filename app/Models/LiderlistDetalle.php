<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LiderlistDetalle extends Model
{
    protected $table = 'db_detalle_liderlist';
    protected $primaryKey  = 'id_liderlist_detalle';
    const CREATED_AT = 'created_at_liderlist_detalle';
    const UPDATED_AT = 'updated_at_liderlist_detalle';

    private static $modelo = 'LiderlistDetalle';
    public static function getLiderlistDetalle($id)
    {
        return LiderlistDetalle::join('db_catalogos as color' ,'db_detalle_liderlist.id_color_liderlist_detalle','color.id_catalogo')
        ->join('db_categorias as categoria' ,'db_detalle_liderlist.id_categoria_liderlist_detalle','categoria.id_categoria')
        ->join('db_subcategorias as subcategoria' ,'db_detalle_liderlist.id_subcategoria_liderlist_detalle','subcategoria.id_subcategoria')
        ->where('id_cabecera_liderlist_detalle',$id)
        ->get([
            'id_liderlist_detalle',
            'id_cabecera_liderlist_detalle',
            'catalogo_liderlist_detalle',
            'pagina_liderlist_detalle',
            'referencia_liderlist_detalle',
            'nombre_liderlist_detalle',
            'categoria.nombre_categoria',
            'subcategoria.nombre_subcategoria',
            'subcategoria.nombre_subcategoria',
            'color.nombre_catalogo as color',
            'tallas_liderlist_detalle',
            'costo_liderlist_detalle',
            'precio_liderlist_detalle',
            'estado_liderlist_detalle',
        ]);
    }
    public static function saveDetalleLiderlist($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $datos['id_empresa_liderlist_detalle']=session('idEmpresa');
            $datos['tallas_liderlist_detalle']=json_encode($datos['tallas_liderlist_detalle']);
            $da_=explode('|',$datos['referencia_liderlist_detalle']);
            $datos['referencia_liderlist_detalle']=$da_[0];
            if ($datos['id_liderlist_detalle'] != '') {
                $cont++;
                $cat = LiderlistDetalle::where('id_liderlist_detalle', $datos['id_liderlist_detalle'])->first(['uuid_liderlist_detalle']);
                if ($cat == '') {
                    $datos['uuid_liderlist_detalle'] = Uuid::uuid1();
                    $datos['id_usuario_modificacion_liderlist_detalle'] = session('idUsuario');
                }
                LiderlistDetalle::where('id_liderlist_detalle', $datos['id_liderlist_detalle'])->update($datos);
                $r->c = 'CatalogoCampana';
                $r->s = 'saveDetalleLiderlist';
                $r->d = $origin['d'];
                $r->m = LiderlistDetalle::$modelo;
                $r->o = 'Se actualizo el liderlist_detalle No.: ' . $datos['id_liderlist_detalle'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_liderlist_detalle'] = Uuid::uuid1();
                $datos['created_at_liderlist_detalle'] = date('Y-m-d H:i:s');
                $datos['id_usuario_creacion_liderlist_detalle'] = session('idUsuario');
                $datos['id_usuario_modificacion_liderlist_detalle'] = session('idUsuario');
                DB::enableQueryLog();
                LiderlistDetalle::insert($datos);
                $r->c = 'CatalogoCampana';
                $r->s = 'saveDetalleLiderlist';
                $r->d = $origin['d'];
                $r->m = LiderlistDetalle::$modelo;
                $r->o = 'Se creo un nuevo liderlist_detalle';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(),'line'=>$e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
}
