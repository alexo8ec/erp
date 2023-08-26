<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ReparacionesCabecera extends Model
{
    protected $table = 'db_cabecera_nota_reparacion';
    protected $primaryKey  = 'id_nota';
    const CREATED_AT = 'created_at_nota';
    const UPDATED_AT = 'updated_at_nota';

    private static $modelo = 'ReparacionesCabecera';
    public static function repararNora($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        $cont = 0;
        DB::beginTransaction();
        try {
            $arrayNota = [
                'reparado_nota' => 1,
                'fecha_reparado_nota' => date('Y-m-d H:i:s'),
            ];
            ReparacionesCabecera::where('id_nota', $datos['id_nota'])->update($arrayNota);
            $arrayLogNota = [
                'id_nota_log' => $datos['id_nota'],
                'obj_nota_log' => json_encode($datos),
                'id_empresa_log' => session('idEmpresa'),
                'tipo_log' => 'Modificacion',
                'detalle' => json_encode($datos),
                'id_usuario_creacion_log' => session('idUsuario'),
                'id_usuario_modificacion_log' => session('idUsuario'),
            ];
            LogNotas::insert($arrayLogNota);
            $cont++;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'modelo' => 'ReparacionesCabecera',
                'try' => 'Principal',
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'ok|Datos guardados correctamente...'
            ];
            return json_encode($result);
        }
    }
    public static function asignarRepuestos($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        $cont = 0;
        DB::beginTransaction();
        try {
            if (count($datos['id_producto']) > 0) {
                for ($i = 0; $i < count($datos['id_producto']); $i++) {
                    $arrayMovimiento = [
                        'uuid_movimiento' => Uuid::uuid1(),
                        'fecha_movimiento' => date('Y-m-d H:i:s'),
                        'id_producto_movimiento' => $datos['id_producto'][$i],
                        'ingreso_movimiento' => 0,
                        'egreso_movimiento' => $datos['cantidad'][$i],
                        'motivo_movimiento' => 'NOTAREPARACION',
                        'id_nota_reparacion_movimiento' => $datos['id_nota'],
                        'id_bodega_movimiento' => $datos['id_bodega'],
                        'id_empresa_movimiento' => session('idEmpresa'),
                        'id_usuario_creacion_movimiento' => $datos['id_tecnico'],
                        'id_usuario_modificacion_movimiento' => session('idUsuario'),
                        'establecimiento_movimiento' => session('estab'),
                        'emision_movimiento' => session('emisi')
                    ];
                    MovimientoProducto::insert($arrayMovimiento);
                }
            }
            $arrayNota = [
                'repuesto_nota' => 1,
                'fecha_repuesto_nota' => date('Y-m-d H:i:s'),
            ];
            ReparacionesCabecera::where('id_nota', $datos['id_nota'])->update($arrayNota);
            $arrayLogNota = [
                'id_nota_log' => $datos['id_nota'],
                'obj_nota_log' => json_encode($datos),
                'id_empresa_log' => session('idEmpresa'),
                'tipo_log' => 'Modificacion',
                'detalle' => json_encode($datos),
                'id_usuario_creacion_log' => session('idUsuario'),
                'id_usuario_modificacion_log' => session('idUsuario'),
            ];
            LogNotas::insert($arrayLogNota);
            $cont++;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'modelo' => 'ReparacionesCabecera',
                'try' => 'Principal',
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'ok|Datos guardados correctamente...'
            ];
            return json_encode($result);
        }
    }
    public static function traeArticulos($id)
    {
        return ReparacionesDetalle::join('db_productos', 'id_producto_detalle_nota', 'id_producto')
            ->where('id_cabecera_detalle_nota', $id)
            ->get();
    }
    public static function getListar($id = '')
    {
        if ($id == '') {
            return ReparacionesCabecera::selectRaw(
                'GROUP_CONCAT(id_nota limit 1) as id_nota,
                GROUP_CONCAT(numero_nota limit 1) as numero_nota,
                GROUP_CONCAT(nombre_subcategoria limit 1) as nombre_subcategoria,
                GROUP_CONCAT(modelo_nota limit 1) as modelo_nota,
                GROUP_CONCAT(estado_nota limit 1) as estado_nota,
                GROUP_CONCAT(tipoNota.nombre_catalogo limit 1) as tipo_nota,
                GROUP_CONCAT(presupuesto_nota limit 1) as presupuesto_nota,
                GROUP_CONCAT(repuesto_nota limit 1) as repuesto_nota,
                GROUP_CONCAT(reparado_nota limit 1) as reparado_nota,
                GROUP_CONCAT(retirado_nota limit 1) as retirado_nota,
                GROUP_CONCAT(usuario limit 1) as usuario,
                GROUP_CONCAT(fecha_nota limit 1) as fecha_nota,
                GROUP_CONCAT(fecha_entrega_nota limit 1) as fecha_entrega_nota,
                GROUP_CONCAT(IFNULL(CONCAT(nombre_persona," ",apellido_persona),"") limit 1) as cliente,
                GROUP_CONCAT(CONCAT(telefono_persona,"-",celular_persona) limit 1) as telefono,
                IFNULL(SUM(valor_cobro),0) AS abono_nota,
                IFNULL(sum(total_detalle_nota),0) as total_nota'

            )
                ->leftjoin('db_subcategorias', 'id_subcategoria', 'id_artefacto_nota')
                ->join('db_catalogos as tipoNota', 'id_tipo_nota', 'tipoNota.id_catalogo')
                ->join('db_usuarios', 'id_usuario_creacion_nota', 'id_usuario')
                ->join('db_clientes', 'id_cliente_nota', 'id_cliente')
                ->join('db_personas', 'id_persona_cliente', 'id_persona')
                ->leftjoin('db_detalle_nota_reparacion', 'id_nota', 'id_cabecera_detalle_nota')
                ->leftjoin('db_cobros', function ($q) {
                    $q->on('id_cliente', 'id_cliente_cobro')
                        ->on('id_nota', 'id_nota_cabecera_cobro');
                })
                ->where('id_empresa_nota', session('idEmpresa'))
                ->where('establecimiento_nota', session('estab'))
                ->groupBy('numero_nota', 'establecimiento_nota')
                ->get();
        } else {
            return ReparacionesCabecera::find($id);
        }
    }
    public static function saveNotaReparacion($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $tipoLog = '';
            $serie = explode('|', $datos['punto_venta']);
            $dCliente = explode('|', $datos['id_cliente']);
            $arrayNotaCabecera = [
                'numero_nota' => $datos['numero_nota'],
                'establecimiento_nota' => $serie[0],
                'emision_nota' => $serie[1],
                'fecha_nota' => $datos['fecha_nota'],
                'id_cliente_nota' => $dCliente[0],
                'fecha_entrega_nota' => $datos['fecha_entrega'],
                'id_tipo_nota' => $datos['id_tipo_nota'],
                'nota_del_cliente_nota' => $datos['observacion_del_cliente'],
                'nota_al_cliente_nota' => $datos['observacion_al_cliente'],
                'instrucciones_taller_nota' => $datos['instrucciones_al_taller'],
                'id_artefacto_nota' => $datos['id_articulo'],
                'id_marca_nota' => $datos['id_marca'],
                'modelo_nota' => $datos['modelo'] == 'agregar' ? $datos['modelo_'] : $datos['modelo'],
                'accesorio_nota' => $datos['accesorio'],
                'nhr_nota' => $datos['nhr'],
                'lugar_compra_nota' => $datos['lugar_compra'],
                'factura_compra_nota' => $datos['factura_compra'],
                'fecha_compra_nota' => $datos['fecha_compra'],
                'documento_nota' => $datos['doc_entregado'],
                'reposicion_nota' => $datos['repos'],
                'autorizado_nota' => $datos['autorizado'],
                'id_empresa_nota' => session('idEmpresa'),
                'id_usuario_creacion_nota' => $datos['id_usuario_creacion_nota'],
                'id_usuario_modificacion_nota' => session('idUsuario'),
            ];
            if ($datos['id_nota'] == '') {
                $tipoLog = 'Creacion';
                $idNotaCabecera = ReparacionesCabecera::insertGetId($arrayNotaCabecera);
            } else {
                $tipoLog = 'Modificacion';
                ReparacionesCabecera::where('id_nota', $datos['id_nota'])->update($arrayNotaCabecera);
                $idNotaCabecera = $datos['id_nota'];
            }
            if (isset($datos['codigo']) && count($datos['codigo']) > 0) {
                ReparacionesDetalle::where('id_cabecera_detalle_nota', $datos['id_nota'])->delete();
                $detalleNota = [];
                for ($i = 0; $i < count($datos['codigo']); $i++) {
                    $dproducto = explode('|', $datos['producto'][$i]);
                    $arrayNotaDetalle = [
                        'id_cabecera_detalle_nota' => $idNotaCabecera,
                        'id_producto_detalle_nota' => $dproducto[0],
                        'cantidad_detalle_nota' => $datos['cantidad'][$i],
                        'precio_detalle_nota' => $datos['precio'][$i],
                        'descuento_detalle_nota' => $datos['descuento'][$i],
                        'iva_detalle_nota' => $datos['iva'][$i],
                        'porcentaje_detalle_nota' => $dproducto[6],
                        'total_detalle_nota' => $datos['total'][$i],
                    ];
                    ReparacionesDetalle::insert($arrayNotaDetalle);
                    array_push($detalleNota, $arrayNotaDetalle);
                }
                $arrayNotaCabecera = ['cabecera' => $arrayNotaCabecera, 'detalle' => $detalleNota];
                $nota = ReparacionesCabecera::find($datos['id_nota']);
                if ($nota == '') {
                    $arrayNotaPresupuesto = [
                        'presupuesto_nota' => 1,
                        'fecha_presupuesto_nota' => date('Y-m-d H:i:s')
                    ];
                } else {
                    $arrayNotaPresupuesto = [
                        'presupuesto_nota' => 1,
                        'fecha_presupuesto_nota' => $nota->fecha_presupuesto_nota != '' ? $nota->fecha_presupuesto_nota : date('Y-m-d H:i:s')
                    ];
                }
                ReparacionesCabecera::where('id_nota', $datos['id_nota'])->update($arrayNotaPresupuesto);
            }
            $arrayLog = [
                'id_nota_log' => $idNotaCabecera,
                'obj_nota_log' => json_encode($r->input()),
                'id_empresa_log' => session('idEmpresa'),
                'id_usuario_creacion_log' => session('idUsuario'),
                'id_usuario_modificacion_log' => session('idUsuario'),
                'tipo_log' => $tipoLog,
                'detalle' => json_encode($arrayNotaCabecera)
            ];
            LogNotas::insert($arrayLog);
            $cont++;
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function getNumNotaReparacion()
    {
        $max = ReparacionesCabecera::where('id_empresa_nota', session('idEmpresa'))
            ->where('establecimiento_nota', session('estab'))
            ->max('numero_nota');
        $max += 1;
        return $max;
    }
}
