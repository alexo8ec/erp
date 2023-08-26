<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Clientes extends Model
{
    protected $table = 'db_clientes';
    protected $primaryKey  = 'id_cliente';
    const CREATED_AT = 'created_at_cliente';
    const UPDATED_AT = 'updated_at_cliente';

    private static $modelo = 'Clientes';
    public static function estadoCliente($r)
    {
        $result = '';
        if (Clientes::where('id_cliente', $r->id)->update(['estado_cliente' => $r->estado]))
            $result = ['status' => 'success'];
        else
            $result = ['status' => 'error'];
        return $result;
    }
    public static function clientesMes($anio)
    {
        $meses = Clientes::selectRaw(
            'GROUP_CONCAT(MONTH(created_at_cliente) limit 1) as mes,
            IF(IFNULL(COUNT(id_cliente),0)>0,COUNT(id_cliente),0) cantidad'
        )
            ->where('id_empresa_cliente', session('idEmpresa'))
            ->whereYear('created_at_cliente', $anio)
            ->groupBy(DB::raw('MONTH(created_at_cliente)'))
            ->orderBy(DB::raw('MONTH(created_at_cliente)'))
            ->get();
        $mes = '';
        for ($i = 0; $i < 12; $i++) {
            $mes .= isset($meses[$i]->mes) ? $meses[$i]->cantidad : 0;
            $mes .= ',';
        }
        $mes = '[' . trim($mes, ',') . ']';
        return $mes;
    }
    public static function aniosClientes()
    {
        $sql = "SELECT YEAR(created_at_cliente) as anios FROM db_clientes WHERE id_empresa_cliente = " . session('idEmpresa') . " GROUP BY YEAR(created_at_cliente) ORDER BY created_at_cliente DESC";
        return DB::select($sql);
    }
    public static function totalClientes()
    {
        return Clientes::selectRaw('count(id_cliente) as total')
            ->where('id_empresa_cliente', session('idEmpresa'))
            ->first();
    }
    public static function getIdCliente($ci, $idEmpresa)
    {
        DB::beginTransaction();
        $idCliente = 0;
        try {
            $persona = Personas::where('identificacion_persona', $ci)->first();
            if ($persona != '') {
                $cliente = Clientes::where('id_persona_cliente', $persona->id_persona)
                    ->where('id_empresa_cliente', $idEmpresa)
                    ->first(['id_cliente']);
                if ($cliente != '')
                    $idCliente = $cliente->id_cliente;
                else {
                    $secuencialCliente = Clientes::setSecuencialCliente($idEmpresa);
                    $arrayPlan = [
                        'uuid_plan' => Uuid::uuid1(),
                        'codigo_contable_plan' => '1.01.315.' . $secuencialCliente,
                        'nombre_cuenta_plan' => $persona->nombre_persona != '' ? $persona->nombre_persona . ' ' . $persona->apellido_persona : ($persona->razon_social != '' ? $persona->razon_social : $persona->nombre_comercial),
                        'clase_contable_plan' => 1,
                        'grupo_contable_plan' => 1,
                        'cuenta_contable_plan' => 315,
                        'auxiliar_contable_plan' => $secuencialCliente,
                        'id_empresa_plan' => $idEmpresa,
                        'id_usuario_creacion_plan' => session('idUsuario'),
                        'id_usuario_modificacion_plan' => session('idUsuario'),
                    ];
                    $idPlan = PlanCuenta::insertGetId($arrayPlan);
                    $arrayCliente = [
                        'uuid_cliente' => Uuid::uuid1(),
                        'id_persona_cliente' => $persona->id_persona,
                        'id_empresa_cliente' => $idEmpresa,
                        'id_tipo_cliente' => 14,
                        'valor_compra_cliente' => 1,
                        'secuencial_cliente' => $secuencialCliente,
                        'tipo_contable_cliente' => 'activo',
                        'cod_contable_cliente' => '1.01.315.' . str_pad($secuencialCliente, 6, 0, STR_PAD_LEFT),
                        'id_plan_cliente' => $idPlan
                    ];
                    $idCliente = Clientes::insertGetId($arrayCliente);
                }
            } else {
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $arrayErrores = [
                'Error' => $e->getMessage(),
                'Linea' => $e->getLine()
            ];
            return json_encode($arrayErrores);
        }
        return $idCliente;
    }
    public static function importarClientes()
    {
        $clienteImp = DB::connection('FAC')
            ->table('bm_cliente as c')
            ->selectRaw('
        GROUP_CONCAT(c.id_cliente limit 1) as id_cliente,
        GROUP_CONCAT(c.nombre limit 1) as nombre,
        GROUP_CONCAT(c.apellido limit 1) as apellido,
        GROUP_CONCAT(c.ci limit 1) as ci,
        GROUP_CONCAT(c.direccion limit 1) as direccion,
        GROUP_CONCAT(c.id_ciudad limit 1) as id_ciudad,
        GROUP_CONCAT(c.telefono limit 1) as telefono,
        GROUP_CONCAT(c.celular limit 1) as celular,
        GROUP_CONCAT(c.email limit 1) as email
        ')
            ->where('id_cliente', '<>', 0)
            ->where('ci', '<>', '')
            ->where('ci', '<>', 0)
            ->where('importFact', 0)
            ->groupBy('ci')
            ->limit(300)
            ->get();
        if (count($clienteImp) > 0) {
            DB::beginTransaction();
            try {
                $cont = 0;
                foreach ($clienteImp as $row) {
                    $persona = Personas::where('identificacion_persona', $row->ci)->first();
                    if ($persona == '') {
                        Personas::insert([
                            'uuid_persona' => Uuid::uuid1(),
                            'nombre_persona' => $row->nombre,
                            'apellido_persona' => $row->apellido,
                            'identificacion_persona' => $row->ci,
                            'telefono_persona' => str_replace('*', '', $row->telefono),
                            'celular_persona' => $row->celular,
                            'email_persona' => $row->email,
                            'direccion_persona' => $row->direccion,
                            'id_ciudad_persona' => (int)$row->id_ciudad,
                            'id_usuario_creacion_persona' => session('idUsuario'),
                            'id_usuario_modificacion_persona' => session('idUsuario'),
                        ]);
                        $cont++;
                        echo $cont . '<br/>';
                    }
                    DB::connection('FAC')
                        ->table('bm_cliente')
                        ->where('id_cliente', $row->id_cliente)
                        ->update([
                            'importFact' => 1
                        ]);
                }
                DB::commit();
                if ($cont == 0)
                    echo 'No hay clientes nuevos';
            } catch (Exception $e) {
                DB::rollBack();
                echo 'Error: ' . $e->getMessage() . '<br/>';
                echo 'Línea: ' . $e->getLine() . '<br/>';
            }
        }
    }
    public static function getDetalleCartera($id)
    {
        DB::enableQueryLog();
        return VentasCabecera::selectRaw('
            GROUP_CONCAT(db_cabecera_ventas.id_cliente_venta_cabecera limit 1) as id_cliente_venta_cabecera,
            GROUP_CONCAT(db_cabecera_ventas.id_venta_cabecera limit 1) as id_venta_cabecera,
            GROUP_CONCAT(db_cabecera_ventas.establecimiento_venta_cabecera limit 1) as establecimiento_venta_cabecera,
            GROUP_CONCAT(db_cabecera_ventas.emision_venta_cabecera limit 1) as emision_venta_cabecera,
            GROUP_CONCAT(db_cabecera_ventas.num_factura_venta_cabecera limit 1) as num_factura_venta_cabecera,
            GROUP_CONCAT(db_cabecera_ventas.fecha_emision_venta_cabecera limit 1) as fecha_emision_venta_cabecera,
            GROUP_CONCAT(IFNULL(db_cabecera_ventas.total_venta_cabecera,0) limit 1) as total_venta_cabecera,
            SUM(IFNULL(valor_fuente_retencion_cabecera_cliente,0))+SUM(IFNULL(valor_renta_retencion_cabecera_cliente,0)) as retenciones,
            GROUP_CONCAT(IFNULL(nc.total_venta_cabecera,0) limit 1) as notascredito,
            GROUP_CONCAT(per.nombre_persona limit 1) as nombre_persona,
            GROUP_CONCAT(per.apellido_persona limit 1) as apellido_persona,
            GROUP_CONCAT(per.identificacion_persona limit 1) as identificacion_persona,
            GROUP_CONCAT(per.direccion_persona limit 1) as direccion_persona,
            GROUP_CONCAT(per.telefono_persona limit 1) as telefono_persona,
            GROUP_CONCAT(per.celular_persona limit 1) as celular_persona,
            GROUP_CONCAT(per.email_persona limit 1) as email_persona,
            GROUP_CONCAT(IFNULL(DATEDIFF(' . date('Y-m-d') . ',db_cabecera_ventas.fecha_emision_venta_cabecera),0) limit 1) as dias,
            SUM(IFNULL(c.valor_cobro,0)) AS totalcobros
        ')
            ->leftjoin('db_cabecera_retenciones_cliente as r', 'db_cabecera_ventas.id_cliente_venta_cabecera', 'r.id_cliente_retencion_cabecera_cliente')
            ->leftjoin('db_cabecera_ventas as nc', function ($nc) use ($id) {
                $nc->where('nc.id_cliente_venta_cabecera', $id)
                    ->where('nc.motivo_venta_cabecera', 'NOTACREDITO');
            })
            ->leftjoin('db_cobros as c', function ($co) use ($id) {
                $co->where('db_cabecera_ventas.id_cliente_venta_cabecera', $id);
                //$co->where('db_cabecera_ventas.id_venta_cabecera', 'c.id_venta_cabecera_cobro');
                $co->where('db_cabecera_ventas.pagado_venta_cabecera', 1);
            })
            ->leftjoin('db_clientes as cl', 'db_cabecera_ventas.id_cliente_venta_cabecera', 'cl.id_cliente')
            ->leftjoin('db_personas as per', 'cl.id_persona_cliente', 'per.id_persona')
            ->where('db_cabecera_ventas.id_cliente_venta_cabecera', $id)
            ->where('db_cabecera_ventas.establecimiento_venta_cabecera', session('establecimiento'))
            ->where('db_cabecera_ventas.emision_venta_cabecera', session('emision'))
            ->where('db_cabecera_ventas.motivo_venta_cabecera', 'VENTA')
            ->groupBy('db_cabecera_ventas.num_factura_venta_cabecera')
            ->get();
        echo '<pre>';
        print_r(DB::getQueryLog());
        exit;
    }
    public static function getCartera()
    {
        return Clientes::selectRaw('GROUP_CONCAT(db_clientes.id_cliente LIMIT 1) AS codigo,
        GROUP_CONCAT(CONCAT(per.apellido_persona," ",per.nombre_persona) LIMIT 1) as cliente,
        GROUP_CONCAT(per.identificacion_persona LIMIT 1) as identificacion,
        SUM(IFNULL(cob.valor_saldo_cobro,0)) AS saldo')
            ->leftjoin('db_cabecera_ventas as v', function ($q) {
                $q->on('db_clientes.id_cliente', 'v.id_cliente_venta_cabecera')
                    ->where('v.pagado_venta_cabecera', 0);
            })
            ->leftjoin('db_cobros as cob', 'db_clientes.id_cliente', 'cob.id_cliente_cobro')
            ->leftjoin('db_personas as per', 'db_clientes.id_persona_cliente', 'per.id_persona')
            ->where('id_empresa_cliente', session('idEmpresa'))
            ->groupBy('db_clientes.id_cliente')
            ->orderBy('per.apellido_persona')
            ->orderBy('per.nombre_persona')
            ->get();
    }
    public static function setSecuencialCliente($idEmpresa = '')
    {
        if ($idEmpresa == '')
            $max = Clientes::where('id_empresa_cliente', session('idEmpresa'))->max('secuencial_cliente');
        else
            $max = Clientes::where('id_empresa_cliente', $idEmpresa)->max('secuencial_cliente');
        $max += 1;
        return str_pad($max, 6, 0, STR_PAD_LEFT);
    }
    public static function asignarCliente($id)
    {
        $cliente = Clientes::where('id_persona_cliente', $id)
            ->where('id_empresa_cliente', session('idEmpresa'))
            ->first();
        $secuencialCliente = Clientes::setSecuencialCliente();
        if ($cliente == '') {
            $persona = Personas::where('id_persona', $id)->first();
            $arrayPlan = [
                'uuid_plan' => Uuid::uuid1(),
                'codigo_contable_plan' => '1.01.315.' . $secuencialCliente,
                'nombre_cuenta_plan' => $persona->nombre_persona != '' ? $persona->nombre_persona . ' ' . $persona->apellido_persona : ($persona->razon_social_persona != '' ? $persona->razon_social_persona : $persona->nombre_comercial_persona),
                'clase_contable_plan' => 1,
                'grupo_contable_plan' => 1,
                'cuenta_contable_plan' => 315,
                'auxiliar_contable_plan' => $secuencialCliente,
                'id_empresa_plan' => session('idEmpresa'),
                'id_usuario_creacion_plan' => session('idUsuario'),
                'id_usuario_modificacion_plan' => session('idUsuario'),
            ];
            $idPlan = PlanCuenta::insertGetId($arrayPlan);
            $arrayCliente = [
                'uuid_cliente' => Uuid::uuid1(),
                'id_persona_cliente' => $id,
                'id_empresa_cliente' => session('idEmpresa'),
                'id_tipo_cliente' => 14,
                'valor_compra_cliente' => 1,
                'secuencial_cliente' => $secuencialCliente,
                'tipo_contable_cliente' => 'activo',
                'cod_contable_cliente' => '1.01.315.' . $secuencialCliente,
                'id_plan_cliente' => $idPlan
            ];
            $idCliente = Clientes::insertGetId($arrayCliente);
            DB::enableQueryLog();
            return Clientes::selectRaw('CONCAT(id_cliente,"|",valor_compra_cliente,"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|c") as id, IF(p.nombre_persona!="",CONCAT(p.nombre_persona," ",p.apellido_persona),IF(p.razon_social_persona!="",p.razon_social_persona,p.nombre_comercial_persona)) AS text')
                ->leftjoin('db_personas as p', 'id_persona_cliente', '=', 'p.id_persona')
                ->where('id_cliente', $idCliente)
                ->first();
        }
    }
    public static function comboClienteSeleccionado($id = '')
    {
        $combo = '';
        $cliente = Clientes::selectRaw('CONCAT(id_cliente,"|",valor_compra_cliente,"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,"")) as id, CONCAT(p.nombre_persona," ",p.apellido_persona," | ",p.identificacion_persona) as text')
            ->leftjoin('db_personas as p', 'id_persona_cliente', '=', 'p.id_persona')
            ->where('id_cliente', $id)
            ->first();
        if ($cliente != '') {
            $combo .= '<option value="' . $cliente->id . '" selected>' . $cliente->text . '</option>';
        }
        return $combo;
    }
    public static function clientesLineajs($r)
    {
        try {
            $termino = $r['term'];
            $cliente = Clientes::selectRaw('CONCAT(id_cliente,"|",valor_compra_cliente,"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|c","|",p.identificacion_persona) as id, CONCAT(IF(p.nombre_persona!="",CONCAT(p.nombre_persona," ",p.apellido_persona),IF(p.razon_social_persona!="",p.razon_social_persona,p.nombre_comercial_persona))," | ",p.identificacion_persona) as text')
                ->leftjoin('db_personas as p', 'id_persona_cliente', '=', 'p.id_persona')
                ->where('id_empresa_cliente', session('idEmpresa'))
                ->where(function ($q) use ($termino) {
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(p.nombre_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(p.apellido_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('p.identificacion_persona like ?', '%' . strtolower($termino) . '%');
                })
                ->get();
            if (count($cliente) == 0) {
                //$cliente = Personas::selectRaw('CONCAT(id_persona,"|",1,"|",telefono_persona,"|",celular_persona,"|",email_persona) as id, CONCAT(IFNULL(nombre_persona,razon_social_persona)," ",IFNULL(apellido_persona,nombre_comercial_persona)," | ",identificacion_persona) as text')
                $cliente = Personas::selectRaw('CONCAT(id_persona,"|",1,"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|p","|",identificacion_persona) as id, CONCAT(IF(nombre_persona!="",CONCAT(nombre_persona," ",apellido_persona),IF(razon_social_persona!="",razon_social_persona,nombre_comercial_persona))," | ",identificacion_persona) as text')
                    ->where(function ($q) use ($termino) {
                        $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(nombre_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                        $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(apellido_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                        $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(razon_social_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                        $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(nombre_comercial_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                        $q->orWhereRaw('identificacion_persona like ?', '%' . strtolower($termino) . '%');
                    })
                    ->get();
            }
            return $cliente;
        } catch (Exception $e) {
            $arrayErrores = [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
            ];
            return $arrayErrores;
        }
    }
    public static function saveClienteNuevo($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        $id = 0;
        $idTipoIdentificacion = explode('|', $datos['id_tipo_identificacion_persona']);
        $arrayPersona = [
            'nombre_persona' => $datos['nombre_persona'],
            'apellido_persona' => $datos['apellido_persona'],
            'id_tipo_identificacion_persona' => $idTipoIdentificacion[0],
            'identificacion_persona' => $datos['identificacion_persona'],
            'telefono_persona' => $datos['telefono_persona'],
            'celular_persona' => $datos['celular_persona'],
            'email_persona' => $datos['email_persona'],
            'direccion_persona' => $datos['direccion_persona'],
            'id_usuario_creacion_persona' => session('idUsuario'),
            'id_usuario_modificacion_persona' => session('idUsuario')
        ];
        DB::beginTransaction();
        try {
            if ($datos['id_persona'] != '') {
                $cont++;
                $cat = Personas::where('id_persona', $datos['id_persona'])->first(['uuid_persona']);
                if ($cat == '') {
                    $arrayPersona['uuid_persona'] = Uuid::uuid1();
                    $arrayPersona['id_usuario_modificacion_persona'] = session('idUsuario');
                }
                Personas::where('id_persona', $datos['id_persona'])->update($arrayPersona);
                $arrayCliente = [
                    'id_tipo_cliente' => 14,
                    'estado_cliente' => 1,
                    'valor_compra_cliente' => 1,
                ];
                Clientes::where('id_persona_cliente',  $datos['id_persona'])
                    ->update($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = Clientes::$modelo;
                $r->o = 'Se actualizo el catalogo No.: ' . $datos['id_persona'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $arrayPersona['uuid_persona'] = Uuid::uuid1();
                $arrayPersona['id_usuario_creacion_persona'] = session('idUsuario');
                $arrayPersona['created_at_persona'] = date('Y-m-d H:i:s');
                $id = Personas::insertGetId($arrayPersona);
                $secuencialCliente = Clientes::setSecuencialCliente();
                $arrayPlan = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => '1.01.315.' . $secuencialCliente,
                    'nombre_cuenta_plan' => $datos['nombre_persona'] . ' ' . $datos['apellido_persona'],
                    'clase_contable_plan' => 1,
                    'grupo_contable_plan' => 1,
                    'cuenta_contable_plan' => 315,
                    'auxiliar_contable_plan' => $secuencialCliente,
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPlan = PlanCuenta::insertGetId($arrayPlan);
                $arrayCliente = [
                    'uuid_cliente' => Uuid::uuid1(),
                    'id_persona_cliente' => $id,
                    'id_tipo_cliente' => 14,
                    'id_empresa_cliente' => session('idEmpresa'),
                    'estado_cliente' => 1,
                    'created_at_cliente' => date('Y-m-d H:i:s'),
                    'valor_compra_cliente' => 1,
                    'secuencial_cliente' => $secuencialCliente,
                    'tipo_contable_cliente' => 'activo',
                    'cod_contable_cliente' => '1.01.315',
                    'id_plan_cliente' => $idPlan
                ];
                Clientes::insert($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = Clientes::$modelo;
                $r->o = 'Se creo un nuevo catalogo';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage(), 'linea: ' => $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...|' . $id);
            return json_encode($result);
        }
    }
    public static function saveCliente($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        $arrayPersona = [
            'nombre_persona' => $datos['nombre_persona'],
            'apellido_persona' => $datos['apellido_persona'],
            'id_tipo_identificacion_persona' => $datos['id_tipo_identificacion_persona'],
            'identificacion_persona' => $datos['identificacion_persona'],
            'telefono_persona' => $datos['telefono_persona'],
            'celular_persona' => $datos['celular_persona'],
            'email_persona' => $datos['email_persona'],
            'direccion_persona' => $datos['direccion_persona'],
            'id_ciudad_persona' => $datos['id_ciudad_persona'],
            'fecha_nacimiento_persona' => $datos['fecha_nacimiento_persona'],
            'id_genero_persona' => $datos['id_genero_persona'],
            'referencia_domicilio_persona' => $datos['referencia_domicilio_persona'],
            'urbanizacion_persona' => $datos['urbanizacion_persona'],
            'etapa_persona' => $datos['etapa_persona'],
            'mz_persona' => $datos['mz_persona'],
            'villa_persona' => $datos['villa_persona'],
            'nombre_empresa_persona' => $datos['nombre_empresa_persona'],
            'ruc_empresa_persona' => $datos['ruc_empresa_persona'],
            'fecha_ingreso_empresa_persona' => $datos['fecha_ingreso_empresa_persona'],
            'sueldo_empresa_persona' => $datos['sueldo_empresa_persona'],
            'telefono_empresa_persona' => $datos['telefono_empresa_persona'],
            'celular_empresa_persona' => $datos['celular_empresa_persona'],
            'id_ciudad_empresa_persona' => $datos['id_ciudad_empresa_persona'],
            'direccion_empresa_persona' => $datos['direccion_empresa_persona'],
            'urbanizacion_empresa_persona' => $datos['urbanizacion_empresa_persona'],
            'etapa_empresa_persona' => $datos['etapa_empresa_persona'],
            'mz_empresa_persona' => $datos['mz_empresa_persona'],
            'villa_empresa_persona' => $datos['villa_empresa_persona'],
            'referencia_empresa_direccion_persona' => $datos['referencia_empresa_direccion_persona'],
        ];
        DB::beginTransaction();
        try {
            if ($datos['id_persona'] != '') {
                $cont++;
                $cat = Personas::where('id_persona', $datos['id_persona'])->first(['uuid_persona']);
                if ($cat == '') {
                    $arrayPersona['uuid_persona'] = Uuid::uuid1();
                    $arrayPersona['id_usuario_modificacion_persona'] = session('idUsuario');
                }
                Personas::where('id_persona', $datos['id_persona'])->update($arrayPersona);
                $arrayCliente = [
                    'id_tipo_cliente' => $datos['id_tipo_cliente'],
                    'estado_cliente' => $datos['estado_cliente'],
                    'valor_compra_cliente' => $datos['valor_compra_cliente'],
                    'observacion_cliente' => $datos['observacion_cliente']
                ];
                Clientes::where('id_persona_cliente',  $datos['id_persona'])
                    ->update($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = Clientes::$modelo;
                $r->o = 'Se actualizo el catalogo No.: ' . $datos['id_persona'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $arrayPersona['uuid_persona'] = Uuid::uuid1();
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
                Clientes::insert($arrayCliente);
                $r->c = 'CRM';
                $r->s = 'saveCliente';
                $r->d = $origin['d'];
                $r->m = Clientes::$modelo;
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
    public static function getClientes($id = '')
    {
        if ($id == '') {
            return Clientes::selectRaw(
                'id_cliente,
                IF(nombre_persona!="",nombre_persona,razon_social_persona) as nombre_persona,
                IF(apellido_persona!="",apellido_persona,razon_social_persona) as apellido_persona,
                    identificacion_persona,
                    telefono_persona,
                    celular_persona,
                    email_persona,
                    direccion_persona,
                    created_at_persona,
                    updated_at_persona,
                    estado_cliente'
            )
                ->join('db_personas as p', 'db_clientes.id_persona_cliente', '=', 'p.id_persona')
                ->where('id_empresa_cliente', session('idEmpresa'))
                ->get();
        } else {
            return Clientes::selectRaw(
                'id_cliente,
                    uuid_persona,
                    id_persona,
                    IF(nombre_persona!="",nombre_persona,razon_social_persona) as nombre_persona,
                    IF(apellido_persona!="",apellido_persona,razon_social_persona) as apellido_persona,
                    cod_contable_cliente,
                    identificacion_persona,
                    telefono_persona,
                    celular_persona,
                    email_persona,
                    direccion_persona,
                    created_at_persona,
                    updated_at_persona,
                    estado_cliente as estado_persona,
                    id_empresa_cliente as id_empresa_persona,
                    id_tipo_identificacion_persona,
                    fecha_nacimiento_persona,
                    id_genero_persona,
                    id_tipo_cliente as id_tipo_cliente,
                    direccion_persona,
                    referencia_domicilio_persona,
                    urbanizacion_persona,
                    etapa_persona,
                    mz_persona,
                    villa_persona,
                    c.id_ciudad,
                    c.id_provincia_ciudad,
                    c.id_pais_ciudad,
                    ce.id_ciudad as id_ciudad_empresa,
                    ce.id_provincia_ciudad as id_provincia_ciudad_empresa,
                    ce.id_pais_ciudad as id_pais_ciudad_empresa,
                    valor_compra_cliente as valor_compra_persona,
                    observacion_cliente as observacion_persona,
                    tipoIdentificacion.valor_catalogo as tipo_identificacion_persona,
                    nombre_empresa_persona,
                    ruc_empresa_persona,
                    fecha_ingreso_empresa_persona,
                    sueldo_empresa_persona,
                    telefono_empresa_persona,
                    celular_empresa_persona,
                    referencia_empresa_direccion_persona,
                    direccion_empresa_persona,
                    urbanizacion_empresa_persona,
                    etapa_empresa_persona,
                    mz_empresa_persona,
                    villa_empresa_persona',
            )
                ->join('db_personas as p', 'db_clientes.id_persona_cliente', '=', 'p.id_persona')
                ->leftjoin('db_ciudades as c', 'p.id_ciudad_persona', '=', 'c.id_ciudad')
                ->leftjoin('db_ciudades as ce', 'p.id_ciudad_empresa_persona', '=', 'ce.id_ciudad')
                ->leftjoin('db_catalogos as tipoIdentificacion', 'p.id_tipo_identificacion_persona', '=', 'tipoIdentificacion.id_catalogo')
                ->where('id_cliente', $id)->first();
        }
    }
}
