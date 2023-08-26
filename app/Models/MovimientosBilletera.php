<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class MovimientosBilletera extends Model
{
    protected $table = 'db_movimientos_billetera';
    protected $primaryKey  = 'ingreso_movimiento';
    const CREATED_AT = 'created_at_movimiento';
    const UPDATED_AT = 'updated_at_movimiento';

    private static $modelo = 'MovimientosBilletera';

    public static function getSaldo($r)
    {
        $token = Utilidades::validateToken($r->token);
        if ($token['status'] == true && $token['token'] == true && $token['firma'] == true) {
            try {
                $data = Usuarios::selectRaw(
                    'IFNULL(SUM(ingreso_movimiento),0)-IFNULL(SUM(egreso_movimiento),0) AS saldo'
                )
                    ->leftjoin('db_movimientos_billetera as m', 'db_usuarios.id_usuario', '=', 'm.id_cliente_movimiento')
                    ->where('db_usuarios.id_usuario', $token['id'])
                    ->where('m.estado_movimiento', 1)
                    ->first();
                $arrayResultado['status'] = true;
                $arrayResultado['code'] = 200;
                $arrayResultado['data'] = $data;
            } catch (Exception $e) {
                $arrayResultado['status'] = false;
                $arrayResultado['code'] = 500;
                $arrayResultado['description'] = $e->getMessage();
                $arrayResultado['linea'] = $e->getLine();
            }
        } else {
            $arrayResultado['status'] = false;
            $arrayResultado['code'] = 500;
            $arrayResultado['description'] = 'Error al validar el token';
            $arrayResultado['data'] = ['token' => $token['mnsT'], 'firma' => $token['mnsF']];
        }
        return json_encode($arrayResultado);
    }
    public static function getTransferencias()
    {
        $tipoMovimiento = Catalogos::traerCatalogo('tipomovimientos');

        return MovimientosBilletera::where('id_cliente_movimiento', session('idUsuario'))->get();
    }
}
