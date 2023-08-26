<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Categorias extends Model
{
    protected $table = 'db_categorias';
    protected $primaryKey  = 'id_categoria';
    const CREATED_AT = 'created_at_categoria';
    const UPDATED_AT = 'updated_at_categoria';

    private static $modelo = 'Categorias';
    public static function importarcategorias()
    {
        $categoriaImp = DB::connection('FAC')
            ->table('bm_categorias')
            ->join('bm_entidad', 'bm_categorias.id_empresa', 'bm_entidad.id_empresa')
            ->where('importFact', 0)
            ->limit(300)
            ->get();
        if (count($categoriaImp) > 0) {
            DB::beginTransaction();
            try {
                $count = 0;
                foreach ($categoriaImp as $row) {
                    $empresa = Empresas::where('ruc_empresa', $row->ruc_empresa)->first();
                    $arrayCategoria = [
                        'nombre_categoria' => $row->categoria,
                        'estado_categoria' => $row->estado == 'T' ? 1 : 0,
                        'id_empresa_categoria' => $empresa->id_empresa,
                        'id_usuario_creacion_categoria' => session('idUsuario'),
                        'id_usuario_modificacion_categoria' => session('idUsuario'),
                    ];
                    Categorias::insert($arrayCategoria);
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
    public static function getArticulos()
    {
        return Categorias::join('db_subcategorias', 'id_categoria_subcategoria', 'id_categoria')
            ->where('id_empresa_categoria', session('idEmpresa'))
            ->whereRaw('LOWER(nombre_categoria) like "%articulo%"')
            ->orderBy('nombre_categoria')
            ->get();
    }
    public static function getCategorias($id = '')
    {
        $categoria = Categorias::where(function ($q) use ($id) {
            if ($id != '')
                $q->where('id_categoria', $id);
        })
            ->where('id_empresa_categoria', session('idEmpresa'));
        if ($id == '')
            $categoria = $categoria->get();
        else
            $categoria = $categoria->first();
        return $categoria;
    }
}
