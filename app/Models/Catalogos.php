<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;
use Ramsey\Uuid\Uuid;

class Catalogos extends Model
{
    protected $table = 'db_catalogos';
    protected $primaryKey  = 'id_catalogo';
    const CREATED_AT = 'created_at_catalogo';
    const UPDATED_AT = 'updated_at_catalogo';

    private static $modelo = 'Catalogos';
    public static function importarsubcategorias()
    {
        $subCategoriaImp = DB::connection('FAC')
            ->table('bm_subcategorias as s')
            ->selectRaw(
                'GROUP_CONCAT(s.id_subcategoria limit 1) as id_subcategoria,
                GROUP_CONCAT(s.subcategoria limit 1) as subcategoria,
                GROUP_CONCAT(c.categoria limit 1) as categoria'
            )
            ->join('bm_categorias as c', 's.id_categoria', 'c.id_categoria')
            ->where('s.subcategoria', '!=', '')
            ->where('s.importFact', 0)
            ->groupBy('s.subcategoria')
            ->limit(300)
            ->get();
        if (count($subCategoriaImp) > 0) {
            DB::beginTransaction();
            try {
                $count = 0;
                foreach ($subCategoriaImp as $row) {
                    $categoria = Catalogos::where('codigo_catalogo', 'like', '%' . Utilidades::sanear_string_tildes(strtolower($row->categoria)) . '%')->first(['id_catalogo']);
                    $arrayCatalogo = [
                        'uuid_catalogo' => Uuid::uuid1(),
                        'nombre_catalogo' => $row->subcategoria,
                        'codigo_catalogo' => Utilidades::sanear_string_tildes(strtolower($row->subcategoria)),
                        'id_catalogo_pertenece' => $categoria->id_catalogo,
                        'estado_catalogo' => 1,
                        'id_usuario_creacion_catalogo' => session('idUsuario'),
                        'id_usuario_modificacion_catalogo' => session('idUsuario'),
                    ];
                    Catalogos::insert($arrayCatalogo);
                    $count++;
                    echo $count . '<br/>';
                    DB::connection('FAC')
                        ->table('bm_subcategorias')
                        ->where('id_subcategoria', $row->id_subcategoria)
                        ->update([
                            'importFact' => 1
                        ]);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                echo 'Error: ' . $e->getMessage() . '<br/>';
                echo 'Línea: ' . $e->getLine() . '<br/>';
            }
        }
    }
    public static function importarcategorias()
    {
        $categoriaImp = DB::connection('FAC')
            ->table('bm_categorias')
            ->selectRaw(
                'GROUP_CONCAT(id_categoria limit 1) as id_categoria,
                GROUP_CONCAT(categoria limit 1) as categoria'
            )
            ->groupBy('categoria')
            ->where('importFact', 0)
            ->limit(300)
            ->get();
        if (count($categoriaImp) > 0) {
            DB::beginTransaction();
            try {
                $count = 0;
                foreach ($categoriaImp as $row) {
                    $arrayCatalogo = [
                        'uuid_catalogo' => Uuid::uuid1(),
                        'nombre_catalogo' => $row->categoria,
                        'codigo_catalogo' => Utilidades::sanear_string_tildes(strtolower($row->categoria)),
                        'id_catalogo_pertenece' => 45,
                        'estado_catalogo' => 1,
                        'id_usuario_creacion_catalogo' => session('idUsuario'),
                        'id_usuario_modificacion_catalogo' => session('idUsuario'),
                    ];

                    Catalogos::insert($arrayCatalogo);

                    $count++;
                    echo $count . '<br/>';
                    DB::connection('FAC')
                        ->table('bm_categorias')
                        ->where('id_categoria', $row->id_categoria)
                        ->update([
                            'importFact' => 1
                        ]);
                }
                DB::commit();
                if ($count == 0)
                    echo 'No hay categorias nuevas';
            } catch (Exception $e) {
                DB::rollBack();
                echo 'Error: ' . $e->getMessage() . '<br/>';
                echo 'Línea: ' . $e->getLine() . '<br/>';
            }
        }
    }
    public static function getCatalogosPrincipal()
    {
        return Catalogos::whereNull('id_catalogo_pertenece')
            ->orderBy('codigo_catalogo')
            ->get();
    }
    public static function saveSubCatalogo($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['id']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            if ($datos['id_catalogo'] != '' && $datos['id_catalogo_pertenece'] != '') {
                $cont++;
                $cat = Catalogos::where('id_catalogo', $datos['id_catalogo'])->first(['uuid_catalogo']);
                if ($cat == '') {
                    $datos['uuid_catalogo'] = Uuid::uuid1();
                    $datos['id_usuario_modificacion_catalogo'] = session('idUsuario');
                }
                Catalogos::where('id_catalogo', $datos['id_catalogo'])->update($datos);
                $r->c = 'configuraciones';
                $r->s = 'saveCatalogo';
                $r->d = $origin['d'];
                $r->m = Catalogos::$modelo;
                $r->o = 'Se actualizo el catalogo No.: ' . $datos['id_catalogo_pertenece'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $dcatalogo = Catalogos::where('codigo_catalogo', $datos['codigo_catalogo'])
                    ->where('nombre_catalogo', $datos['nombre_catalogo'])
                    ->first();
                if ($dcatalogo == '') {
                    $cont++;
                    $datos['uuid_catalogo'] = Uuid::uuid1();
                    $datos['created_at_catalogo'] = date('Y-m-d H:i:s');
                    Catalogos::insert($datos);
                    $r->c = 'configuraciones';
                    $r->s = 'saveCatalogo';
                    $r->d = $origin['d'];
                    $r->m = Catalogos::$modelo;
                    $r->o = 'Se creo un nuevo catalogo';
                    Auditorias::saveAuditoria($r, DB::getQueryLog());
                } else {
                    $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|Ya existe un dato igual guardado en la base');
                    return json_encode($result);
                }
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
    public static function saveCatalogo($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $isExiste = Catalogos::where('codigo_catalogo', $datos['codigo_catalogo'])->first();
            if ($isExiste != '') {
                $result = array('code' => 200, 'state' => false, 'data' => '', 'message' => 'no|El código que intenta ingresar ya existe...');
                return json_encode($result);
            } elseif ($datos['id_catalogo'] != '') {
                $cont++;
                $cat = Catalogos::where('id_catalogo', $datos['id_catalogo'])->first(['uuid_catalogo']);
                if ($cat == '') {
                    $datos['uuid_catalogo'] = Uuid::uuid1();
                    $datos['id_usuario_modificacion_catalogo'] = session('idUsuario');
                }
                Catalogos::where('id_catalogo', $datos['id_catalogo'])->update($datos);
                $r->c = 'configuraciones';
                $r->s = 'saveCatalogo';
                $r->d = $origin['d'];
                $r->m = Catalogos::$modelo;
                $r->o = 'Se actualizo el catalogo No.: ' . $datos['id_catalogo'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_catalogo'] = Uuid::uuid1();
                $datos['created_at_catalogo'] = date('Y-m-d H:i:s');
                $datos['id_usuario_creacion_catalogo']=session('idUsuario');
                $datos['id_usuario_modificacion_catalogo']=session('idUsuario');
                Catalogos::insert($datos);
                $r->c = 'configuraciones';
                $r->s = 'saveCatalogo';
                $r->d = $origin['d'];
                $r->m = Catalogos::$modelo;
                $r->o = 'Se creo un nuevo catalogo';
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
    public static function getCatalogos($id = '')
    {
        if ($id == '') {
            return Catalogos::where('id_catalogo_pertenece', null)->get([
                'id_catalogo',
                'codigo_catalogo',
                'nombre_catalogo',
                'valor_catalogo',
                'orden_catalogo',
                'created_at_catalogo',
                'updated_at_catalogo',
                'estado_catalogo'
            ]);
        } else {
            return Catalogos::where('id_catalogo', $id)->first();
        }
    }
    public static function getSubCatalogos($id, $ids = '')
    {
        if ($ids == '') {
            return Catalogos::join('db_catalogos as c', 'db_catalogos.id_catalogo_pertenece', '=', 'c.id_catalogo')
                ->where('db_catalogos.id_catalogo_pertenece', $id)->get([
                    'db_catalogos.id_catalogo',
                    'db_catalogos.codigo_catalogo',
                    'db_catalogos.nombre_catalogo',
                    'db_catalogos.valor_catalogo',
                    'db_catalogos.created_at_catalogo',
                    'db_catalogos.updated_at_catalogo',
                    'db_catalogos.estado_catalogo',
                    'db_catalogos.orden_catalogo',
                    'c.nombre_catalogo as catalogo'
                ]);
        } else {
            return Catalogos::where('id_catalogo', $ids)->first();
        }
    }
    public static function traerCatalogo($tipo = '', $orden = '')
    {
        $catalogo = Catalogos::where('codigo_catalogo', $tipo)->first();
        if ($catalogo != '') {
            if ($orden == '')
                return Catalogos::where('id_catalogo_pertenece', $catalogo->id_catalogo)->orderBy('nombre_catalogo')->get();
            else
                return Catalogos::where('id_catalogo_pertenece', $catalogo->id_catalogo)->orderBy('orden_catalogo', $orden)->get();
        } else {
            return ['mensaje' => 'Lo siento, no tienes un catalogo definido para este menú, solicite al administrador que ingrese a: configuraciones->catálgo y los cree... '];
        }
    }
}
