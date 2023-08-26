<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;
use Ramsey\Uuid\Uuid;

class AsientosCabecera extends Model
{
    protected $table = 'db_cabecera_asientos_contable';
    protected $primaryKey  = 'id_asiento_cabecera';
    const CREATED_AT = 'created_at_asiento_cabecera';
    const UPDATED_AT = 'updated_at_asiento_cabecera';

    private static $modelo = 'AsientoCabecera';
    public static function saveAsientoManual($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            if ($datos['id_asiento_cabecera'] != '') {
                $cont++;
                $asiento = AsientosCabecera::where('id_asiento_cabecera', $datos['id_asiento_cabecera'])->first(['uuid_asiento_cabecera']);
                if ($asiento == '') {
                    $datos['uuid_asiento_cabecera'] = Uuid::uuid1();
                }
                $datos['id_usuario_asiento_cabecera'] = session('idUsuario');
                AsientosCabecera::where('id_asiento_cabecera',  $datos['id_asiento_cabecera'])
                    ->update($datos);
                $r->c = 'AsientosCabecera';
                $r->s = 'saveAsientoManual';
                $r->d = $origin['d'];
                $r->m = AsientosCabecera::$modelo;
                $r->o = 'Se actualizo el asiento No.: ' . $datos['id_asiento_cabecera'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $arrayAsientoCabecera = [
                    'uuid_asiento_cabecera' => Uuid::uuid1(),
                    'fecha_asiento_cabecera' => $datos['fecha_asiento_cabecera'],
                    'debe_asiento_cabecera' => $datos['debe_asiento_cabecera'],
                    'haber_asiento_cabecera' => $datos['haber_asiento_cabecera'],
                    'glosa_asiento_cabecera' => $datos['glosa_asiento_cabecera'],
                    'estado_asiento_cabecera' => 1,
                    'id_empresa_asiento_cabecera' => session('idEmpresa'),
                    'id_usuario_creacion_asiento_cabecera' => session('idUsuario'),
                    'id_usuario_modificacion_asiento_cabecera' => session('idUsuario'),
                ];

                $idAsiento = AsientosCabecera::insertGetId($arrayAsientoCabecera);
                for ($i = 0; $i < count($datos['codigo_contable']); $i++) {
                    $arrayAsientoDetalle = [
                        'fecha_asiento_detalle' => $datos['fecha_asiento_cabecera'],
                        'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                        'codigo_cuenta_detalle_asiento' => $datos['codigo_contable'][$i],
                        'debe_asiento_detalle' => $datos['ndebe'][$i],
                        'haber_asiento_detalle' => $datos['nhaber'][$i],
                        'estado_asiento_detalle' => 1,
                    ];
                    AsientosDetalle::insert($arrayAsientoDetalle);
                }
                $r->c = 'AsientosCabecera';
                $r->s = 'saveAsientoManual';
                $r->d = $origin['d'];
                $r->m = AsientosCabecera::$modelo;
                $r->o = 'Se creo un nuevo asiento';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(), 'linea' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function getAsientos()
    {
        return AsientosCabecera::join('db_usuarios as u', 'db_cabecera_asientos_contable.id_usuario_creacion_asiento_cabecera', 'u.id_usuario')
            ->where('id_empresa_asiento_cabecera', session('idEmpresa'))
            ->whereYear('fecha_asiento_cabecera', session('periodo'))
            ->get([
                'id_asiento_cabecera',
                'fecha_asiento_cabecera',
                'debe_asiento_cabecera',
                'haber_asiento_cabecera',
                'u.usuario as usuario_asiento_cabecera',
                'glosa_asiento_cabecera'
            ]);
    }
    public static function saveAsiento($r)
    {
        $arrayAsientoCabecera = [];
        $debe = 0;
        $haber = 0;
        DB::beginTransaction();
        try {
            if ($r->tipo == 'compras') {
                $arrayAsientoCabecera = [
                    'uuid_asiento_cabecera' => Uuid::uuid1(),
                    'fecha_asiento_cabecera' => date('Y-m-d H:i:s'),
                    'id_proveedor_asiento_cabecera' => $r->id_proveedor,
                    'debe_asiento_cabecera' => $r->debe,
                    'haber_asiento_cabecera' => $r->haber,
                    'id_factura_compra_asiento_cabecera' => $r->id_compra,
                    'glosa_asiento_cabecera' => $r->glosa_asiento_cabecera,
                    'estado_asiento_cabecera' => 1,
                    'id_empresa_asiento_cabecera' => session('idEmpresa'),
                    'id_usuario_creacion_asiento_cabecera' => session('idUsuario'),
                    'id_usuario_modificacion_asiento_cabecera' => session('idUsuario'),
                    'created_at_asiento_cabecera' => date('Y-m-d H:i:s')
                ];
                $idAsiento = AsientosCabecera::insertGetId($arrayAsientoCabecera);
                $debe = 0;
                $haber = $r->haber;
                $arrayAsientoDetalle = [
                    'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                    'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                    'codigo_cuenta_detalle_asiento' => $r->cod_plan_proveedor,
                    'debe_asiento_detalle' => $debe,
                    'haber_asiento_detalle' => $haber,
                    'estado_asiento_detalle' => 1,
                    'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                ];
                AsientosDetalle::insert($arrayAsientoDetalle);
                foreach ($r->detalle as $row) {
                    $debe = 0;
                    $haber = 0;
                    if ($row['iva_compra_detalle'] > 0) {
                        $debe = $row['iva_compra_detalle'];
                        $arrayAsientoDetalle = [
                            'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                            'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                            'codigo_cuenta_detalle_asiento' => '1.01.323.000001',
                            'debe_asiento_detalle' => $debe,
                            'haber_asiento_detalle' => $haber,
                            'estado_asiento_detalle' => 1,
                            'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                        ];
                        AsientosDetalle::insert($arrayAsientoDetalle);
                    }

                    $debe = ($row['cantidad_compra_detalle'] * $row['precio_unitario_compra_detalle']) - $row['descuento_compra_detalle'];
                    $arrayAsientoDetalle = [
                        'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                        'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                        'codigo_cuenta_detalle_asiento' => $row['codigo_contable_compra_detalle'],
                        'debe_asiento_detalle' => $debe,
                        'haber_asiento_detalle' => $haber,
                        'estado_asiento_detalle' => 1,
                        'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                    ];
                    AsientosDetalle::insert($arrayAsientoDetalle);
                }
            } elseif ($r->tipo == 'ventas') {
                $arrayAsientoCabecera = [
                    'uuid_asiento_cabecera' => Uuid::uuid1(),
                    'fecha_asiento_cabecera' => date('Y-m-d H:i:s'),
                    'id_cliente_asiento_cabecera' => $r->id_cliente,
                    'debe_asiento_cabecera' => $r->debe,
                    'haber_asiento_cabecera' => $r->haber,
                    'id_factura_venta_asiento_cabecera' => $r->id_venta,
                    'glosa_asiento_cabecera' => $r->glosa_asiento_cabecera,
                    'estado_asiento_cabecera' => 1,
                    'id_empresa_asiento_cabecera' => session('idEmpresa'),
                    'id_usuario_creacion_asiento_cabecera' => session('idUsuario'),
                    'id_usuario_modificacion_asiento_cabecera' => session('idUsuario'),
                    'created_at_asiento_cabecera' => date('Y-m-d H:i:s')
                ];
                $idAsiento = AsientosCabecera::insertGetId($arrayAsientoCabecera);
                $debe = 0;
                $haber = $r->haber - $r->iva;
                $arrayAsientoDetalle = [
                    'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                    'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                    'codigo_cuenta_detalle_asiento' => '4.01.601',
                    'debe_asiento_detalle' => $debe,
                    'haber_asiento_detalle' => $haber,
                    'estado_asiento_detalle' => 1,
                    'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                ];
                AsientosDetalle::insert($arrayAsientoDetalle);
                if ($r->iva > 0) {
                    $debe = 0;
                    $haber = $r->iva;
                    $arrayAsientoDetalle = [
                        'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                        'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                        'codigo_cuenta_detalle_asiento' => '2.01.500',
                        'debe_asiento_detalle' => $debe,
                        'haber_asiento_detalle' => $haber,
                        'estado_asiento_detalle' => 1,
                        'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                    ];
                    AsientosDetalle::insert($arrayAsientoDetalle);
                }
                if ($r->pagado == 1) {
                    $planCuenta = Cuentas::find($r->cod_plan_caja);
                    $debe = $r->debe;
                    $haber = 0;
                    $arrayAsientoDetalle = [
                        'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                        'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                        'codigo_cuenta_detalle_asiento' => $planCuenta->codigo_contable_cuenta,
                        'debe_asiento_detalle' => $debe,
                        'haber_asiento_detalle' => $haber,
                        'estado_asiento_detalle' => 1,
                        'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                    ];
                    AsientosDetalle::insert($arrayAsientoDetalle);
                } else {
                    $debe = $r->debe;
                    $haber = 0;
                    $arrayAsientoDetalle = [
                        'fecha_asiento_detalle' => date('Y-m-d H:i:s'),
                        'id_asiento_cabecera_asiento_detalle' => $idAsiento,
                        'codigo_cuenta_detalle_asiento' => $r->cod_plan_cliente,
                        'debe_asiento_detalle' => $debe,
                        'haber_asiento_detalle' => $haber,
                        'estado_asiento_detalle' => 1,
                        'created_at_asiento_detalle' => date('Y-m-d H:i:s'),
                    ];
                    AsientosDetalle::insert($arrayAsientoDetalle);
                }
            }
            DB::commit();
            return $idAsiento;
        } catch (Exception $e) {
            DB::rollBack();
            $arrayError = [
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($arrayError);
        }
    }
    public function detalles()
    {
        return $this->hasMany(AsientosDetalle::class, 'id_asiento_detalle', 'id_asiento_cabecera');
    }
}
