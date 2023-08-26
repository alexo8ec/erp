<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Proveedores extends Model
{
    protected $table = 'db_proveedores';
    protected $primaryKey  = 'id_proveedor';
    const CREATED_AT = 'created_at_proveedor';
    const UPDATED_AT = 'updated_at_proveedor';

    public static function getProveedores($id = '')
    {
        $proveedor = Proveedores::leftjoin('db_personas as p', 'id_persona_proveedor', '=', 'p.id_persona')
            ->where(function ($q) use ($id) {
                if ($id != '') {
                    $q->where('id_proveedor', $id);
                }
            })
            ->where('id_empresa_proveedor', session('idEmpresa'));
        if ($id != '')
            $proveedor = $proveedor->first();
        else
            $proveedor = $proveedor->get();
        return $proveedor;
    }
    public static function totalProveedores()
    {
        return Proveedores::selectRaw('count(id_proveedor) as total')
            ->where('id_empresa_proveedor', session('idEmpresa'))
            ->first();
    }
    public static function importarProveedores()
    {
        $proveedorImp = DB::connection('FAC')
            ->table('bm_proveedor as p')
            ->selectRaw('
        GROUP_CONCAT(p.id_proveedor limit 1) as id_proveedor,
        GROUP_CONCAT(p.proveedor limit 1) as proveedor,
        GROUP_CONCAT(p.ci_ruc limit 1) as ci_ruc,
        GROUP_CONCAT(p.id_ciudad limit 1) as id_ciudad,
        GROUP_CONCAT(p.direccion limit 1) as direccion,
        GROUP_CONCAT(p.telefono limit 1) as telefono,
        GROUP_CONCAT(p.celular limit 1) as celular,
        GROUP_CONCAT(p.email limit 1) as email,
        GROUP_CONCAT(p.id_tipo_identificacion limit 1) as id_tipo_identificacion
        ')
            ->where('id_proveedor', '<>', 0)
            ->where('ci_ruc', '<>', '')
            ->where('importFact', 0)
            ->groupBy('ci_ruc')
            ->limit(200)
            ->get();
        if (count($proveedorImp) > 0) {
            DB::beginTransaction();
            try {
                $cont = 0;
                foreach ($proveedorImp as $row) {
                    $persona = Personas::where('identificacion_persona', $row->ci_ruc)->first();
                    if ($persona == '') {
                        Personas::insert([
                            'uuid_persona' => Uuid::uuid1(),
                            'razon_social_persona' => $row->proveedor,
                            'nombre_comercial_persona' => $row->proveedor,
                            'identificacion_persona' => $row->ci_ruc,
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
                        ->table('bm_proveedor')
                        ->where('id_proveedor', $row->id_proveedor)
                        ->update([
                            'importFact' => 1
                        ]);
                }
                DB::commit();
                if ($cont == 0)
                    echo 'No hay proveedores nuevos';
            } catch (Exception $e) {
                DB::rollBack();
                echo 'Error: ' . $e->getMessage() . '<br/>';
                echo 'Línea: ' . $e->getLine() . '<br/>';
            }
        }
    }
    public static function asignarProveedor($id, $comprobante = '')
    {
        DB::beginTransaction();
        try {
            $proveedor = Proveedores::where('id_persona_proveedor', $id)
                ->where('id_empresa_proveedor', session('idEmpresa'))
                ->first();
            $secuencialProveedor = Proveedores::setSecuencialProveedor();
            if ($proveedor == '') {
                $persona = Personas::where('id_persona', $id)->first();
                $nombrePersona = $persona->nombre_persona != '' ? $persona->nombre_persona . ' ' . $persona->apellido_persona : ($persona->razon_social_persona != '' ? $persona->razon_social_persona : $persona->nombre_comercial_persona);
                $arrayPlan = [
                    'uuid_plan' => Uuid::uuid1(),
                    'codigo_contable_plan' => '2.01.413.' . $secuencialProveedor,
                    'nombre_cuenta_plan' => $nombrePersona,
                    'clase_contable_plan' => 2,
                    'grupo_contable_plan' => 2,
                    'cuenta_contable_plan' => 413,
                    'auxiliar_contable_plan' => $secuencialProveedor,
                    'id_empresa_plan' => session('idEmpresa'),
                    'id_usuario_creacion_plan' => session('idUsuario'),
                    'id_usuario_modificacion_plan' => session('idUsuario'),
                ];
                $idPlan = PlanCuenta::insertGetId($arrayPlan);
                $arrayProveedor = [
                    'uuid_proveedor' => Uuid::uuid1(),
                    'id_persona_proveedor' => $id,
                    'id_empresa_proveedor' => session('idEmpresa'),
                    'obligado_contabilidad_proveedor' => isset($comprobante->infoFactura->obligadoContabilidad) ? ($comprobante->infoFactura->obligadoContabilidad == 'SI' ? 1 : 0) : 0,
                    'contribuyente_especial_proveedor' => isset($comprobante->infoFactura->contribuyenteEspecial) ? 1 : 0,
                    'num_contribuyente_especial_proveedor' => isset($comprobante->infoFactura->contribuyenteEspecial) ? $comprobante->infoFactura->contribuyenteEspecial : 0,
                    'tipo_contable_proveedor' => 'pasivo',
                    'cod_plan_proveedor' => '2.01.413.' . $secuencialProveedor,
                    'id_plan_proveedor' => $idPlan,
                    'secuencial_proveedor' => $secuencialProveedor,
                ];
                $idProveedor = Proveedores::insertGetId($arrayProveedor);
                DB::commit();
                return Proveedores::selectRaw('CONCAT(id_proveedor,"|",IFNULL(secuencial_proveedor,0),"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|c") as id, CONCAT(IFNULL(nombre_persona,IFNULL(razon_social_persona,""))," ",IFNULL(apellido_persona,IFNULL(nombre_comercial_persona,""))," | ",identificacion_persona) as text')
                    ->leftjoin('db_personas as p', 'id_persona_proveedor', '=', 'p.id_persona')
                    ->where('id_proveedor', $idProveedor)
                    ->first();
            } else {
                $plan = PlanCuenta::where('id_empresa_plan', session('idEmpresa'))
                    ->where('codigo_contable_plan', $proveedor->cod_plan_proveedor)
                    ->first();
                if ($plan == '') {
                    $persona = Personas::where('id_persona', $id)->first();
                    $nombrePersona = $persona->nombre_persona != '' ? $persona->nombre_persona . ' ' . $persona->apellido_persona : ($persona->razon_social_persona != '' ? $persona->razon_social_persona : $persona->nombre_comercial_persona);
                    $arrayPlan = [
                        'uuid_plan' => Uuid::uuid1(),
                        'codigo_contable_plan' => $proveedor->cod_plan_proveedor,
                        'nombre_cuenta_plan' => $nombrePersona,
                        'clase_contable_plan' => 2,
                        'grupo_contable_plan' => 2,
                        'cuenta_contable_plan' => 413,
                        'auxiliar_contable_plan' => $proveedor->auxiliar_contable_plan,
                        'id_empresa_plan' => session('idEmpresa'),
                        'id_usuario_creacion_plan' => session('idUsuario'),
                        'id_usuario_modificacion_plan' => session('idUsuario'),
                    ];
                    $idPlan = PlanCuenta::insertGetId($arrayPlan);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $arrayDatos = [
                'status' => false,
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ];
            return json_encode($arrayDatos);
        }
    }
    public static function ProveedoresLineajs($r)
    {
        $termino = $r['term'];
        $proveedor = Proveedores::selectRaw('CONCAT(id_proveedor,"|",IFNULL(secuencial_proveedor,0),"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|c") as id, CONCAT(IFNULL(nombre_persona,IFNULL(razon_social_persona,""))," ",IFNULL(apellido_persona,IFNULL(nombre_comercial_persona,""))," | ",identificacion_persona) as text')
            ->leftjoin('db_personas as p', 'id_persona_proveedor', '=', 'p.id_persona')
            ->where('id_empresa_proveedor', session('idEmpresa'))
            ->where(function ($q) use ($termino) {
                $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(p.nombre_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(p.apellido_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                $q->orWhereRaw('p.identificacion_persona like ?', '%' . strtolower($termino) . '%');
            })
            ->get();
        if (count($proveedor) == 0) {
            $proveedor = Personas::selectRaw('CONCAT(id_persona,"|",1,"|",IFNULL(telefono_persona,""),"|",IFNULL(celular_persona,""),"|",IFNULL(email_persona,""),"|p") as id, CONCAT(IFNULL(nombre_persona,IFNULL(razon_social_persona,""))," ",IFNULL(apellido_persona,IFNULL(nombre_comercial_persona,""))," | ",identificacion_persona) as text')
                ->where(function ($q) use ($termino) {
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(nombre_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(apellido_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(razon_social_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(nombre_comercial_persona),"á","a"),"é","e"),"í","i"),"ó","o"),"ú","u"),"ü","u") like ?', '%' . strtolower($termino) . '%');
                    $q->orWhereRaw('identificacion_persona like ?', '%' . strtolower($termino) . '%');
                })
                ->get();
        }
        return $proveedor;
    }
    public static function setSecuencialProveedor()
    {
        $max = Proveedores::where('id_empresa_proveedor', session('idEmpresa'))->max('secuencial_proveedor');
        $max += 1;
        return str_pad($max, 6, 0, STR_PAD_LEFT);
    }
}
