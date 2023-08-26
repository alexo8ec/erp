<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprasDetalle extends Model
{
    protected $table = 'db_detalle_compras';
    protected $primaryKey  = 'id_compras_detalle';
    const CREATED_AT = 'created_at_compra_detalle';
    const UPDATED_AT = 'updated_at_compra_detalle';
}
