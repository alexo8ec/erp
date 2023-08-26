<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincias extends Model
{
    protected $table = 'db_provincias';
    protected $primaryKey  = 'id_provincia';
    const CREATED_AT = 'created_at_provincias';
    const UPDATED_AT = 'updated_at_provincias';

    public static function getProvincias($id)
    {
       return Provincias::where('id_pais_provincia', $id)->orderBy('nombre_provincia')->get();
    }
}
