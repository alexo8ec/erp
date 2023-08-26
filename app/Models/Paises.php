<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $table = 'db_paises';
    protected $primaryKey  = 'id_pais';
    const CREATED_AT = 'created_at_pais';
    const UPDATED_AT = 'updated_at_pais';

    public static function getPaises($id = '')
    {
        if ($id == '') {
            return Paises::all([
                'id_pais',
                'nombre_pais'
            ]);
        } else {
            return Paises::where('id_pais', $id)->first();
        }
    }
}
