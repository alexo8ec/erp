<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ComprasCabecera extends Model
{
    protected $table = 'db_cabecera_compras';
    protected $primaryKey  = 'id_compra_cabecera';
    const CREATED_AT = 'created_at_compra_cabecera';
    const UPDATED_AT = 'updated_at_compra_cabecera';

    private static $modelo = 'ComprasCabecera';
    public static function leerCompraXml($r)
    {
        $contenido = "";
        if ($da = fopen($_FILES['docXML']['tmp_name'], "r")) {
            while ($aux = fgets($da, 1024)) {
                $contenido .= $aux;
            }
            fclose($da);
        } else {
            echo "Error: no se ha podido leer el archivo <strong>" . $_FILES['docXML']['name'] . "</strong>";
        }
        $arrayXml = [
            'status' => false,
            'error' => 'Ocurrio un error al leer el archivo XML...',
        ];
        try {
            $xml = simplexml_load_string($contenido);
            $comprobante = simplexml_load_string($xml->comprobante);
            $mensajes = simplexml_load_string($xml->mensajes);
            $claveAcceso = $comprobante->infoTributaria->claveAcceso;
            $compra = ComprasCabecera::where('clave_acceso_compra_cabecera', $claveAcceso)
                ->first();
            $ruc = $comprobante->infoFactura->identificacionComprador;
            if (strlen($ruc) == 10)
                $ruc = $ruc . '001';
            $empresa = Empresas::getIdEmpresa($ruc);
            if ($empresa !=session('idEmpresa')) {
                $arrayXml = [
                    'status' => false,
                    'error' => 'El comprobante pertenece a otra empresa...',
                ];
            } elseif ($compra != '') {
                $arrayXml = [
                    'status' => false,
                    'error' => 'El comprobante que intenta ingresar ya fue ingresado...',
                ];
            } else {
                $arrayXml = [
                    'status' => true,
                    'xml' => $xml,
                    'comprobante' => $comprobante,
                    'mensajes' => $mensajes
                ];
            }
        } catch (Exception $e) {
            $arrayXml = [
                'status' => false,
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
            ];
        }

        return json_encode($arrayXml);
    }
    public static function totalCompras()
    {
        return ComprasCabecera::selectRaw('IFNULL(sum(subtotal_0_compra_cabecera),0)+IFNULL(sum(subtotal_12_compra_cabecera),0)-IFNULL(sum(descuento_compra_cabecera),0) as total')
            ->where('id_empresa_compra_cabecera', session('idEmpresa'))
            ->where('fecha_emision_compra_cabecera', session('periodo'))
            ->first();
    }
    public static function getCompras($id = '')
    {
        if ($id == '') {
            return ComprasCabecera::join('db_proveedores as pr', 'id_proveedor_compra_cabecera', 'pr.id_proveedor')
                ->join('db_personas as pe', 'pr.id_persona_proveedor', 'pe.id_persona')
                ->join('db_tipo_documentos as td', 'id_tipo_documento_compra_cabecera', 'td.id_tipo_doc')
                ->join('db_usuarios as us', 'id_usuario_creacion_compra_cabecera', 'us.id_usuario')
                ->where('id_empresa_compra_cabecera', session('idEmpresa'))
                ->get();
        } else {
            return ComprasCabecera::join('db_proveedores as pr', 'id_proveedor_compra_cabecera', 'pr.id_proveedor')
                ->join('db_personas as pe', 'pr.id_persona_proveedor', 'pe.id_persona')
                ->join('db_tipo_documentos as td', 'id_tipo_documento_compra_cabecera', 'td.id_tipo_doc')
                ->join('db_usuarios as us', 'id_usuario_creacion_compra_cabecera', 'us.id_usuario')
                ->where('id_compra_cabecera', $id)
                ->first();
        }
    }
    public static function saveComprasSri($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        $idPersona = 0;
        $idProveedor = 0;
        DB::beginTransaction();
        try {
            $persona = Personas::where('identificacion_persona', $datos['ruc'])->first();
            if ($persona == '') {
                $secuencialProveedor = Proveedores::setSecuencialProveedor();
                $arrayPersona = [
                    'uuid_persona' => Uuid::uuid1(),
                    'razon_social_persona' => $datos['razon_social'],
                    'nombre_comercial_persona' => $datos['nombre_comercial'],
                    'id_tipo_identificacion_persona' => 19,
                    'identificacion_persona' => $datos['ruc'],
                    'direccion_persona' => $datos['direccion'],
                    'referencia_domicilio_persona' => $datos['direccion_sucursal'],
                    'id_usuario_creacion_persona' => session('idUsuario'),
                    'id_usuario_modificacion_persona' => session('idUsuario'),
                ];
                $idPersona = Personas::insertGetId($arrayPersona);
                $arrayPlanCuenta = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => '2.01.413.' . $secuencialProveedor,
                    'nombre_cuenta_plan' => $datos['razon_social'],
                    'clase_contable_plan' => 2,
                    'grupo_contable_plan' => 1,
                    'cuenta_contable_plan' => 413,
                    'auxiliar_contable_plan' => $secuencialProveedor,
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPLanCuenta = PlanCuenta::insertGetId($arrayPlanCuenta);
                $arrayProveedor = [
                    'uuid_proveedor' => Uuid::uuid1(),
                    'id_persona_proveedor' => $idPersona,
                    'id_empresa_proveedor' => session('idEmpresa'),
                    'obligado_contabilidad_proveedor' => $datos['obligado_contabilidad'] == 'NO' ? 0 : 1,
                    'contribuyente_especial_proveedor' => $datos['contribuyente_especial'] == 'NO' ? 0 : 1,
                    'num_contribuyente_especial_proveedor' => $datos['numContribuyenteEspecial'],
                    'secuencial_proveedor' => (int)$secuencialProveedor,
                    'tipo_contable_proveedor' => 'pasivo',
                    'cod_plan_proveedor' => '2.01.413',
                    'id_plan_proveedor' => $idPLanCuenta
                ];
                $idProveedor = Proveedores::insertGetId($arrayProveedor);
            } else {
                $idPersona = $persona->id_persona;
                $arrayPersona = [
                    'razon_social_persona' => $datos['razon_social'],
                    'direccion_persona' => $datos['direccion'],
                    'referencia_domicilio_persona' => $datos['direccion_sucursal'],
                    'id_usuario_modificacion_persona' => session('idUsuario')
                ];
                Personas::where('identificacion_persona', $datos['ruc'])->update($arrayPersona);
                $arrayProveedor = [
                    'obligado_contabilidad_proveedor' => $datos['obligado_contabilidad'] == 'NO' ? 0 : 1,
                    'contribuyente_especial_proveedor' => $datos['contribuyente_especial'] == 'NO' ? 0 : 1,
                    'num_contribuyente_especial_proveedor' => $datos['numContribuyenteEspecial'],
                ];
                Proveedores::where('id_persona_proveedor', $idPersona)->update($arrayProveedor);
                $idProveedor = Proveedores::where('id_persona_proveedor', $idPersona)->first();
                $arrayPlanCuenta = [
                    'nombre_cuenta_plan' => $datos['razon_social'],
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                PlanCuenta::where('id_plan', $idProveedor->id_plan_proveedor)->update($arrayPlanCuenta);
                $idProveedor = $idProveedor->id_proveedor;
            }
            $serie = explode('-', $datos['serie']);
            $metodoPago = explode(' - ', $datos['forma_pago']);
            $mPago = MetodoPago::where('cod_sri_metodo_pago', $metodoPago[0])->first();

            $arrayCabeceraCompra = [
                'uuid_compra_cabecera' => Uuid::uuid1(),
                'secuencial_compra_cabecera' => $datos['secuencial'],
                'establecimiento_compra_cabecera' => $serie[0],
                'emision_cabecera_compra' => $serie[1],
                'id_proveedor_compra_cabecera' => $idProveedor,
                'clave_acceso_compra_cabecera' => $datos['clave_acceso'],
                'id_metodo_pago_compra_cabecera' => $mPago->id_metodo_pago,
                'fecha_emision_compra_cabecera' => $datos['fecha_emision'],
                'id_empresa_compra_cabecera' => session('idEmpresa'),
                'id_sustento_tributario_compra_cabecera' => $datos['id_sustento_tributario'],
                'id_usuario_creacion_compra_cabecera' => session('idUsuario'),
                'id_usuario_modificacion_compra_cabecera' => session('idUsuario'),
            ];
            $arrayDetalleCompra = [];
            $idCabeceraCompra = ComprasCabecera::insertGetId($arrayCabeceraCompra);
            if (count($datos['producto']) > 0) {
                for ($i = 0; $i < count($datos['producto']); $i++) {
                    $idProducto = 0;
                    if ($datos['producto'][$i] == '') {
                        $prodExterno = explode(' | ', $datos['producto_externo'][$i]);
                        $producto = Productos::where('descripcion_producto', $prodExterno[1])->first();
                        if ($producto == '') {
                            $arrayProducto = [
                                'uuid_producto' => Uuid::uuid1(),
                                'descripcion_producto' => $prodExterno[1],
                                'codigo_producto' => Productos::setCodigo(),
                                'id_presentacion_producto' => 43,
                                'id_plan_cuenta_producto' => PlanCuenta::setIdPlanCuenta($prodExterno[1], $datos['tipo'][$i]),
                                'id_categoria_producto',
                                'id_subcategoria_producto',
                                'min_stock_producto',
                                'costo_producto',
                                'valor1_producto',
                                'valor2_producto',
                                'valor3_producto',
                                'valor4_producto',
                                'id_tipo_producto',
                                'id_empresa_producto',
                                'id_marca_producto',
                                'modelo_producto',
                                'qr_producto',
                                'observacion_producto',
                                'codigo_externo_producto' => $prodExterno[0],
                                'estado_producto',
                                'id_iva_producto',
                                'id_ice_producto',
                                'id_irbpnr_producto',
                                'id_deducible_producto',
                                'tipo_contable_producto',
                                'cod_plan_producto',
                                'id_talla_producto',
                                'id_color_producto',
                                'peso_producto',
                                'id_usuario_creacion_producto',
                                'id_usuario_modificacion_producto',
                                'created_at_producto',
                                'updated_at_producto'
                            ];
                        }
                    } else {
                    }
                    $arrayDetalleCompra = [
                        'id_cabecera_compra_detalle' => $idCabeceraCompra,
                        'id_producto_compra_detalle',
                        'cantidad_compra_detalle',
                        'precio_unitario_compra_detalle',
                        'descuento_compra_detalle',
                        'iva_compra_detalle',
                        'total_compra_detalle',
                        'adicional_compra_detalle'
                    ];
                    echo '<pre>';
                    print_r($arrayProducto);
                }
            }
            DB::rollBack();
            exit;
            $cont++;
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
        exit;
        $cont = 0;
        DB::beginTransaction();
        try {
            if ($datos['id_persona'] != '') {
                $cont++;
                $cat = ComprasCabecera::where('id_compra_cabecera', $datos['id_compra_cabecera'])->first(['uuid_compra_cabecera']);
                if ($cat == '') {
                    $arrayPersona['uuid_compra_cabecera'] = Uuid::uuid1();
                    $arrayPersona['id_usuario_modificacion_persona'] = session('idUsuario');
                }
                $arrayCliente = [
                    'id_tipo_cliente' => $datos['id_tipo_cliente'],
                    'estado_cliente' => $datos['estado_cliente'],
                    'valor_compra_cliente' => $datos['valor_compra_cliente'],
                    'observacion_cliente' => $datos['observacion_cliente']
                ];
                ComprasCabecera::where('id_persona_cliente',  $datos['id_persona'])
                    ->update($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = ComprasCabecera::$modelo;
                $r->o = 'Se actualizo el catalogo No.: ' . $datos['id_persona'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $arrayPersona['uuid_compra_cabecera'] = Uuid::uuid1();
                $arrayPersona['id_usuario_creacion_persona'] = session('idUsuario');
                $arrayPersona['created_at_persona'] = date('Y-m-d H:i:s');
                $id = Personas::insertGetId($arrayPersona);
                $arrayCliente = [
                    'uuid_cliente' => Uuid::uuid1(),
                    'id_persona_cliente' => $id,
                    'id_tipo_cliente' => $datos['id_tipo_cliente'],
                    'id_empresa_cliente' => session('idEmpresa'),
                    'estado_cliente' => $datos['estado_cliente'],
                    'created_at_cliente' => date('Y-m-d H:i:s'),
                    'valor_compra_cliente' => $datos['valor_compra_cliente'],
                    'observacion_cliente' => $datos['observacion_cliente']
                ];
                ComprasCabecera::insert($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = ComprasCabecera::$modelo;
                $r->o = 'Se creo un nuevo catalogo';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function saveCompras($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            if (isset($datos['ruc'])) {
                $persona = Personas::where('identificacion_persona', $datos['ruc'])->first();
                if ($persona == '') {
                    $secuencialProveedor = Proveedores::setSecuencialProveedor();
                    $arrayPersona = [
                        'uuid_persona' => Uuid::uuid1(),
                        'razon_social_persona' => $datos['razon_social'],
                        'nombre_comercial_persona' => $datos['nombre_comercial'],
                        'id_tipo_identificacion_persona' => 19,
                        'identificacion_persona' => $datos['ruc'],
                        'direccion_persona' => $datos['direccion'],
                        'referencia_domicilio_persona' => $datos['direccion_sucursal'],
                        'id_usuario_creacion_persona' => session('idUsuario'),
                        'id_usuario_modificacion_persona' => session('idUsuario'),
                    ];
                    $idPersona = Personas::insertGetId($arrayPersona);
                    $arrayPlanCuenta = [
                        'uuid_plan' => Uuid::uuid1(),
                        'codigo_contable_plan' => '2.01.413.' . $secuencialProveedor,
                        'nombre_cuenta_plan' => $datos['razon_social'],
                        'clase_contable_plan' => 2,
                        'grupo_contable_plan' => 1,
                        'cuenta_contable_plan' => 413,
                        'auxiliar_contable_plan' => $secuencialProveedor,
                        'id_empresa_plan' => session('idEmpresa'),
                        'id_usuario_creacion_plan' => session('idUsuario'),
                        'id_usuario_modificacion_plan' => session('idUsuario'),
                    ];
                    $idPLanCuenta = PlanCuenta::insertGetId($arrayPlanCuenta);
                    $arrayProveedor = [
                        'uuid_proveedor' => Uuid::uuid1(),
                        'id_persona_proveedor' => $idPersona,
                        'id_empresa_proveedor' => session('idEmpresa'),
                        'obligado_contabilidad_proveedor' => $datos['obligado_contabilidad'] == 'NO' ? 0 : 1,
                        'contribuyente_especial_proveedor' => $datos['contribuyente_especial'] == 'NO' ? 0 : 1,
                        'num_contribuyente_especial_proveedor' => $datos['numContribuyenteEspecial'],
                        'secuencial_proveedor' => (int)$secuencialProveedor,
                        'tipo_contable_proveedor' => 'pasivo',
                        'cod_plan_proveedor' => '2.01.413',
                        'id_plan_proveedor' => $idPLanCuenta
                    ];
                    $idProveedor = Proveedores::insertGetId($arrayProveedor);
                    $r->c = 'Compras';
                    $r->s = 'saveCompra';
                    $r->d = $origin['d'];
                    $r->m = ComprasCabecera::$modelo;
                    $r->o = 'Se creo una persona y un proveedor al crear una factura por SRI ' . $idProveedor;
                    Auditorias::saveAuditoria($r, DB::getQueryLog());
                } else {
                    $dp = Proveedores::where('id_persona_proveedor', $persona->id_persona)->first();
                    if ($dp == '') {
                        $secuencialProveedor = Proveedores::setSecuencialProveedor();
                        $arrayPlanCuenta = [
                            'uuid_plan' => Uuid::uuid1(),
                            'codigo_contable_plan' => '2.01.413.' . $secuencialProveedor,
                            'nombre_cuenta_plan' => $datos['razon_social'],
                            'clase_contable_plan' => 2,
                            'grupo_contable_plan' => 1,
                            'cuenta_contable_plan' => 413,
                            'auxiliar_contable_plan' => $secuencialProveedor,
                            'id_empresa_plan' => session('idEmpresa'),
                            'id_usuario_creacion_plan' => session('idUsuario'),
                            'id_usuario_modificacion_plan' => session('idUsuario'),
                        ];
                        $idPLanCuenta = PlanCuenta::insertGetId($arrayPlanCuenta);
                        $arrayProveedor = [
                            'uuid_proveedor' => Uuid::uuid1(),
                            'id_persona_proveedor' => $persona->id_persona,
                            'id_empresa_proveedor' => session('idEmpresa'),
                            'obligado_contabilidad_proveedor' => $datos['obligado_contabilidad'] == 'NO' ? 0 : 1,
                            'contribuyente_especial_proveedor' => $datos['contribuyente_especial'] == 'NO' ? 0 : 1,
                            'num_contribuyente_especial_proveedor' => $datos['numContribuyenteEspecial'],
                            'secuencial_proveedor' => (int)$secuencialProveedor,
                            'tipo_contable_proveedor' => 'pasivo',
                            'cod_plan_proveedor' => '2.01.413',
                            'id_plan_proveedor' => $idPLanCuenta
                        ];
                        $idProveedor = Proveedores::insertGetId($arrayProveedor);
                        $r->c = 'Compras';
                        $r->s = 'saveCompra';
                        $r->d = $origin['d'];
                        $r->m = ComprasCabecera::$modelo;
                        $r->o = 'Se creo un proveedor al crear una factura por SRI ' . $idProveedor;
                        Auditorias::saveAuditoria($r, DB::getQueryLog());
                    } else {
                        $idProveedor = $dp->id_proveedor;
                    }
                }
            } else {
                if (isset($datos['id_proveedor_compra_cabecera'])) {
                    $dProveedor = explode('|', $datos['id_proveedor_compra_cabecera']);
                    $idProveedor = $dProveedor[0];
                }
            }

            $serie = explode('-', $datos['serie']);
            $metodoPago = explode(' - ', $datos['forma_pago']);

            $mPago = MetodoPago::where('cod_sri_metodo_pago', $metodoPago[0])->first();

            $tipoDoc = explode(' - ', $datos['tipo_doc']);

            $idTipoDoc = TipoDocumento::where('codigo_sri', $tipoDoc[0])->first();

            $compra = ComprasCabecera::where('id_proveedor_compra_cabecera', $idProveedor)
                ->where('establecimiento_compra_cabecera', $serie[0])
                ->where('emision_compra_cabecera', $serie[1])
                ->where('secuencial_compra_cabecera', $datos['secuencial'])
                ->where('id_tipo_documento_compra_cabecera', $idTipoDoc->id_tipo_doc)
                ->first();

            if ($compra == '') {
                $arrayCabeceraCompra = [
                    'uuid_compra_cabecera' => Uuid::uuid1(),
                    'secuencial_compra_cabecera' => $datos['secuencial'],
                    'establecimiento_compra_cabecera' => $serie[0],
                    'emision_compra_cabecera' => $serie[1],
                    'id_proveedor_compra_cabecera' => $idProveedor,
                    'clave_acceso_compra_cabecera' => $datos['clave_acceso'],
                    'id_metodo_pago_compra_cabecera' => $mPago->id_metodo_pago,
                    'fecha_emision_compra_cabecera' => $datos['fecha_emision'],
                    'id_empresa_compra_cabecera' => session('idEmpresa'),
                    'id_tipo_documento_compra_cabecera' => $idTipoDoc->id_tipo_doc,
                    'id_sustento_tributario_compra_cabecera' => $datos['id_sustento_tributario'],
                    'dias_credito_compra_cabecera' => isset($datos['dias_credito']) ? $datos['dias_credito'] : 0,
                    'id_usuario_creacion_compra_cabecera' => session('idUsuario'),
                    'id_usuario_modificacion_compra_cabecera' => session('idUsuario'),
                    'subtotal_0_compra_cabecera' => $datos['subtotal0'],
                    'subtotal_12_compra_cabecera' => $datos['subtotal12'],
                    'descuento_compra_cabecera' => $datos['descuentos'],
                    'iva_compra_cabecera' => $datos['iva'],
                    'total_compra_cabecera' => $datos['total_pagar'],
                ];
                $arrayDetalleCompra = [];
                $idCabeceraCompra = ComprasCabecera::insertGetId($arrayCabeceraCompra);
                if (count($datos['producto']) > 0) {
                    for ($i = 0; $i < count($datos['producto']); $i++) {
                        $dproducto = explode('|', $datos['producto'][$i]);
                        $arrayDetalleCompra = [
                            'id_cabecera_compra_detalle' => $idCabeceraCompra,
                            'id_producto_compra_detalle' => $dproducto[0] != '' ? $dproducto[0] : Productos::crearProductoExterno($datos['producto_externo'][$i], $datos['tipo'][$i], $datos['precio'][$i], $datos['iiva'][$i], $datos['codigoPlan'][$i]),
                            'cantidad_compra_detalle' => $datos['cantidad'][$i],
                            'precio_unitario_compra_detalle' => $datos['precio'][$i],
                            'descuento_compra_detalle' => $datos['descuento'][$i],
                            'iva_compra_detalle' => $datos['iiva'][$i],
                            'total_compra_detalle' => $datos['total'][$i],
                            'eleboracion_compra_detalle' => isset($datos['elab'][$i]) ? $datos['elab'][$i] : null,
                            'vencimiento_compra_detalle' => isset($datos['venc'][$i]) ? $datos['venc'][$i] : null,
                            'lote_compra_detalle' => isset($datos['lote'][$i]) ? $datos['lote'][$i] : null,
                            'codigo_contable_compra_detalle' => $datos['codigoPlan'][$i]
                        ];
                        ComprasDetalle::insert($arrayDetalleCompra);
                        $arrayMovimiento = [
                            'uuid_movimiento' => Uuid::uuid1(),
                            'id_producto_movimiento' => $arrayDetalleCompra['id_producto_compra_detalle'],
                            'ingreso_movimiento' => $datos['cantidad'][$i],
                            'egreso_movimiento' => 0,
                            'motivo_movimiento' => 'COMPRA',
                            'id_factura_compra_movimiento' => $idCabeceraCompra,
                            'precio_compra_movimiento' => $datos['precio'][$i],
                            'id_empresa_movimiento' => session('idEmpresa'),
                            'id_usuario_creacion_movimiento' => session('idUsuario'),
                            'id_usuario_modificacion_movimiento' => session('idUsuario'),
                        ];
                        MovimientoProducto::insert($arrayMovimiento);
                    }
                }
                $cont++;
                $r->c = 'Compras';
                $r->s = 'saveCompra';
                $r->d = $origin['d'];
                $r->m = ComprasCabecera::$modelo;
                $r->o = 'Se creo una compra: ' . $serie[0] . '-' . $serie[1] . '-' . $datos['secuencial'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|Compra ya se encuentra registrada...');
                return json_encode($result);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(), 'linea' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...|' . $idCabeceraCompra);
            return json_encode($result);
        }
    }
    public function detalleCompras()
    {
        return $this->hasMany(ComprasDetalle::class, 'id_cabecera_compra_detalle', 'id_compra_cabecera');
    }
}
