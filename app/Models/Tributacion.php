<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SoapClient;
use stdClass;

class Tributacion extends Model
{
    public static function saveCompraXml($r)
    {
        $datos = $r->input();
        $serie = explode('-', $datos['serie_compra_cabecera']);
        DB::beginTransaction();
        try {
            $arrayCabecera = [
                'uuid_compra_cabecera' => Uuid::uuid1(),
                'secuencial_compra_cabecera' => $datos['secuencial_tributario_compra_cabecera'],
                'establecimiento_compra_cabecera' => $serie[0],
                'emision_compra_cabecera' => $serie[1],
                'id_proveedor_compra_cabecera' => $datos['id_proveedor_compra_cabecera'],
                'clave_acceso_compra_cabecera' => $datos['clave_acceso_compra_cabecera'],
                'id_metodo_pago_compra_cabecera' => json_encode($datos['id_forma_pago_compra_cabecera']),
                'fecha_emision_compra_cabecera' => $datos['fecha_emision_compra_cebacera'],
                'id_empresa_compra_cabecera' => session('idEmpresa'),
                'id_tipo_documento_compra_cabecera' => $datos['id_tipo_documento_compra_cabecera'],
                'id_sustento_tributario_compra_cabecera' => $datos['id_sustento_tributario_compra_cabecera'],
                'dias_credito_compra_cabecera' => $datos['dias_credito_compra_cabecera'],
                'subtotal_0_compra_cabecera' => $datos['subtotal_0_compra_cabecera'],
                'subtotal_12_compra_cabecera' => $datos['subtotal_12_compra_cabecera'],
                'descuento_compra_cabecera' => $datos['descuento_compra_cabecera'],
                'iva_compra_cabecera' => $datos['iva_compra_cabecera'],
                'total_compra_cabecera' => $datos['total_compra_cabecera'],
                'porcentaje_iva_compra_cabecera' => $datos['tarifaIva'],
                'contabilizado_compra_cabecera' => 0,
                'pagado_compra_cabecera' => 0,
                'id_forma_pago_compra_cabecera' => 0,
                'estado_compra_cabecera' => 1,
                'id_usuario_creacion_compra_cabecera' => session('idUsuario'),
                'id_usuario_modificacion_compra_cabecera' => session('idUsuario')
            ];
            $idCompra = ComprasCabecera::insertGetId($arrayCabecera);
            $arrayAsientoDetalle = [];
            for ($i = 0; $i < count($datos['codigoPrincipal']); $i++) {
                $prod = new stdClass();
                $prod->modelo = '';
                $prod->descripcion = $datos['descripcion'][$i];
                $prod->cod_producto = $datos['codigoPrincipal'][$i];
                $prod->id_externo = $datos['codigoPrincipal'][$i];
                $prod->p_costo = $datos['precioUnitario'][$i];
                $planCuenta = PlanCuenta::find($datos['id_plan_cuenta_producto'][$i]);
                $prod->codigo_contable_productos = $planCuenta->codigo_contable_plan;
                $idProducto = Productos::getIdProducto($prod, session('idEmpresa'));
                $producto=Productos::find($idProducto);
                $arrayDetalle = [
                    'id_cabecera_compra_detalle' => $idCompra,
                    'id_producto_compra_detalle' => $idProducto,
                    'cantidad_compra_detalle' => $datos['cantidad'][$i],
                    'precio_unitario_compra_detalle' => $datos['precioUnitario'][$i],
                    'descuento_compra_detalle' => $datos['descuento'][$i],
                    'iva_compra_detalle' => $datos['valor'][$i],
                    'total_compra_detalle' => $datos['total_compra_cabecera'],
                    'codigo_contable_compra_detalle' => $producto->cod_plan_producto,
                    'created_at_compra_detalle' => date('Y-m-d H:i:s')
                ];
                ComprasDetalle::insert($arrayDetalle);
                array_push($arrayAsientoDetalle, $arrayDetalle);
                $arrayMovimiento = [
                    'uuid_movimiento' => Uuid::uuid1(),
                    'fecha_movimiento' => $datos['fecha_emision_compra_cebacera'],
                    'id_producto_movimiento' => $idProducto,
                    'ingreso_movimiento' => $datos['cantidad'][$i],
                    'egreso_movimiento' => 0,
                    'motivo_movimiento' => 'COMPRA',
                    'id_factura_compra_movimiento' => $idCompra,
                    'precio_compra_movimiento' => $datos['precioUnitario'][$i],
                    'id_empresa_movimiento' => session('idEmpresa'),
                    'id_usuario_creacion_movimiento' => session('idUsuario'),
                    'id_usuario_modificacion_movimiento' => session('idUsuario'),
                ];
                MovimientoProducto::insert($arrayMovimiento);
            }
            $proveedor = Proveedores::find($datos['id_proveedor_compra_cabecera']);
            $datoAsiento = new stdClass();
            $datoAsiento->tipo = 'compras';
            $datoAsiento->id_proveedor = $datos['id_proveedor_compra_cabecera'];
            $datoAsiento->id_compra = $idCompra;
            $datoAsiento->debe = $datos['total_compra_cabecera'];
            $datoAsiento->haber = $datos['total_compra_cabecera'];
            $datoAsiento->cod_plan_proveedor = $proveedor->cod_plan_proveedor;
            $datoAsiento->detalle = $arrayAsientoDetalle;
            $datoAsiento->glosa_asiento_cabecera = $datos['glosa_asiento_cabecera'];
            $asiento = AsientosCabecera::saveAsiento($datoAsiento);
            $arrayDocImportado = [
                'estado' => 'Ingresado'
            ];
            DB::table('db_importacion_documento_sri')
                ->where('clave_acceso', $datos['clave_acceso_compra_cabecera'])
                ->update($arrayDocImportado);
            DB::commit();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        } catch (Exception $e) {
            DB::rollBack();
            $arrayError = [
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($arrayError);
        }
    }
    public static function traerDocumentoSri($clave = '')
    {
        $arrayDatos = [
            'status' => false,
            'error' => 'Ocurrio un problema al conectarse con el SRI, intente dentro de un momento...',
        ];
        try {
            $servicio = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
            $parametros = array();
            $parametros['claveAccesoComprobante'] = $clave;
            $client = new SoapClient($servicio, $parametros);
            $result = $client->autorizacionComprobante($parametros);
            $docCod = substr($clave, 8, 2);
            $esta = substr($clave, 24, 3);
            $emis = substr($clave, 27, 3);
            $num = substr($clave, 30, 9);
            if ($result->RespuestaAutorizacionComprobante->numeroComprobantes > 0) {
                $comprobante = simplexml_load_string($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
                $ruc = (string)$comprobante->infoTributaria->ruc;
                $persona = Personas::where('identificacion_persona', $ruc)->first();
                if ($persona == '') {
                    DB::beginTransaction();
                    try {
                        $arrayPersona = [
                            'uuid_persona' => Uuid::uuid1(),
                            'razon_social_persona' => (string)$comprobante->infoTributaria->razonSocial,
                            'nombre_comercial_persona' => (string)$comprobante->infoTributaria->nombreComercial,
                            'identificacion_persona' => $ruc,
                            'direccion_persona' => (string)$comprobante->infoTributaria->dirMatriz,
                            'id_usuario_creacion_persona' => session('idUsuario'),
                            'id_usuario_modificacion_persona' => session('idUsuario'),
                        ];
                        $idPersona = Personas::insertGetId($arrayPersona);
                        Proveedores::asignarProveedor($idPersona, $comprobante);
                        $proveedor = Proveedores::where('id_persona_proveedor', $idPersona)->first();
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        $arrayDatos = [
                            'status' => false,
                            'error' => $e->getMessage(),
                            'linea' => $e->getLine()
                        ];
                    }
                } else {
                    $idPersona = $persona->id_persona;
                    Proveedores::asignarProveedor($idPersona, $comprobante);
                }
                $proveedor = Proveedores::where('id_persona_proveedor', $idPersona)->first();
                $tipoDocumentos = TipoDocumento::where('codigo_sri', $comprobante->infoTributaria->codDoc)->first();
                $formaPago = '';
                $fp = isset($comprobante->infoFactura->pagos->pago->formaPago) ? (string)$comprobante->infoFactura->pagos->pago->formaPago : '';
                if ($fp == '') {
                    $count = 0;
                    foreach ($comprobante->infoFactura->pagos->pago as $row) {
                        $fp[$count] = $row->formaPago;
                        $count++;
                    }
                } else
                    $fp = ['0' => $fp];
                $fp = MetodoPago::whereIn('cod_sri_metodo_pago', $fp)->get([
                    'id_metodo_pago'
                ]);
                $arrayDatos = [
                    'status' => true,
                    'xml' => $result,
                    'autorizaciones' => $result->RespuestaAutorizacionComprobante->autorizaciones,
                    'comprobante' => $comprobante,
                    'proveedor' => $proveedor,
                    'id_tipo_doc' => $tipoDocumentos->id_tipo_doc,
                    'forma_pago' => $fp
                ];
            } elseif ($result->RespuestaAutorizacionComprobante->numeroComprobantes == 0) {
                $arrayDatos = [
                    'status' => false,
                    'error' => 'El documento no se encuentra autorizado...',
                ];
            } else {
                $arrayDatos = [
                    'status' => false,
                    'error' => $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje,
                ];
            }
        } catch (Exception $e) {
            $arrayDatos = [
                'status' => false,
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
        }
        return json_encode($arrayDatos);
    }
    public static function getImportarDocumentos($r)
    {
        $empresa = Empresas::getEmpresas(session('idEmpresa'));
        $guardado = $_FILES[$r->tipo_archivo]['tmp_name'];
        $cont = 0;
        $contFila = 0;
        DB::enableQueryLog();
        DB::table('db_importacion_documento_sri')
            ->where('id_empresa', session('idEmpresa'))
            ->delete();
        if ($da = fopen($guardado, "r")) {
            while ($aux = fgets($da, 1024)) {
                if ($cont > 0) {
                    $lineaArchivo = explode('	', $aux);
                    if (isset($lineaArchivo[4])) {
                        $rucReceptor = strlen($lineaArchivo[8]) == 10 ? $lineaArchivo[8] . '001' : $lineaArchivo[8];
                        if ($rucReceptor == $empresa->ruc_empresa) {
                            $contFila++;
                            $tipo = '';
                            $docCod = substr($lineaArchivo[10], 8, 2);
                            if ($docCod == '01') {
                                $tipo = 'Factura';
                            } elseif ($docCod == '03') {
                                $tipo = 'Liquidación de compra';
                            } elseif ($docCod == '04') {
                                $tipo = 'Nota de crédito';
                            } elseif ($docCod == '05') {
                                $tipo = 'Nota de débito';
                            } elseif ($docCod == '06') {
                                $tipo = 'Guia de remisión';
                            } elseif ($docCod == '07') {
                                $tipo = 'Comprobante de retención';
                            }
                            $arrayComprobante = [
                                'no' => $contFila,
                                'estado' => Tributacion::estadoDocumentoSRI(trim($lineaArchivo[10])),
                                'tipo' => $tipo,
                                'num_comprobante' => $lineaArchivo[1],
                                'ruc_emisior' => $lineaArchivo[2],
                                'razon_social' => utf8_encode($lineaArchivo[3]),
                                'fecha_emision' => $lineaArchivo[4],
                                'fecha_autorizacion' => $lineaArchivo[5],
                                'clave_acceso' => $lineaArchivo[9],
                                'num_autorizacion' => $lineaArchivo[10],
                                'total' => $lineaArchivo[11],
                                'id_empresa' => session('idEmpresa')
                            ];
                            DB::table('db_importacion_documento_sri')->insert($arrayComprobante);
                        }
                    }
                }
                $cont++;
            }
            fclose($da);
        } else {
            echo "Error: no se ha podido leer el archivo <strong>" . $_FILES['docXML']['name'] . "</strong>";
        }
    }
    public static function estadoDocumentoSRI($clave)
    {
        $estado = '';
        $docCod = substr($clave, 8, 2);
        if ($docCod == '01') {
            DB::enableQueryLog();
            $compra = ComprasCabecera::where('clave_acceso_compra_cabecera', $clave)->first();
            if ($compra != '')
                $estado = 'Ingresado';
        }
        return $estado;
    }
}
