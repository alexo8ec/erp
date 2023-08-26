<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobros extends Model
{
    protected $table = 'db_cobros';
    protected $primaryKey  = 'id_cobro';
    const CREATED_AT = 'created_at_cobro';
    const UPDATED_AT = 'updated_at_cobro';

    public static function getCobros($id)
    {
        return Cobros::join('db_clientes as cl', 'db_cobros.id_cliente_cobro', 'cl.id_cliente')
            ->join('db_personas as p', 'cl.id_persona_cliente', '=', 'p.id_persona')
            ->join('db_cabecera_ventas as cv', 'db_cobros.id_venta_cabecera_cobro', '=', 'cv.id_venta_cabecera')
            ->join('db_metodo_pagos as m', 'cv.id_forma_pago_venta_cabecera', '=', 'm.id_metodo_pago')
            ->where('id_venta_cabecera_cobro', $id)
            ->get();
    }
    public static function setSecuencialCobro($idEmpresa = '')
    {
        if ($idEmpresa == '')
            $max = Cobros::where('id_empresa_cobro', session('idEmpresa'))->max('secuencial_cobro');
        else
            $max = Cobros::where('id_empresa_cobro', $idEmpresa)->max('secuencial_cobro');
        $max += 1;
        return $max;
    }
}
