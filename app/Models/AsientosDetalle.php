<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientosDetalle extends Model
{
    protected $table = 'db_detalle_asientos_contable';
    protected $primaryKey  = 'id_asiento_detalle';
    const CREATED_AT = 'created_at_asiento_detalle';
    const UPDATED_AT = 'updated_at_asiento_detalle';

    public static function getMayor($cod)
    {
        return AsientosDetalle::join('db_plan_cuentas', function ($q) {
            $q->on('codigo_cuenta_detalle_asiento', 'codigo_contable_plan')
                ->where('id_empresa_plan', session('idEmpresa'));
        })
            ->join('db_cabecera_asientos_contable', 'id_asiento_cabecera_asiento_detalle', 'id_asiento_cabecera')
            ->where('codigo_cuenta_detalle_asiento', $cod)
            ->get();
    }
}
