<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SubCategorias extends Model
{
    protected $table = 'db_subcategorias';
    protected $primaryKey  = 'id_subcategoria';
    const CREATED_AT = 'created_at_subcategoria';
    const UPDATED_AT = 'updated_at_subcategoria';

    private static $modelo = 'SubCategorias';
    public static function importarsubcategorias()
    {
        $subCategoriaImp = DB::connection('FAC')
            ->table('bm_subcategorias as s')
            ->join('bm_categorias as c', 's.id_categoria', 'c.id_categoria')
            ->join('bm_entidad', 's.id_empresa', 'bm_entidad.id_empresa')
            ->where('s.subcategoria', '!=', '')
            ->where('s.importFact', 0)
            ->limit(300)
            ->get();
        if (count($subCategoriaImp) > 0) {
            DB::beginTransaction();
            try {
                $count = 0;
                foreach ($subCategoriaImp as $row) {
                    $empresa = Empresas::where('ruc_empresa', $row->ruc_empresa)->first();
                    $categoria = Categorias::where('nombre_categoria', $row->categoria)
                        ->where('id_empresa_categoria', $empresa->id_empresa)
                        ->first();
                    $arraySubCategoria = [
                        'nombre_subcategoria' => $row->subcategoria,
                        'id_categoria_subcategoria' => $categoria->id_categoria,
                        'estado_subcategoria' => $row->estado == 'T' ? 1 : 0,
                        'id_empresa_subcategoria' => $empresa->id_empresa,
                        'id_usuario_creacion_subcategoria' => session('idUsuario'),
                        'id_usuario_modificacion_subcategoria' => session('idUsuario'),
                    ];
                    SubCategorias::insert($arraySubCategoria);
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
    public static function traerSubcategoria($r)
    {
        return SubCategorias::where('id_categoria_subcategoria', $r->id)->get();
    }
    public static function getSubCategorias($id = '')
    {
        $subcategorias = SubCategorias::where(function ($q) use ($id) {
            if ($id != '')
                $q->where('id_subcategoria', $id);
        });
        if ($id == '')
            $subcategorias = $subcategorias->get();
        else
            $subcategorias = $subcategorias->first();
    }
}
