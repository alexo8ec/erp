<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentasDetalle extends Model
{
    protected $table = 'db_detalle_ventas';
    protected $primaryKey  = 'id_venta_detalle';
    const CREATED_AT = 'created_at_venta_detalle';
    const UPDATED_AT = 'updated_at_venta_detalle';
}
