<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Modulos extends Model
{
    protected $table = 'db_modulos';
    protected $primaryKey  = 'id_modulo';
    const CREATED_AT = 'created_at_modulo';
    const UPDATED_AT = 'updated_at_modulo';

    private static $modelo = 'Modulos';

    public function submodulos()
    {
        return $this->hasMany(SubModulos::class, 'id_modulo_submodulo', 'id_modulo')->orderBy('orden_submodulo');
    }
    public static function getModulos($id = '')
    {
        if ($id == '')
            return Modulos::where('estado_modulo', 1)->orderBy('orden_modulo')->get();
        else
            return Modulos::find($id);
    }
}
