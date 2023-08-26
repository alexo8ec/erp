<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoProducto extends Model
{
    protected $table = 'db_movimiento_productos';
    protected $primaryKey  = 'id_movimiento';
    const CREATED_AT = 'created_at_movimiento';
}
