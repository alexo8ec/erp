<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudades extends Model
{
    protected $table = 'db_ciudades';
    protected $primaryKey  = 'id_ciudad';
    const CREATED_AT = 'created_at_ciudad';
    const UPDATED_AT = 'updated_at_ciudad';

    public static function getCiudades($id)
    {
        return Ciudades::where('id_provincia_ciudad', $id)
            ->orderBy('nombre_ciudad')
            ->get();
    }
}
