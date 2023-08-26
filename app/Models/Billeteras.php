<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Billeteras extends Model
{
    protected $table = 'db_billeteras';
    protected $primaryKey  = 'id_billetera';
    const CREATED_AT = 'created_at_billetera';
    const UPDATED_AT = 'updated_at_billetera';

    private static $modelo = 'Billetera';

    public static function validarCuenta($r)
    {
        return Billeteras::join('db_usuarios as u', 'db_billeteras.id_cliente_billetera',  'u.id_usuario')
            ->where('numero_cuenta_billetera', $r->num_cuenta)
            ->first([
                'nombre_usuario',
                'apellido_usuario'
            ]);
    }
    public static function getSaldos()
    {
        return Usuarios::selectRaw("IFNULL(SUM(ingreso_movimiento),0) AS ingreso,
        IFNULL(SUM(egreso_movimiento),0) AS egreso,
        IFNULL(SUM(ingreso_movimiento),0) +IFNULL(SUM(egreso_movimiento),0) AS saldo")
            ->leftjoin('db_movimientos_billetera as m', 'db_usuarios.id_usuario', 'm.id_cliente_movimiento')
            ->where('id_usuario', session('idUsuario'))
            ->first();
    }
    public static function asignarUsuarioBilletera($id)
    {
        $usuario = Usuarios::where('identificacion_usuario', $id)
            ->first();
        DB::beginTransaction();
        try {
            if ($usuario == '') {
                $persona = Personas::where('identificacion_persona', $id)->first();
                $arrayUsuario = [
                    'uuid_usuario' => Uuid::uuid1(),
                    'usuario' => $id,
                    'clave_usuario' => md5($id . Utilidades::keyLock() . $id),
                    'nombre_usuario' => $persona->nombre_persona != '' ? $persona->nombre_persona  : ($persona->razon_social_persona != '' ? $persona->razon_social_persona : $persona->nombre_comercial_persona),
                    'apellido_usuario' => $persona->apellido_persona != '' ? $persona->apellido_persona : ($persona->razon_social_persona != '' ? $persona->razon_social_persona : $persona->nombre_comercial_persona),
                    'identificacion_usuario' => $persona->identificacion_persona,
                    'fecha_nacimiento_usuario' => $persona->fecha_nacimiento_persona,
                    'email_usuario' => $persona->email_persona,
                    'direccion_usuario' => $persona->direccion_persona,
                    'telefono_usuario' => $persona->telefono_persona,
                    'celular_usuario' => $persona->celular_persona,
                    'id_ciudad_usuario' => $persona->id_ciudad_persona,
                    'id_genero_usuario' => $persona->id_genero_persona,
                    'id_usuario_creacion_usuario' => session('idUsuario'),
                    'id_usuario_modificacion_usuario' => session('idUsuario'),
                    'id_tipo_uso_usuario' => 747,
                ];
                $idUsuario = Usuarios::insertGetId($arrayUsuario);
                DB::commit();
                return Usuarios::selectRaw('CONCAT(id_usuario,"|",1,"|",IFNULL(telefono_usuario,""),"|",IFNULL(celular_usuario,""),"|",IFNULL(email_usuario,""),"|c","|",identificacion_usuario) as id, IF(nombre_usuario!="",CONCAT(nombre_usuario," ",apellido_usuario),"") AS text')
                    ->where('id_usuario', $idUsuario)
                    ->first();
            }
        } catch (Exception $e) {
            DB::rollBack();
            $arrayErrores = [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
            ];
            return $arrayErrores;
        }
    }
    public static function saveBilletera($r)
    {
        $datos = $r->input();
        $origin = $r->input();
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        try {
            $usuario = Usuarios::where('identificacion_usuario', $datos['identificacion'])->first();
            unset($datos['identificacion']);
            unset($datos['id_cliente_billetera']);
            $datos['id_cliente_billetera'] = $usuario->id_usuario;
            if ($datos['id_billetera'] != '') {
                $cont++;
                $cat = Billeteras::where('id_billetera', $datos['id_billetera'])->first();
                if ($cat == '') {
                    $datos['uuid_billetera'] = Uuid::uuid1();
                }
                $datos['id_usuario_modificacion_billetara'] = session('idUsuario');
                Billeteras::where('id_billetera',  $datos['id_billetera'])
                    ->update($datos);
                $r->c = 'Billeteras';
                $r->s = 'saveBilletera';
                $r->d = $origin['d'];
                $r->m = Billeteras::$modelo;
                $r->o = 'Se actualizo la billetera No.: ' . $datos['id_billetera'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_billetera'] = Uuid::uuid1();
                $datos['id_usuario_modificacion_billetera'] = session('idUsuario');
                Billeteras::insert($datos);
                $r->c = 'Billeteras';
                $r->s = 'saveBilletera';
                $r->d = $origin['d'];
                $r->m = Billeteras::$modelo;
                $r->o = 'Se creo una nueva billetera';
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
    public static function generarCuenta()
    {
        $cuenta = str_pad(rand(0, 9999999999), 10, 0, STR_PAD_LEFT);
        $val = Billeteras::where('numero_cuenta_billetera', $cuenta)->first();
        if ($val == '') {
            return $cuenta;
        } else {
            Billeteras::generarCuenta();
        }
    }
    public static function numControl()
    {
        $cuenta = Billeteras::generarCuenta();
        $arrayControl = [
            'control1' => rand(0, 9) . Utilidades::generateVerifyNumber(date('YmdHis')),
            'control2' => Utilidades::generateVerifyNumber(date('YmdHis')) . rand(0, 9),
            'entidad' => str_pad(1, 4, 0, STR_PAD_LEFT),
            'oficina' => str_pad(1, 4, 0, STR_PAD_LEFT),
            'cuenta' => $cuenta,
        ];
        return $arrayControl;
    }
    public static function getBilletera($id = '')
    {
        if ($id == '') {
            return Billeteras::join('db_usuarios as c', 'c.id_usuario', '=', 'db_billeteras.id_cliente_billetera')
                ->get([
                    'db_billeteras.id_billetera',
                    'db_billeteras.uuid_billetera',
                    'db_billeteras.codigo_pais_billetera',
                    'db_billeteras.digito_control_1_billetera',
                    'db_billeteras.digito_control_2_billetera',
                    'db_billeteras.entidad_billetera',
                    'db_billeteras.oficina_billetera',
                    'db_billeteras.numero_cuenta_billetera',
                    'db_billeteras.estado_billetera',
                    'db_billeteras.id_cliente_billetera',
                    'db_billeteras.observacion_billetera',
                    'db_billeteras.id_usuario_creacion_billetera',
                    'db_billeteras.id_usuario_modificacion_billetera',
                    'db_billeteras.created_at_billetera',
                    'db_billeteras.updated_at_billetera',
                    'c.identificacion_usuario as identificacion_billetera',
                    'c.nombre_usuario as nombre_cliente_billetera',
                    'c.telefono_usuario as telefono_cliente_billetera',
                    'c.celular_usuario as celular_cliente_billetera',
                    'c.email_usuario as email_cliente_billetera',
                    'c.direccion_usuario as direccion_cliente_billetera',
                ]);
        } else {
            return Billeteras::find($id);
        }
    }
}
