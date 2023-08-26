<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProductosTelefonicos extends Model
{
    protected $table = 'db_productos_telefonicos';
    protected $primaryKey  = 'id_producto';
    const CREATED_AT = 'created_at_producto';
    const UPDATED_AT = 'updated_at_producto';

    private static $modelo = 'ProductosTelefonicos';

    public static function guardarRecarga($r, $id)
    {
        $arrayRespuesta = [];
        DB::beginTransaction();
        try {
            $tipoMovimiento = Catalogos::where('codigo_catalogo', 'recargatelefonica')->first();
            $valores = explode('|', $r->valores);
            $egreso = 0;
            if ($valores[1] == 'null') {
                $egreso = $valores[2];
            } else {
                $egreso = $r->valor;
            }
            $arrayMovimiento = [
                'uuid_movimiento' => Uuid::uuid1(),
                'ingreso_movimiento' => 0,
                'egreso_movimiento' => $egreso,
                'fecha_movimiento' => date('Y-m-d H:i:s'),
                'id_cliente_movimiento' => $id,
                'observacion_movimiento' => $r->observacion,
                'id_tipo_movimiento' => $tipoMovimiento->id_catalogo,
                'tipo_uso_movimiento' => $r->paynow,
                'numero_telefono' => $r->numero,
                'id_usuario_creacion_movimiento' => $id,
                'id_usuario_modificacion_movimiento' => $id
            ];
            $idMov = MovimientosBilletera::insertGetId($arrayMovimiento);
            DB::commit();
            $arrayRespuesta = [
                'status' => true,
                'id_movimiento' => $idMov,
                'codigo_unico' => $arrayMovimiento['uuid_movimiento'],
                'fecha' => $arrayMovimiento['fecha_movimiento']
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $arrayRespuesta = [
                'status' => false,
                'code' => 500,
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
        }
        return $arrayRespuesta;
    }
    public static function saveRecarga($r)
    {
        $datos = json_encode($r->datos);
        $datos = json_decode($datos);
        $arrayResultado = [];
        $token = Utilidades::validateToken($r->token);
        if ($token['status'] == true && $token['token'] == true && $token['firma'] == true) {
            try {
                $data = ProductosTelefonicos::guardarRecarga($datos, $token['id']);
                if ($data['status'] == true) {
                    $arrayResultado['status'] = true;
                    $arrayResultado['code'] = 200;
                    $arrayResultado['description'] = 'Recarga guardada correctamente';
                    $arrayResultado['data'] = $data;
                } else {
                    $arrayResultado['status'] = false;
                    $arrayResultado['code'] = 500;
                    $arrayResultado['description'] = 'Error al guardar la recarga';
                }
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
}
