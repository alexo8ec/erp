<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoImpuesto extends Model
{
    protected $table = 'db_tipo_impuestos';
    protected $primaryKey  = 'id_tipo_impuestos';
    const CREATED_AT = 'created_at_tipo_impuesto';
    const UPDATED_AT = 'updated_at_tipo_impuesto';

    public static function getImpuestos($tipo)
    {
        if ($tipo == 'iva')
            return TipoImpuesto::where('codigo_impuesto_tipo_impuesto', 2)
                ->where('tipo_impuesto', '<>', 3)
                ->orWhere('id_tipo_impuesto', '-1')
                ->get([
                    'id_tipo_impuesto as id_catalogo',
                    'descripcion_tipo_impuesto as nombre_catalogo'
                ]);
        elseif ($tipo == 'ice')
            return TipoImpuesto::where('tipo_impuesto', '<>', 3)
                ->where('codigo_impuesto_tipo_impuesto', 3)
                ->orWhere('id_tipo_impuesto', '-1')
                ->get([
                    'id_tipo_impuesto as id_catalogo',
                    'descripcion_tipo_impuesto as nombre_catalogo'
                ]);
        elseif ($tipo == 'irbpnr')
            return TipoImpuesto::where('codigo_impuesto_tipo_impuesto', 5)
                ->orWhere('id_tipo_impuesto', '-1')
                ->get([
                    'id_tipo_impuesto as id_catalogo',
                    'descripcion_tipo_impuesto as nombre_catalogo'
                ]);
    }
}
