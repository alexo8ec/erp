<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReparacionesDetalle extends Model
{
    protected $table = 'db_detalle_nota_reparacion';
    protected $primaryKey  = 'id_detalle_nota';
    const CREATED_AT = 'created_at_detalle_nota';
    const UPDATED_AT = 'updated_at_detalle_nota';

    private static $modelo = 'ReparacionesDetalle';
}
