<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModulos extends Model
{
    protected $table = 'db_submodulos';
    protected $primaryKey  = 'id_submodulos';

    public static function getSubModulos($id)
    {
        return SubModulos::leftjoin('db_modulos as m', 'db_submodulos.id_modulo_submodulo', '=', 'm.id_modulo')
            ->where('id_modulo_submodulo', $id)
            ->get([
                'id_submodulo',
                'funcion_submodulo',
                'nombre_submodulo',
                'm.nombre_modulo',
                'orden_submodulo',
                'created_at_submodulo',
                'updated_at_submodulo'
            ]);
    }
}
