<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cobros extends Model
{
    protected $table = 'db_cobros';
    protected $primaryKey  = 'id_cobro';
    const CREATED_AT = 'created_at_cobro';
    const UPDATED_AT = 'updated_at_cobro';

    public static function cobroDiarioEstadistico_()
    {
        $arrayCobros = '';
        $cobros = Cobros::selectRaw(
            'GROUP_CONCAT(YEAR(fecha_cobro) limit 1) as Y,
            GROUP_CONCAT(MONTH(fecha_cobro) limit 1) as m,
            GROUP_CONCAT(DAY(fecha_cobro) limit 1) as d,
            SUM(valor_cobro) as total'
        )
            ->where('id_empresa_cobro', session('idEmpresa'))
            ->whereYear('fecha_cobro', session('periodo'))
            ->whereMonth('fecha_cobro', date('m'))
            ->where('estado_cobro', 1)
            ->groupBy(DB::raw('DAY(fecha_cobro)'))
            ->get();
        $number = cal_days_in_month(CAL_GREGORIAN, date('m'), session('periodo'));
        for ($i = 0; $i < $number; $i++) {
            $a = $i;
            $dia = $a + 1;
            if ($cobros->count() > 0) {
                foreach ($cobros as $sal) {
                    if ($sal->d == $dia) {
                        $arrayCobros .= '{"0":"' . $sal->Y . '","1":"' . $sal->m . '","2":"' . (string)$dia . '","3":"' . $sal->total . '"},';
                    } else {
                        $arrayCobros .= '{"0":"' . session('periodo') . '","1":"' . date('m') . '","2":"' . (string)$dia . '","3":"0"},';
                    }
                }
            } else {
                $arrayCobros .= '{"0":"' . session('periodo') . '","1":"' . date('m') . '","2":"' . (string)$dia . '","3":"0"},';
            }
        }
        $arrayCobros = substr($arrayCobros, 0, -1);
        return '[' . $arrayCobros . ']';
        return $arrayCobros;
    }
    public static function cobroDiarioEstadistico()
    {
        $cobros = Cobros::selectRaw('
            YEAR(fecha_cobro) as Y,
            MONTH(fecha_cobro) as m,
            DAY(fecha_cobro) as d,
            SUM(valor_cobro) as total
        ')
            ->where('id_empresa_cobro', session('idEmpresa'))
            ->whereYear('fecha_cobro', session('periodo'))
            ->whereMonth('fecha_cobro', date('m'))
            ->where('estado_cobro', 1)
            ->groupBy('Y', 'm', 'd')
            ->get();

        $arrayCobros = $cobros->map(function ($cobro) {
            return [
                '0' => $cobro->Y,
                '1' => $cobro->m,
                '2' => (string) $cobro->d,
                '3' => $cobro->total,
            ];
        })->toArray();
        echo '<pre>';
        print_r($arrayCobros);
        exit;
        return json_encode($arrayCobros);
    }
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
