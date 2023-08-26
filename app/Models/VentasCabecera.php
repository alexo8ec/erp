<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use stdClass;

class VentasCabecera extends Model
{
    protected $table = 'db_cabecera_ventas';
    protected $primaryKey  = 'id_venta_cabecera';
    const CREATED_AT = 'created_at_venta_cabecera';
    const UPDATED_AT = 'updated_at_venta_cabecera';

    private static $modelo = 'Ventas';

    public static function ventasDiariasEstadistico()
    {
        $arrayVentas = '';
        $ventas = VentasCabecera::selectRaw(
            'GROUP_CONCAT(YEAR(fecha_emision_venta_cabecera) limit 1) as Y,
            GROUP_CONCAT(MONTH(fecha_emision_venta_cabecera) limit 1) as m,
            GROUP_CONCAT(DAY(fecha_emision_venta_cabecera) limit 1) as d,
            SUM(total_venta_cabecera) as total'
        )
            ->where('id_empresa_venta_cabecera', session('idEmpresa'))
            ->whereYear('fecha_emision_venta_cabecera', session('periodo'))
            ->whereMonth('fecha_emision_venta_cabecera', date('m'))
            ->where('estado_venta_cabecera', 1)
            ->groupBy(DB::raw('DAY(fecha_emision_venta_cabecera)'))
            ->get();
        $number = cal_days_in_month(CAL_GREGORIAN, date('m'), session('periodo'));
        for ($i = 0; $i < $number; $i++) {
            $a = $i;
            $dia = $a + 1;
            foreach ($ventas as $sal) {
                if ($sal->d == $dia) {
                    $arrayVentas .= '{"0":"' . $sal->Y . '","1":"' . $sal->m . '","2":"' . (string)$dia . '","3":"' . $sal->total . '"},';
                    /*$arrayVenta = [
                        $sal->Y,
                        $sal->m,
                        (string)$dia,
                        $sal->total,
                    ];*/
                } else {
                    $arrayVentas .= '{"0":"' . session('periodo') . '","1":"' . date('m') . '","2":"' . (string)$dia . '","3":"0"},';
                    /*$arrayVenta = [
                        (string)session('periodo'),
                        (string)date('m'),
                        (string)$dia,
                        (string)0,
                    ];*/
                }
                //array_push($arrayVentas, $arrayVenta);
            }
        }
        $arrayVentas = substr($arrayVentas, 0, -1);
        return '[' . $arrayVentas . ']';
    }
    public static function ventasMes($anio)
    {
        $meses = VentasCabecera::selectRaw(
            'GROUP_CONCAT(MONTH(fecha_emision_venta_cabecera) limit 1) as mes,
            IF(IFNULL(SUM(total_venta_cabecera),0)>0,ROUND(SUM(total_venta_cabecera),2),0) cantidad'
        )
            ->where('id_empresa_venta_cabecera', session('idEmpresa'))
            ->whereYear('fecha_emision_venta_cabecera', $anio)
            ->groupBy(DB::raw('MONTH(fecha_emision_venta_cabecera)'))
            ->orderBy(DB::raw('MONTH(fecha_emision_venta_cabecera)'))
            ->get();
        $mes = '';
        for ($i = 0; $i < 12; $i++) {
            $mes .= isset($meses[$i]->mes) ? $meses[$i]->cantidad : 0;
            $mes .= ',';
        }
        $mes = '[' . trim($mes, ',') . ']';
        return $mes;
    }
    public static function aniosVentas()
    {
        $sql = "SELECT YEAR(fecha_emision_venta_cabecera) as anios FROM db_cabecera_ventas WHERE id_empresa_venta_cabecera = " . session('idEmpresa') . " GROUP BY YEAR(fecha_emision_venta_cabecera) ORDER BY fecha_emision_venta_cabecera DESC";
        return DB::select($sql);
    }
    public static function totalVentas()
    {
        //SELECT IFNULL(SUM(subtotal0_venta_cabecera),0)+IFNULL(SUM(subtotal12_venta_cabecera),0) AS total FROM db_cabecera_ventas WHERE id_empresa_venta_cabecera=70 AND establecimiento_venta_cabecera=001 AND emision_venta_cabecera=001 AND YEAR(fecha_emision_venta_cabecera)=2023;
        return VentasCabecera::selectRaw('IFNULL(SUM(subtotal0_venta_cabecera),0)+IFNULL(SUM(subtotal12_venta_cabecera),0)-IFNULL(SUM(descuento_venta_cabecera),0) AS total')
            ->where('id_empresa_venta_cabecera', session('idEmpresa'))
            ->where('establecimiento_venta_cabecera', session('estab'))
            ->where('emision_venta_cabecera', session('emisi'))
            ->whereYear('fecha_emision_venta_cabecera', session('periodo'))
            ->first();
    }
    public static function importarventas()
    {
        ini_set('max_execution_time', '300');
        $ventasImp = DB::connection('FAC')
            ->table('bm_ventash as vc')
            ->select('vc.*', 'en.ruc_empresa', 'cl.ci')
            ->join('bm_entidad as en', 'vc.id_empresa', 'en.id_empresa')
            ->join('bm_cliente as cl', 'vc.id_cliente', 'cl.id_cliente')
            ->where('vc.ambiente', 2)
            ->where('vc.motivo', 'VENTA')
            ->where('vc.importFact', 0)
            ->whereNotNull('vc.num_factura')
            ->where('vc.fecha_venta', '<>', '0000-00-00 00:00:00')
            ->orderBy('vc.id_empresa')
            ->orderBy('vc.establecimiento')
            ->orderBy('vc.num_factura')
            ->orderBy('vc.emision')
            ->limit(500)
            ->get();
        $cont = 0;
        DB::beginTransaction();
        $idClienteFact = '';
        $cedulaFact = '';
        try {
            if (count($ventasImp) > 0) {
                foreach ($ventasImp as $row) {
                    $idClienteFact = $row->id_cliente;
                    $cedulaFact = $row->ci;
                    $cont++;
                    $idEmpresa = Empresas::getIdEmpresa($row->ruc_empresa);
                    $idCliente = Clientes::getIdCliente($row->ci, $idEmpresa);
                    /*echo $idCliente . '<br/>';
                    echo $row->ci;
                    exit;*/
                    $obj = json_decode($row->obj_factura);
                    $ventas = DB::connection('FAC')
                        ->table('bm_venta as vd')
                        ->select('vd.*', 'pr.descripcion', 'pr.modelo', 'pr.cod_producto', 'pr.codigo_contable_productos', 'pr.p_costo', 'pr.id_externo', 'pr.iva_venta')
                        ->join('bm_productos as pr', 'vd.id_producto', 'pr.id_producto')
                        ->where('vd.id_empresa', $row->id_empresa)
                        ->where('vd.num_factura', $row->num_factura)
                        ->where('vd.establecimiento', $row->establecimiento)
                        ->where('vd.emision', $row->emision)
                        ->get();
                    $totalItems = 0;
                    $subtotal0 = 0;
                    $subtotal12 = 0;
                    $descuento = 0;
                    $ivaTotal = 0;
                    $total = 0;
                    $enComprobante = '';
                    $fechaVenta = '';
                    if (count($ventas) > 0) {
                        foreach ($ventas as $rowv) {

                            $totalItems += $rowv->cantidad;
                            $subtotal = $rowv->cantidad * $rowv->p_lista;
                            $iva = (((($rowv->cantidad * $rowv->p_lista) - $rowv->descuento_individual)) * $rowv->iva) / 100;
                            $ivaTotal += $iva;
                            if ($rowv->iva > 0) {
                                $subtotal12 += $subtotal;
                            } else {
                                $subtotal0 += $subtotal;
                            }
                            $descuento += $rowv->descuento_individual;
                            $total += $subtotal + $iva;
                            $enComprobante = $rowv->encomprobante;
                        }
                    }

                    $arrayVentaCabecera = [
                        'num_factura_venta_cabecera' => $row->num_factura,
                        'establecimiento_venta_cabecera' => $row->establecimiento,
                        'emision_venta_cabecera' => $row->emision,
                        'fecha_emision_venta_cabecera' => $row->fecha_venta,
                        'subtotal0_venta_cabecera' => $subtotal0,
                        'subtotal12_venta_cabecera' => $subtotal12,
                        'descuento_venta_cabecera' => $descuento,
                        'iva_venta_cabecera' => $ivaTotal,
                        'total_venta_cabecera' => $total,
                        'total_items_venta_cabecera' => $totalItems,
                        'estado_sri_venta_cabecera' => $row->estado_sri == 'T' ? 1 : 0,
                        'clave_acceso_venta_cabecera' => isset($obj->clave_acceso) ? $obj->clave_acceso : '9999999999',
                        'id_empresa_venta_cabecera' => $idEmpresa,
                        'encomprobante_venta_cabecera' => $enComprobante == 'T' ? 1 : 0,
                        'id_cliente_venta_cabecera' => $idCliente,
                        'pagado_venta_cabecera' => isset($obj->chepagado) ? 1 : 0,
                        'id_forma_pago_venta_cabecera' => 20,
                        'motivo_venta_cabecera' => $row->motivo,
                        'id_usuario_creacion_venta_cabecera' => session('idUsuario'),
                        'id_usuario_modificacion_venta_cabecera' => session('idUsuario'),
                    ];

                    $idVenta = VentasCabecera::insertGetId($arrayVentaCabecera);

                    $totalItems = 0;
                    $subtotal0 = 0;
                    $subtotal12 = 0;
                    $descuento = 0;
                    $ivaTotal = 0;
                    $total = 0;
                    foreach ($ventas as $rowv) {
                        $idProducto = Productos::getIdProducto($rowv, $idEmpresa);
                        $totalItems += $rowv->cantidad;
                        $subtotal = $rowv->cantidad * $rowv->p_lista;
                        $iva = (((($rowv->cantidad * $rowv->p_lista) - $rowv->descuento_individual)) * $rowv->iva) / 100;
                        $ivaTotal += $iva;
                        if ($rowv->iva > 0) {
                            $subtotal12 += $subtotal;
                        } else {
                            $subtotal0 += $subtotal;
                        }
                        $descuento += $rowv->descuento_individual;
                        $total += $subtotal + $iva;
                        $arrayVentaDetalle = [
                            'id_cabecera_venta_detalle' => $idVenta,
                            'id_producto_venta_detalle' => $idProducto,
                            'cantidad_venta_detalle' => $rowv->cantidad,
                            'precio_unitario_venta_detalle' => $rowv->p_lista,
                            'subtotal_venta_detalle' => $subtotal,
                            'descuento_venta_detalle' => $rowv->descuento_individual,
                            'iva_venta_detalle' => $iva,
                            'total_venta_detalle' => $subtotal + $iva,
                            'porcentaje_iva_venta_detalle' => $rowv->iva
                        ];
                        VentasDetalle::insert($arrayVentaDetalle);
                        $arrayMovimiento = [
                            'uuid_movimiento' => Uuid::uuid1(),
                            'id_producto_movimiento' => $idProducto,
                            'ingreso_movimiento' => 0,
                            'egreso_movimiento' =>  $rowv->cantidad,
                            'motivo_movimiento' => 'VENTA',
                            'id_factura_venta_movimiento' => $idVenta,
                            'precio_venta_movimiento' => $rowv->p_lista,
                            'id_empresa_movimiento' => $idEmpresa,
                            'id_usuario_creacion_movimiento' => session('idUsuario'),
                            'id_usuario_modificacion_movimiento' => session('idUsuario'),
                        ];
                        MovimientoProducto::insert($arrayMovimiento);
                    }
                    $pagado =  isset($obj->chepagado) ? 1 : 0;
                    $total_ = $arrayVentaCabecera['subtotal0_venta_cabecera'] +  $arrayVentaCabecera['subtotal12_venta_cabecera'] + $arrayVentaCabecera['iva_venta_cabecera'];

                    $arrayCobro = [
                        'id_venta_cabecera_cobro' => $idVenta,
                        'valor_generado_cobro' => $total_,
                        'valor_cobro' => $pagado == 1 ? $total_ : 0,
                        'valor_saldo_cobro' => $pagado == 1 ? 0 : $total_,
                        'id_cliente_cobro' => $idCliente,
                        'id_empresa_cobro' => $idEmpresa,
                        'fecha_cobro' => $arrayVentaCabecera['fecha_emision_venta_cabecera'],
                        'id_usuario_creacion_cobro' => session('idUsuario'),
                        'id_usuario_modificacion_cobro' => session('idUsuario'),
                        'secuencial_cobro' => Cobros::setSecuencialCobro($idEmpresa),
                    ];
                    Cobros::insertGetId($arrayCobro);
                    $empresa = Empresas::find($idEmpresa);
                    echo 'Id Empresa: ' . $idEmpresa . '</br>';
                    echo 'Empresa: ' . $empresa->razon_social_empresa . '</br>';
                    echo 'Fecha: ' . $row->fecha_venta . '</br>';
                    echo 'No. Factura: ' . $row->establecimiento . '-' . $row->emision . '-' . $row->num_factura . '</br>';
                    echo 'Count: ' . $cont . '</br>';
                    echo '********************<br/>';
                    DB::connection('FAC')
                        ->table('bm_ventash')
                        ->where('id_venta', $row->id_venta)
                        ->update([
                            'importFact' => 1
                        ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            DB::connection('FAC')
                ->table('bm_cliente')
                ->where('id_cliente', $idClienteFact)
                ->update([
                    'importFact' => 0
                ]);
            Clientes::importarClientes();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage() . 'CI: ' . $cedulaFact, 'modelo' => VentasCabecera::$modelo, 'linea' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...|' . $cont);
            return json_encode($result);
        } else {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|No hay datos para procesar...|');
            return json_encode($result);
        }
    }
    public static function saveVenta($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            if ($datos['id_venta'] != '') {
                $cont++;
                $emp = Establecimientos::where('id_establecimiento', $datos['id_establecimiento'])
                    ->first(['uuid_establecimiento']);
                if ($emp->uuid_establecimiento == '')
                    $datos['uuid_establecimiento'] = Uuid::uuid1();
                Establecimientos::where('id_establecimiento', $datos['id_establecimiento'])->update($datos);
                $r->c = 'establecimiento';
                $r->s = 'saveEstablecimiento';
                $r->d = $origin['d'];
                $r->m = VentasCabecera::$modelo;
                $r->o = 'Se actualizo le establecimiento No.: ' . $datos['id_establecimiento'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $empresa = Empresas::getEmpresas(session('idEmpresa'));
                $numFactura = VentasCabecera::numFactura();
                $campos = [
                    'fecha_emision' =>  $r->fecha,
                    'tipo_comprobante' => '01',
                    'ruc' => $empresa->ruc_empresa,
                    'tipo_ambiente' => 2,
                    'serie' => session('estab') . '-' . session('emisi'),
                    'numero_comprobante' => str_pad($numFactura, 9, 0, STR_PAD_LEFT),
                    'tipo_emision' => 1,
                    'punto_establecimiento' => session('estab'),
                    'punto_emision' => session('emisi')
                ];
                $keyEntrySri = new KeyEntrySri($campos);
                $result = $keyEntrySri->toArray();
                $dCliente = explode('|', $r->id_cliente);
                $arrayCabecera = [
                    'num_factura_venta_cabecera' => $numFactura,
                    'establecimiento_venta_cabecera' => session('estab'),
                    'emision_venta_cabecera' => session('emisi'),
                    'fecha_emision_venta_cabecera' => $r->fecha,
                    'subtotal0_venta_cabecera' => $r->subtotal0,
                    'subtotal12_venta_cabecera' => $r->subtotal12,
                    'iva_venta_cabecera' => $r->iva,
                    'total_items_venta_cabecera' => 0,
                    'descuento_venta_cabecera' => $r->descuento,
                    'clave_acceso_venta_cabecera' => $result['clave_acceso'],
                    'total_venta_cabecera' => $r->total_pagar,
                    'id_forma_pago_venta_cabecera' => json_encode($r->id_forma_pago),
                    'id_empresa_venta_cabecera' => session('idEmpresa'),
                    'id_cliente_venta_cabecera' => $dCliente[0],
                    'id_usuario_creacion_venta_cabecera' => session('idUsuario'),
                    'id_usuario_modificacion_venta_cabecera' => session('idUsuario'),
                    'pagado_venta_cabecera' => $r->pagado != '' ? 1 : 0,
                    'id_cuenta_venta_cabecera' => $r->id_cuenta_venta_cabecera
                ];
                $idCabecera = VentasCabecera::insertGetId($arrayCabecera);
                $totalItems = 0;
                $detalles = [];
                $subtotal = 0;
                for ($i = 0; $i < count($r->cantidad); $i++) {
                    $dProducto = explode('|', $r->producto[$i]);
                    $iiva = (((float)$r->cantidad[$i] * (float)$r->precio[$i] - $r->descuento[$i]) * $r->iiva[$i]) / 100;
                    $subtotal = (float)$r->cantidad[$i] * (float)$r->precio[$i];
                    $total = $subtotal + $iiva;
                    $totalItems += $r->cantidad[$i];
                    $arrayDetalle = [
                        'id_cabecera_venta_detalle' => $idCabecera,
                        'id_producto_venta_detalle' => $dProducto[0],
                        'cantidad_venta_detalle' => $r->cantidad[$i],
                        'precio_unitario_venta_detalle' => $r->precio[$i],
                        'subtotal_venta_detalle' => $subtotal,
                        'descuento_venta_detalle' => $r->descuento[$i],
                        'iva_venta_detalle' => $iiva,
                        'total_venta_detalle' => $total,
                    ];
                    $producto = Productos::leftjoin('db_catalogos as marca', 'db_productos.id_marca_producto', '=', 'marca.id_catalogo')
                        ->where('id_producto', $dProducto[0])
                        ->first([
                            'id_producto',
                            'descripcion_producto',
                            'codigo_producto',
                            'modelo_producto',
                            'marca.nombre_catalogo as marca_producto'
                        ]);
                    if ($r->iva == 0)
                        $impuestoIva = -1;
                    else
                        $impuestoIva = 1;
                    $modeloproducto = $producto->modelo_producto != '' ? ' | ' . $producto->modelo_producto : '';
                    $detalle = [
                        'id_producto' => $producto->id_producto,
                        'codigo_producto' => $producto->codigo_producto,
                        'descripcion' => $producto->descripcion_producto . ' ' . $modeloproducto,
                        'p_lista' => $r->precio[$i],
                        'iva' => $iiva,
                        'cantidad' => $r->cantidad[$i],
                        'descuento_individual' =>  $r->descuento[$i],
                        'total_individual' => ($r->precio[$i] * $r->cantidad[$i]) - $r->descuento[$i],
                        'id_impuesto_iva' => $impuestoIva,
                        'id_impuesto_ice' => -1,
                        'id_impuesto_irbpnr' => -1,
                        'datAdicional1' => 'Producto: ' . $producto->descripcion_producto,
                        'datAdicional2' => 'Modelo: ' . $producto->modelo_producto,
                        'datAdicional3' => 'Marca: ' . $producto->marca_producto,
                    ];
                    array_push($detalles, $detalle);
                    VentasDetalle::insert($arrayDetalle);
                    $arrayMovimiento = [
                        'uuid_movimiento' => Uuid::uuid1(),
                        'id_producto_movimiento' => $producto->id_producto,
                        'ingreso_movimiento' => 0,
                        'egreso_movimiento' => $r->cantidad[$i],
                        'motivo_movimiento' => 'VENTA',
                        'id_factura_venta_movimiento' => $idCabecera,
                        'precio_venta_movimiento' => $r->precio[$i],
                        'id_empresa_movimiento' => session('idEmpresa'),
                        'id_usuario_creacion_movimiento' => session('idUsuario'),
                        'id_usuario_modificacion_movimiento' => session('idUsuario'),
                        'fecha_movimiento' => date('Y-m-d H:i:s')
                    ];
                    MovimientoProducto::insert($arrayMovimiento);
                }
                $totalventaCabecera = $r->subtotal0 + $r->subtotal12 + $r->iva;
                $cliente = Clientes::getClientes($dCliente[0]);
                $datoAsiento = new stdClass();
                $datoAsiento->tipo = 'ventas';
                $datoAsiento->id_cliente = $dCliente[0];
                $datoAsiento->id_venta = $idCabecera;
                $datoAsiento->debe = $totalventaCabecera;
                $datoAsiento->haber = $totalventaCabecera;
                $datoAsiento->cod_plan_cliente = $cliente->cod_contable_cliente;
                $datoAsiento->cod_plan_caja = $arrayCabecera['id_cuenta_venta_cabecera'];
                $datoAsiento->detalle = $detalles;
                $datoAsiento->iva = $arrayCabecera['iva_venta_cabecera'];
                $datoAsiento->pagado = $arrayCabecera['pagado_venta_cabecera'];
                $datoAsiento->glosa_asiento_cabecera = trim($cliente->nombre_persona) . ' ' . trim($cliente->apellido_persona) . ' | FACTURA | VENTA | ' . session('establecimiento') . '-' . session('emision') . str_pad($numFactura, 9, 0, STR_PAD_LEFT);
                $asiento = AsientosCabecera::saveAsiento($datoAsiento);
                $arrayCabeceraUp = [
                    'total_items_venta_cabecera' => $totalItems,
                    'id_asiento_venta_cabecera' => $asiento
                ];
                VentasCabecera::where('id_venta_cabecera', $idCabecera)->update($arrayCabeceraUp);
                $pagado = $r->pagado != '' ? 1 : 0;
                $total_ = $r->subtotal0 + $r->subtotal12 + $r->iva;
                $arrayCobro = [
                    'id_venta_cabecera_cobro' => $idCabecera,
                    'valor_generado_cobro' => $total_,
                    'valor_cobro' => $pagado == 1 ? $total_ : 0,
                    'valor_saldo_cobro' => $pagado == 1 ? 0 : $total_,
                    'id_cliente_cobro' => $dCliente[0],
                    'id_empresa_cobro' => session('idEmpresa'),
                    'fecha_cobro' => date('Y-m-d H:i:s'),
                    'id_usuario_creacion_cobro' => session('idUsuario'),
                    'id_usuario_modificacion_cobro' => session('idUsuario'),
                    'secuencial_cobro' => Cobros::setSecuencialCobro(),
                ];
                Cobros::insertGetId($arrayCobro);
                $totalConImpuestos = [];
                if ($r->subtotal0 != 0) {
                    $totalImpuesto['id_impuesto_iva'] = -1;
                    $totalImpuesto['descuento_adicional'] = 0;
                    $totalImpuesto['total'] = $r->subtotal0;
                    $totalImpuesto['valor'] = 0;
                    array_push($totalConImpuestos, $totalImpuesto);
                } else {
                    $totalImpuesto['id_impuesto_iva'] = 1;
                    $totalImpuesto['descuento_adicional'] = 0;
                    $totalImpuesto['total'] =  $r->subtotal12;
                    $totalImpuesto['valor'] =  $r->iva;
                    array_push($totalConImpuestos, $totalImpuesto);
                }
                $formaPago = MetodoPago::whereIn('id_metodo_pago', $r->id_forma_pago)->get(['cod_sri_metodo_pago as forma_pago']);
                $adicionales = [];
                $cliente = Clientes::join('db_personas as p', 'db_clientes.id_persona_cliente', '=', 'p.id_persona')
                    ->where('id_cliente', $dCliente[0])
                    ->first();
                $adicional['nombre'] = 'email';
                $adicional['valor'] = $cliente->email_persona;
                array_push($adicionales, $adicional);
                $adicional['nombre'] = 'dirección';
                $adicional['valor'] = $cliente->direccion_persona;
                array_push($adicionales, $adicional);
                if ($cliente->telefono_persona != '') {
                    $adicional['nombre'] = 'telefono';
                    $adicional['valor'] = $cliente->telefono_persona;
                    array_push($adicionales, $adicional);
                }
                if ($cliente->celular_persona != '') {
                    $adicional['nombre'] = 'celular';
                    $adicional['valor'] = $cliente->celular_persona;
                    array_push($adicionales, $adicional);
                }

                if (isset($r->nombreAdicional) && count($r->nombreAdicional) > 0) {
                    for ($a = 0; $a < count($r->nombreAdicional); $a++) {
                        if ($r->nombreAdicional[$a] != '' && $r->valorAdicional[$a] != '') {
                            $adicional['nombre'] = $r->nombreAdicional[$a];
                            $adicional['valor'] = $r->valorAdicional[$a];
                            array_push($adicionales, $adicional);
                        }
                    }
                }
                $dFactura = [
                    'propina' => 0,
                    'ambiente' => 2,
                    'tipo_emision' => 1,
                    'clave_acceso' => $result['clave_acceso'],
                    'codDoc' => '01',
                    'establecimiento' => session('estab'),
                    'emision' => session('emisi'),
                    'num_factura' =>  $numFactura,
                    'fecha_emision' => $r->fecha,
                    'id_cliente' =>  $dCliente[0],
                    'total' => (float)$r->subtotal0 + (float)$r->subtotal12,
                    'descuento' => $r->descuento,
                    'totalConImpuestos' => $totalConImpuestos,
                    'detalles' => $detalles,
                    'total_pagar' => $r->total_pagar,
                    'forma_pago' => json_decode(json_encode($formaPago), true),
                    'tiempo_credito' => 0,
                    'ruc' => $empresa->ruc_empresa,
                    'adicionales' => $adicionales
                ];
                VentasCabecera::crearXMLFactura($dFactura);
                $cont++;
                $r->c = 'ventas';
                $r->s = 'saveVenta';
                $r->d = $origin['d'];
                $r->m = VentasCabecera::$modelo;
                $r->o = 'Se creo la venta';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $r->c = 'ventas';
            $r->s = 'saveVenta';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = VentasCabecera::$modelo;
            $r->o = 'Error al guardar la venta: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(), 'modelo' => VentasCabecera::$modelo, 'linea' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...|' . session('estab') . '-' . session('emisi') . '-' . str_pad($numFactura, 9, 0, STR_PAD_LEFT));
            return json_encode($result);
        }
    }
    public static function crearXMLFactura($datos, $reFactura = '')
    {
        $datos['tipo_comprobante'] = '01';
        $xml = new Comprobante($datos);
        return $xml->generarXML($reFactura);
    }
    public static function getTotalVentas()
    {
        $total = VentasCabecera::selectRaw('IFNULL(SUM(total_venta_cabecera),0) AS total')
            ->where('id_empresa_venta_cabecera', session('idEmpresa'))
            ->first();
        return $total->total;
    }
    public static function getVentas($id = '', $mes = '')
    {
        if ($mes == '')
            $mes = date('m');
        if ($id == '') {
            return VentasCabecera::join('db_clientes as c', 'db_cabecera_ventas.id_cliente_venta_cabecera', '=', 'c.id_cliente')
                ->join('db_personas as p', 'c.id_persona_cliente', '=', 'p.id_persona')
                ->join('db_usuarios as u', 'db_cabecera_ventas.id_usuario_creacion_venta_cabecera', '=', 'u.id_usuario')
                ->where('id_empresa_venta_cabecera', session('idEmpresa'))
                ->whereYear('fecha_emision_venta_cabecera', session('periodo'))
                ->whereMonth('fecha_emision_venta_cabecera', $mes)
                ->get([
                    'id_venta_cabecera',
                    'num_factura_venta_cabecera',
                    'establecimiento_venta_cabecera',
                    'emision_venta_cabecera',
                    'p.nombre_persona',
                    'p.apellido_persona',
                    'fecha_emision_venta_cabecera',
                    'total_venta_cabecera',
                    'estado_sri_venta_cabecera',
                    'u.usuario as usuario_venta_cabecera',
                    'estado_venta_cabecera'
                ]);
        } else {
            return VentasCabecera::join('db_clientes as c', 'db_cabecera_ventas.id_cliente_venta_cabecera', '=', 'c.id_cliente')
                ->join('db_personas as p', 'c.id_persona_cliente', '=', 'p.id_persona')
                ->join('db_detalle_ventas as d', 'db_cabecera_ventas.id_venta_cabecera', '=', 'd.id_cabecera_venta_detalle')
                ->join('db_productos as pro', 'd.id_producto_venta_detalle', '=', 'pro.id_producto')
                ->where('id_venta_cabecera', $id)
                ->get([
                    'id_venta_cabecera',
                    'num_factura_venta_cabecera',
                    'establecimiento_venta_cabecera',
                    'emision_venta_cabecera',
                    'p.nombre_persona',
                    'p.apellido_persona',
                    'fecha_emision_venta_cabecera',
                    'total_venta_cabecera',
                    'estado_sri_venta_cabecera',
                    'pro.codigo_producto',
                    'pro.descripcion_producto',
                    'pro.modelo_producto',
                    'd.cantidad_venta_detalle',
                    'd.precio_unitario_venta_detalle',
                    'subtotal0_venta_cabecera',
                    'subtotal12_venta_cabecera',
                    'descuento_venta_cabecera',
                    'iva_venta_cabecera',
                    'total_venta_cabecera',
                ]);
        }
    }
    public function detalleVentas()
    {
        return $this->hasMany(VentasDetalle::class, 'id_cabecera_venta_detalle', 'id_venta_cabecera');
    }
    private static function numFactura()
    {
        $num = 1;
        $numVenta = VentasCabecera::where('id_empresa_venta_cabecera', session('idEmpresa'))
            ->where('establecimiento_venta_cabecera', session('establecimiento'))
            ->where('emision_venta_cabecera', session('emision'))
            ->max('num_factura_venta_cabecera');
        if ($numVenta != '') {
            $num = $numVenta + 1;
        } else {
            $numFactura = Establecimientos::where('id_empresa_establecimiento', session('idEmpresa'))
                ->where('establecimiento', session('establecimiento'))
                ->where('emision_establecimiento', session('emision'))
                ->first();
            if ($numFactura != '') {
                $num = $numFactura->num_inicial_establecimiento + 1;
            }
        }
        return $num;
    }
}
