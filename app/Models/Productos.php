<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Productos extends Model
{
    protected $table = 'db_productos';
    protected $primaryKey  = 'id_producto';
    const CREATED_AT = 'created_at_producto';
    const UPDATED_AT = 'updated_at_producto';

    private static $modelo = 'Productos';
    public static function getArticulosProductos($id)
    {
        return Productos::selectRaw(
            'id_producto,
            codigo_producto,
            descripcion_producto,
            valor1_producto,
            valor2_producto,
            valor3_producto,
            valor4_producto,
            iva.porcentaje_tipo_impuesto as procentaje_iva,
            modelo_producto'

        )
            ->leftJoin('db_tipo_impuestos as iva', 'db_productos.id_iva_producto', '=', 'iva.id_tipo_impuesto')
            ->leftJoin('db_tipo_impuestos as ice', 'db_productos.id_ice_producto', '=', 'ice.id_tipo_impuesto')
            ->leftJoin('db_tipo_impuestos as irbpnr', 'db_productos.id_irbpnr_producto', '=', 'irbpnr.id_tipo_impuesto')
            ->where('id_subcategoria_producto', $id)
            ->get();
    }
    public static function getModelosCombo($r)
    {
        return Productos::where('id_subcategoria_producto', $r->id)
            ->get([
                'modelo_producto'
            ]);
    }
    public static function getMovimientos($r)
    {
        $fecha_hasta = $r->h;
        $fecha_hasta = date('Y-m-d', strtotime($fecha_hasta . "+ 1 days"));
        DB::enableQueryLog();
        return MovimientoProducto::selectRaw(
            'id_movimiento,
            p.codigo_producto,
            p.descripcion_producto,
            p.modelo_producto,
            ingreso_movimiento,
            egreso_movimiento, 
            fecha_movimiento,
            IF(IFNULL(id_factura_compra_movimiento,0)>0,(SELECT CONCAT("COMPRA | ",LPAD(establecimiento_compra_cabecera,3,0),"-",LPAD(emision_compra_cabecera,3,0),"-",LPAD(secuencial_compra_cabecera,9,0)) FROM db_cabecera_compras WHERE id_compra_cabecera=db_movimiento_productos.id_factura_compra_movimiento),
            IF(IFNULL(id_factura_venta_movimiento,0)>0,(SELECT CONCAT("VENTA | ",LPAD(establecimiento_venta_cabecera,3,0),"-",LPAD(emision_venta_cabecera,3,0),"-",LPAD(num_factura_venta_cabecera,9,0)) FROM db_cabecera_ventas WHERE id_venta_cabecera=db_movimiento_productos.id_factura_venta_movimiento),"")
            ) AS comprobante,
            created_at_movimiento'
        )
            ->join('db_productos as p', 'db_movimiento_productos.id_producto_movimiento', 'p.id_producto')
            ->where('id_producto_movimiento', $r->id)
            ->where('fecha_movimiento', '>=', $r->d)
            ->where('fecha_movimiento', '<=', $fecha_hasta)
            ->get();
        echo '<pre>';
        print_r(DB::getQueryLog());
        exit;
    }
    public static function validarCodigo($r)
    {
        $result = [];
        $codigo = Productos::where('id_empresa_producto', session('idEmpresa'))
            ->where('codigo_producto', $r->codigo)
            ->first(['codigo_producto']);
        if ($codigo != '') {
            $result = array('code' => 200, 'state' => true, 'data' => json_encode($codigo), 'message' => 'ok|El código que desea ingresar ya existe en otro producto...');
        } else {
            $result = array('code' => 200, 'state' => false, 'data' => '', 'message' => 'no|No existe el codigo consultado...');
        }
        return json_encode($result);
    }
    public static function getIdProducto($row, $idEmpresa)
    {
        DB::beginTransaction();
        try {
            $idProducto = 0;
            $modelo = $row->modelo;
            $producto = Productos::where('descripcion_producto', $row->descripcion)
                ->where('id_empresa_producto', $idEmpresa)
                ->where(function ($q) use ($modelo) {
                    if ($modelo != '')
                        $q->where('modelo_producto', $modelo);
                    else {
                        $q->whereNull('modelo_producto');
                        //$q->where('modelo_producto', '');
                    }
                })
                //->where('codigo_producto', $row->cod_producto)
                ->first();
            /*echo '<pre>';
            print_r($producto);
            exit;*/
            if ($producto != '')
                $idProducto = $producto->id_producto;
            else {
                $codContPro = explode('.', $row->codigo_contable_productos);
                $codigo = Productos::setCodigo();
                $codigoContable = '';
                $codigoContable = $codContPro[0] . '.' . $codContPro[1] . '.' . $codContPro[2] . '.' . $codigo;
                $dCodigoContable = explode('.', $codigoContable);
                $arrayPlan = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => $codigoContable,
                    'nombre_cuenta_plan' => $row->descripcion . ' ' . $row->modelo,
                    'clase_contable_plan' => $dCodigoContable[0],
                    'grupo_contable_plan' => (int)$dCodigoContable[1],
                    'cuenta_contable_plan' => (int)$dCodigoContable[2],
                    'auxiliar_contable_plan' => (int)$dCodigoContable[3],
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPlan = PlanCuenta::insertGetId($arrayPlan);
                $tipo = '';
                if ($dCodigoContable[0] == 1)
                    $tipo = 'activo';
                elseif ($dCodigoContable[0] == 2)
                    $tipo = 'pasivo';
                elseif ($dCodigoContable[0] == 4)
                    $tipo = 'ingreso';
                elseif ($dCodigoContable[0] . '.' . $dCodigoContable[1] == 5.01)
                    $tipo = 'costo';
                elseif ($dCodigoContable[0] . '.' . $dCodigoContable[1] == 5.02)
                    $tipo = 'gasto';
                $arrayProducto = [
                    'uuid_producto' => Uuid::uuid1(),
                    'descripcion_producto' => $row->descripcion,
                    'codigo_producto' => $codigo,
                    'id_presentacion_producto' => 43,
                    'id_plan_cuenta_producto' => $idPlan,
                    'min_stock_producto' => 0,
                    'costo_producto' => $row->p_costo,
                    'id_empresa_producto' => session('idEmpresa'),
                    'codigo_externo_producto' => $row->id_externo,
                    'id_iva_producto' => $row->iva_venta = 'S' ? 1 : 0,
                    'id_ice_producto' => -1,
                    'id_irbpnr_producto' => -1,
                    'id_deducible_producto' => 6,
                    'tipo_contable_producto' => $tipo,
                    'cod_plan_producto' => $codigoContable,
                    'secuencial_producto' => $codigo,
                    'id_usuario_creacion_producto' => session('idUsuario'),
                    'id_usuario_modificacion_producto' => session('idUsuario'),
                ];
                $idProducto = Productos::insertGetId($arrayProducto);
            }
            DB::commit();
            return $idProducto;
        } catch (Exception $e) {
            DB::rollBack();
            $arrayError = [
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($arrayError);
        }
    }
    public static function importarproductos()
    {
        $productoImp = DB::connection('FAC')
            ->table('bm_productos as p')
            ->select('p.*', 'e.ruc_empresa', 'u.unidad', 'c.categoria', 'sc.subcategoria', 'm.marca')
            ->join('bm_entidad as e', 'p.id_empresa', 'e.id_empresa')
            ->leftjoin('bm_unidad_medida as u', 'p.id_unidad', 'u.id_unidad')
            ->leftjoin('bm_categorias as c', 'p.id_categoria', 'c.id_categoria')
            ->leftjoin('bm_subcategorias as sc', 'p.id_subcategoria', 'sc.id_subcategoria')
            ->leftjoin('bm_marcas as m', 'p.id_marca', 'm.id_marca')
            ->where('p.importFact', 0)
            ->orderBy('e.ruc_empresa')
            ->limit(300)
            ->get();
        if (count($productoImp) > 0) {
            DB::beginTransaction();
            try {
                $cont = 0;
                foreach ($productoImp as $row) {
                    $empresa = Empresas::where('ruc_empresa', $row->ruc_empresa)->first(['id_empresa', 'razon_social_empresa']);
                    $unidad = Catalogos::where('codigo_catalogo',  trim(Utilidades::sanear_string_tildes(strtolower($row->unidad))))->first(['id_catalogo']);
                    $categoria = Categorias::where('nombre_categoria', $row->categoria)->first(['id_categoria']);
                    $subcategoria = SubCategorias::where('nombre_subcategoria', $row->subcategoria)->first(['id_subcategoria']);
                    $marca = Catalogos::where('codigo_catalogo',  trim(Utilidades::sanear_string_tildes(strtolower($row->marca))))->first(['id_catalogo', 'nombre_catalogo', 'codigo_catalogo']);
                    $secuencial = Productos::setCodigo($empresa->id_empresa);
                    if ($empresa != '') {
                        $tipoContable = '';
                        if ($row->tipo == 'P' || $row->tipo == 1) {
                            $tipoContable = 'activo';
                            $codigoContable = '1.01.327.' . $secuencial;
                        } elseif ($row->tipo == 2) {
                            $tipoContable = 'activo';
                            $codigoContable = '1.01.327.' . $secuencial;
                        } elseif ($row->tipo == 3) {
                            $tipoContable = 'activo';
                            $codigoContable = '1.01.327.' . $secuencial;
                        } elseif ($row->tipo == 4) {
                            $tipoContable = 'ingreso';
                            if ($row->iva_venta == 'S')
                                $codigoContable = '4.01.601.' . $secuencial;
                            else
                                $codigoContable = '4.01.602.' . $secuencial;
                        } elseif ($row->tipo == 5) {
                            $tipoContable = 'gasto';
                            $codigoContable = '5.02.793.' . $secuencial;
                        } elseif ($row->tipo == 7) {
                            $tipoContable = 'gasto';
                            $codigoContable = '5.02.719.' . $secuencial;
                        } elseif ($row->tipo == 8) {
                            $tipoContable = 'activo';
                            $codigoContable = '1.02.345.' . $secuencial;
                        } else {
                            $tipoContable = 'activo';
                            $codigoContable = '1.01.327.' . $secuencial;
                        }
                        $scontabla = explode('.', $codigoContable);
                        $arrayPlanCuenta = [
                            'uuid_plan' => Uuid::uuid1(),
                            'codigo_contable_plan' => $codigoContable,
                            'nombre_cuenta_plan' => $row->descripcion . ' ' . $row->modelo,
                            'clase_contable_plan' => $scontabla[0],
                            'grupo_contable_plan' => (int)$scontabla[1],
                            'cuenta_contable_plan' => (int)$scontabla[2],
                            'auxiliar_contable_plan' => $secuencial,
                            'id_empresa_plan' => $empresa->id_empresa,
                            'id_usuario_creacion_plan' => session('idUsuario'),
                            'id_usuario_modificacion_plan' => session('idUsuario'),
                        ];
                        $idPLanCuenta = PlanCuenta::insertGetId($arrayPlanCuenta);
                        $arrayProducto = [
                            'uuid_producto' => Uuid::uuid1(),
                            'descripcion_producto' => $row->descripcion,
                            'codigo_producto' => $row->cod_producto,
                            'id_presentacion_producto' => isset($unidad->id_catalogo) ? $unidad->id_catalogo : 43,
                            'id_plan_cuenta_producto' => $idPLanCuenta,
                            'tipo_contable_producto' => $tipoContable,
                            'cod_plan_producto' => $codigoContable,
                            'id_categoria_producto' => isset($categoria->id_categoria) ? $categoria->id_categoria : null,
                            'id_subcategoria_producto' => isset($subcategoria->id_subcategoria) ? $subcategoria->id_subcategoria : null,
                            'min_stock_producto' => $row->stock_min,
                            'costo_producto' => $row->p_costo != '' ? $row->p_costo : 0,
                            'valor1_producto' => $row->valor1 != '' ? $row->valor1 : 0,
                            'valor2_producto' => $row->valor2 != '' ? $row->valor2 : 0,
                            'valor3_producto' => $row->valor3 != '' ? $row->valor3 : 0,
                            'valor4_producto' => $row->valor4 != '' ? $row->valor4 : 0,
                            'id_tipo_producto' => $row->sub_tipo == 'F' ? 37 : 38,
                            'id_empresa_producto' => $empresa->id_empresa,
                            'id_marca_producto' => isset($marca->id_catalogo) ? $marca->id_catalogo : null,
                            'modelo_producto' => $row->modelo,
                            'qr_producto' => $row->qr,
                            'observacion_producto' => $row->observacion,
                            'codigo_externo_producto' => $row->codigo_externo,
                            'estado_producto' => $row->estado == 'T' ? 1 : 0,
                            'id_iva_producto' => $row->sri_tipo_impuesto_iva_id != '' ? $row->sri_tipo_impuesto_iva_id : -1,
                            'id_ice_producto' => $row->sri_tipo_impuesto_ice_id != '' ? $row->sri_tipo_impuesto_ice_id : -1,
                            'id_irbpnr_producto' => $row->sri_tipo_impuesto_irbpnr_id != '' ? $row->sri_tipo_impuesto_irbpnr_id : -1,
                            'id_deducible_producto' => 6,
                            'secuencial_producto' => (int)$secuencial,
                            'id_usuario_creacion_producto' => session('idUsuario'),
                            'id_usuario_modificacion_producto' => session('idUsuario'),
                        ];
                        Productos::insert($arrayProducto);
                        $cont++;
                        echo $cont . ' | ' . $empresa->razon_social_empresa . '<br/>';
                    }
                    DB::connection('FAC')
                        ->table('bm_productos')
                        ->where('id_producto', $row->id_producto)
                        ->update([
                            'importFact' => 1
                        ]);
                }
                DB::commit();
                if ($cont == 0)
                    echo 'No hay productos nuevos';
            } catch (Exception $e) {
                DB::rollBack();
                echo 'Error: ' . $e->getMessage() . '<br/>';
                echo 'Línea: ' . $e->getLine() . '<br/>';
            }
        }
    }
    public static function crearProductoExterno($producto, $tipo, $precio, $iva, $codigoPlan)
    {
        $codigo = Productos::setCodigo();
        $dProducto = explode(' | ', $producto);
        $productoEncontrado = Productos::where('descripcion_producto', $dProducto[1])
            ->where('codigo_externo_producto', $dProducto[0])
            ->first();
        if ($productoEncontrado == '') {
            $codigoContable = '';
            $codigoContable = $codigoPlan . '.' . $codigo;
            $dCodigoContable = explode('.', $codigoContable);
            $codPlan = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                ->where('codigo_contable_plan', $codigoContable)
                ->first();
            $idPlan = '';
            if ($codPlan == '') {
                $arrayPlan = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => $codigoContable,
                    'nombre_cuenta_plan' => $dProducto[1],
                    'clase_contable_plan' => $dCodigoContable[0],
                    'grupo_contable_plan' => (int)$dCodigoContable[1],
                    'cuenta_contable_plan' => (int)$dCodigoContable[2],
                    'auxiliar_contable_plan' => (int)$dCodigoContable[3],
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPlan = PlanCuenta::insertGetId($arrayPlan);
            } else {
                $idPlan = $codPlan->id_plan;
            }
            $dpro = Productos::where('id_empresa_producto', session('idEmpresa'))
                ->where('id_plan_cuenta_producto', $idPlan)
                ->first();
            if ($dpro == '') {
                $arrayProducto = [
                    'uuid_producto' => Uuid::uuid1(),
                    'descripcion_producto' => $dProducto[1],
                    'codigo_producto' => $codigo,
                    'id_presentacion_producto' => 43,
                    'id_plan_cuenta_producto' => $idPlan,
                    'min_stock_producto' => 0,
                    'costo_producto' => $precio,
                    'id_empresa_producto' => session('idEmpresa'),
                    'codigo_externo_producto' => $dProducto[0],
                    'id_iva_producto' => $iva > 0 ? 1 : 0,
                    'id_ice_producto' => -1,
                    'id_irbpnr_producto' => -1,
                    'id_deducible_producto' => 6,
                    'tipo_contable_producto' => $tipo,
                    'cod_plan_producto' => $codigoContable,
                    'secuencial_producto' => $codigo,
                    'id_usuario_creacion_producto' => session('idUsuario'),
                    'id_usuario_modificacion_producto' => session('idUsuario'),
                ];
                $idProducto = Productos::insertGetId($arrayProducto);
            } else
                $idProducto = $dpro->id_producto;
        } else {
            $idProducto = $productoEncontrado->id_producto;
        }
        return $idProducto;
    }
    public static function setCodigo($idEmpresa = '')
    {
        if ($idEmpresa == '')
            $idEmpresa = session('idEmpresa');
        $max = Productos::where('id_empresa_producto', $idEmpresa)->max('secuencial_producto');
        $max += 1;
        return str_pad($max, 6, 0, STR_PAD_LEFT);
    }
    public static function agregarLinea($r)
    {
        $linea = '';
        $combo = '';
        $productos = Productos::leftjoin('db_tipo_impuestos', 'db_productos.id_iva_producto', '=', 'db_tipo_impuestos.id_tipo_impuesto')
            ->where('id_empresa_producto', session('idEmpresa'))
            ->where(function ($q) use ($r) {
                if ($r->tipo == 'venta') {
                    $q->where('cod_plan_producto', 'like', '1%')
                        ->orWhere('cod_plan_producto', 'like', '4%');
                }
            })
            ->get();
        if (count($productos) > 0) {
            $combo .= '<option value=""></option>';
            foreach ($productos as $row) {
                $combo .= '<option value="' . $row->id_producto .  '|' . $row->valor1_producto . '|' . $row->valor2_producto . '|' . $row->valor3_producto . '|' . $row->valor4_producto . '|' . $row->codigo_producto . '|' . $row->porcentaje_tipo_impuesto . '|' . $row->descripcion_producto . '|' . $row->modelo_producto . '|' . $row->costo_producto . '">' . $row->descripcion_producto . ' ' . $row->modelo_producto . '</option>';
            }
        }
        $compra = '';
        if ($r->tipo == 'compra') {
            $compra .= '<td style="width:8%;"><input type="date" class="form-control" id="elab_' . $r->fila . '" name="elab[]" style="text-align:right;" onblur="calcular();" /></td>
            <td style="width:8%;"><input type="date" class="form-control" id="venc_' . $r->fila . '" name="venc[]" style="text-align:right;" onblur="calcular();" /></td>
            <td style="width:8%;"><input type="text" class="form-control" id="lote_' . $r->fila . '" name="lote[]" style="text-align:right;" onblur="calcular();" /></td>';
        }
        $linea .= '<tr id="lineaFactura_' . $r->fila . '">
        <td style="width:10%;"><input type="hidden" id="iiva_' . $r->fila . '" name="iiva[]" value="" /><input type="text" class="form-control" id="codigo_' . $r->fila . '" readonly /></td>
        <td style="width:10%;"><input type="text" class="form-control" id="cantidad_' . $r->fila . '" name="cantidad[]" style="text-align:right;" onblur="calcular();" value="1" /></td>
        <td style="width:25%">
            <select class="form-control" id="productos_' . $r->fila . '"  name="producto[]" onchange="seleccionarProducto(this,\'' . $r->fila . '\');" style="width:100%;">' . $combo . '</select>
        </td>
        ' . $compra . '
        <td style="width:10%;"><input type="text" class="form-control" id="precio_' . $r->fila . '" name="precio[]" style="text-align:right;" value="0.00" onblur="calcular();" /></td>
        <td style="width:10%"><input type="text" class="form-control" id="descuento_' . $r->fila . '" name="descuento[]" style="text-align:right;" value="0.00" onblur="calcular();" /></td>
        <td style="width:15%;"><input type="text" class="form-control" id="total_' . $r->fila . '" name="total[]" style="text-align:right;" value="0.00" readonly /></td>
        <td style="width:5%;" align="center"><button type="button" class="btn btn-danger waves-effect waves-light" onclick="eliminarFila(\'' . $r->fila . '\');"><i class="fa-solid fa-trash-can"></i></button></td>
        </tr>
        <script>
        $(document).ready(function(){
            $(\'#productos_' . $r->fila . '\').select2({noResults: function () {return "No hay resultado";},searching: function () {return "Buscando..";},placeholder: "--Seleccione un producto--",});
        });        
        </script>';
        return $linea;
    }
    public static function saveProducto($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        unset($datos['ncontable']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $datos['id_empresa_producto'] = session('idEmpresa');
            $datos['costo_producto'] = str_replace('$ ', '', $datos['costo_producto']);
            $datos['valor1_producto'] = str_replace('$ ', '', $datos['valor1_producto']);
            $datos['valor2_producto'] = str_replace('$ ', '', $datos['valor2_producto']);
            $datos['valor3_producto'] = str_replace('$ ', '', $datos['valor3_producto']);
            $datos['valor4_producto'] = str_replace('$ ', '', $datos['valor4_producto']);
            if ($datos['id_producto'] != '') {
                $cont++;
                $cat = Productos::where('id_producto', $datos['id_producto'])->first(['uuid_producto', 'secuencial_producto']);
                if ($cat == '') {
                    $datos['uuid_producto'] = Uuid::uuid1();
                }
                $datos['cod_plan_producto'] = PlanCuenta::traerCodigoContable($datos['id_plan_cuenta_producto']) . '.' . str_pad($cat->secuencial_producto, 6, 0, STR_PAD_LEFT);
                $datos['id_usuario_modificacion_producto'] = session('idUsuario');
                Productos::where('id_producto',  $datos['id_producto'])
                    ->update($datos);
                $r->c = 'Productos';
                $r->s = 'saveProducto';
                $r->d = $origin['d'];
                $r->m = Productos::$modelo;
                $r->o = 'Se actualizo el Producto No.: ' . $datos['id_producto'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_producto'] = Uuid::uuid1();
                $datos['secuencial_producto'] = str_pad(Productos::setCodigo(), 6, 0, STR_PAD_LEFT);
                $datos['id_usuario_creacion_producto'] = session('idUsuario');
                $datos['id_usuario_modificacion_producto'] = session('idUsuario');
                Productos::insert($datos);
                $r->c = 'Productos';
                $r->s = 'saveProducto';
                $r->d = $origin['d'];
                $r->m = Productos::$modelo;
                $r->o = 'Se creo un nuevo producto';
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
    public static function getProductos($id = '')
    {
        if ($id == '') {
            $establecimiento = session('estab');
            $emision = session('emisi');
            $idEmpresa = session('idEmpresa');
            $sql = "SELECT GROUP_CONCAT(id_producto LIMIT 1) AS id_producto,
                IFNULL(SUM(m.ingreso_movimiento),0)-IFNULL(SUM(m.egreso_movimiento),0) AS stock,
                GROUP_CONCAT(codigo_producto LIMIT 1) AS codigo_producto,
                GROUP_CONCAT(id_presentacion_producto LIMIT 1) AS id_presentacion_producto,
                GROUP_CONCAT(descripcion_producto LIMIT 1) AS descripcion_producto,
                GROUP_CONCAT(modelo_producto LIMIT 1) AS modelo_producto,
                GROUP_CONCAT(presentacion.nombre_catalogo LIMIT 1) AS presentacion_producto,
                GROUP_CONCAT(marca.nombre_catalogo LIMIT 1) AS marca_producto,
                GROUP_CONCAT(iva.descripcion_tipo_impuesto LIMIT 1) AS iva_producto,
                GROUP_CONCAT(ice.descripcion_tipo_impuesto LIMIT 1) AS ice_producto,
                GROUP_CONCAT(costo_producto LIMIT 1) AS costo_producto,
                GROUP_CONCAT(valor1_producto LIMIT 1) AS valor1_producto,
                GROUP_CONCAT(valor2_producto LIMIT 1) AS valor2_producto,
                GROUP_CONCAT(valor3_producto LIMIT 1) AS valor3_producto,
                GROUP_CONCAT(valor4_producto LIMIT 1) AS valor4_producto,
                GROUP_CONCAT(categoria.nombre_categoria LIMIT 1) AS categoria_producto,
                GROUP_CONCAT(subcategoria.nombre_subcategoria LIMIT 1) AS subcategoria_producto,
                GROUP_CONCAT(id_categoria_producto LIMIT 1) AS id_categoria_producto,
                GROUP_CONCAT(id_subcategoria_producto LIMIT 1) AS id_subcategoria_producto,
                GROUP_CONCAT(estado_producto LIMIT 1) AS estado_producto,
                GROUP_CONCAT(color.nombre_catalogo LIMIT 1) AS color_producto,
                GROUP_CONCAT(talla.nombre_catalogo LIMIT 1) AS talla_producto,
                GROUP_CONCAT(id_color_producto LIMIT 1) AS id_color_producto,
                GROUP_CONCAT(id_talla_producto LIMIT 1) AS id_talla_producto 
                FROM db_productos 
                LEFT JOIN db_catalogos AS presentacion ON db_productos.id_presentacion_producto = presentacion.id_catalogo 
                LEFT JOIN db_movimiento_productos AS m ON db_productos.id_producto = m.id_producto_movimiento AND db_productos.id_empresa_producto = m.id_empresa_movimiento 
                AND m.establecimiento_movimiento =  $establecimiento  
                AND m.emision_movimiento = $emision
                LEFT JOIN db_catalogos AS marca ON db_productos.id_marca_producto = marca.id_catalogo 
                LEFT JOIN db_catalogos AS color ON db_productos.id_color_producto = color.id_catalogo 
                LEFT JOIN db_catalogos AS talla ON db_productos.id_talla_producto = talla.id_catalogo 
                LEFT JOIN db_tipo_impuestos AS iva ON db_productos.id_iva_producto = iva.id_tipo_impuesto 
                LEFT JOIN db_tipo_impuestos AS ice ON db_productos.id_ice_producto = ice.id_tipo_impuesto 
                LEFT JOIN db_tipo_impuestos AS irbpnr ON db_productos.id_irbpnr_producto = irbpnr.id_tipo_impuesto 
                LEFT JOIN db_categorias AS categoria ON db_productos.id_categoria_producto = categoria.id_categoria 
                LEFT JOIN db_subcategorias AS subcategoria ON db_productos.id_subcategoria_producto = subcategoria.id_subcategoria 
                WHERE id_empresa_producto = $idEmpresa 
                GROUP BY db_productos.id_producto";
            return DB::select($sql);
        } else {
            return Productos::leftJoin('db_catalogos as presentacion', 'db_productos.id_presentacion_producto', '=', 'presentacion.id_catalogo')
                ->leftJoin('db_catalogos as marca', 'db_productos.id_marca_producto', '=', 'marca.id_catalogo')
                ->leftJoin('db_catalogos as color', 'db_productos.id_color_producto', '=', 'color.id_catalogo')
                ->leftJoin('db_catalogos as talla', 'db_productos.id_talla_producto', '=', 'talla.id_catalogo')
                ->leftJoin('db_tipo_impuestos as iva', 'db_productos.id_iva_producto', '=', 'iva.id_tipo_impuesto')
                ->leftJoin('db_tipo_impuestos as ice', 'db_productos.id_ice_producto', '=', 'ice.id_tipo_impuesto')
                ->leftJoin('db_tipo_impuestos as irbpnr', 'db_productos.id_irbpnr_producto', '=', 'irbpnr.id_tipo_impuesto')
                ->leftJoin('db_categorias as categoria', 'db_productos.id_categoria_producto', '=', 'categoria.id_categoria')
                ->leftJoin('db_subcategorias as subcategoria', 'db_productos.id_subcategoria_producto', '=', 'subcategoria.id_subcategoria')
                ->where('id_empresa_producto', session('idEmpresa'))
                ->where('id_producto', $id)
                ->first();
        }
    }
    public static function buscarProducto($r)
    {
        $termino = $r['term'];
        return Productos::selectRaw('CONCAT(id_producto) as id, CONCAT(codigo_producto," | ",descripcion_producto," | ",IFNULL(modelo_producto,"")) as text')
            ->where('id_empresa_producto', session('idEmpresa'))
            ->where(function ($q) use ($termino) {
                $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(codigo_producto),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(descripcion_producto),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(modelo_producto),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
            })
            ->get();
    }
    public function archivos()
    {
        return $this->hasMany(Archivos::class, 'id_empresa_archivo', 'id_empresa');
    }
}
