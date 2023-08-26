<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emisiones extends Model
{
    protected $table = 'db_emisiones';
    protected $primaryKey  = 'id_emision';
    const CREATED_AT = 'created_at_emision';
    const UPDATED_AT = 'updated_at_emision';

    public static function getEmisiones($id = '')
    {
        return Emisiones::orderBy('id_emision')->get();
    }
}
