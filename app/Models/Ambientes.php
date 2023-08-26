<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambientes extends Model
{
    protected $table = 'db_ambientes';
    protected $primaryKey  = 'id_ambiente';
    const CREATED_AT = 'created_at_ambiente';
    const UPDATED_AT = 'updated_at_ambiente';

    public static function getAmbientes($id='')
    {
        return Ambientes::get();
    }
}
