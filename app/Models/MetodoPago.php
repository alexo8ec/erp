<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'db_metodo_pagos';
    protected $primaryKey  = 'id_metodo_pago';
    const CREATED_AT = 'created_at_metodo_pago';
    const UPDATED_AT = 'updated_at_metodo_pago';

    private static $modelo = 'Metodo_pago';
    public static function getMetodoPagos($id = '')
    {
        if ($id == '')
            return MetodoPago::all();
        else
            return MetodoPago::find($id);
    }
}
