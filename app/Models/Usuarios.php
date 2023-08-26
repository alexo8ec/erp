<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuarios extends Model
{
    protected $table = 'db_usuarios';
    protected $primaryKey  = 'id_usuario';
    const CREATED_AT = 'created_at_usuario';
    const UPDATED_AT = 'updated_at_usuario';

    public static function cambiarClaveUsuario($r)
    {
        $mensaje = '';
        $usuario = Usuarios::where('id_usuario', session('idUsuario'))->first();
        $clave = $r->clave_usuario;
        $clave_ = $r->clave_usuario_;
        if ($clave != $clave_)
            $mensaje = 'danger|Las contraseñas deben coincidir';
        elseif (is_numeric($clave))
            $mensaje = 'danger|La contraseña debe contener letras o signos';
        elseif (strlen($clave) < 6)
            $mensaje = 'danger|La contraseña debe contener minimo 6 dígitos';
        else {
            try {
                $arrayUsuario = [
                    'clave_usuario' => md5($usuario->usuario . Utilidades::keyLock() . $clave),
                    'clave_cambiada_usuario' => 1
                ];
                $usuario->where('id_usuario', $usuario->id_usuario)->update($arrayUsuario);
            } catch (Exception $e) {
                $mensaje = 'danger|Error al cambiar la clave, intente de nuevo ' . $e->getMessage() . ' linea: ' . $e->getLine();
            }
        }
        return $mensaje;
    }
    public static function resetearClave($r)
    {
        $result = '';
        $usuario = Usuarios::where('email_usuario', $r->email_usuario)->first();
        if ($usuario != '') {
            $nuevaClave = Utilidades::generaPass(10);
            $arrayUsuario = [
                'clave_usuario' => md5($usuario->usuario . Utilidades::keyLock() . $nuevaClave),
                'clave_cambiada_usuario' => 0
            ];
            DB::beginTransaction();
            try {
                $usuario->where('id_usuario', $usuario->id_usuario)->update($arrayUsuario);
                $mailData = [
                    'id_usuario' => $usuario->id_usuario,
                    'de' => 'info@facturalgo.com',
                    'nombre_de' => '',
                    'para' => $r->email_usuario,
                    'nombre_para' => $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario,
                    'copia' => '',
                    'nombre_copia' => '',
                    'copia_oculta' => '',
                    'nombre_copia_oculta' => '',
                    'firma' => '',
                    'prioridad' => 1,
                    'vista' => 'plantillas.email.view_envio_clave',
                    'subject' => 'Su contraseña ha sido reseteada',
                    'clave' => $nuevaClave,
                    'usuario' => $usuario->usuario,
                    'nombre' => $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario
                ];
                $result = 'success|su clave fue enviada a la direccion: ' . $usuario->email_usuario . ' - ' . $nuevaClave;
                $arrayEmail = [
                    'id_usuario_email' => $usuario->id_usuario,
                    'subject_email' => 'Su contraseña ha sido reseteada',
                    'body_email' => json_encode($mailData),
                    'destinatario_email' => $usuario->email_usuario,
                    'estado_email' => 1,
                    'id_usuario_creacion_email' => $usuario->id_usuario,
                    'id_usuario_modificacion_email' => $usuario->id_usuario,
                ];
                EnvioEmails::insert($arrayEmail);
                DB::commit();
            } catch (Exception $e) {
                $result = 'danger|Error al resetear su contraseña: ' . $e->getMessage() . ' Linea: ' . $e->getLine();
                DB::rollback();
            }
        } else {
            $result = 'danger|La dirección de correo no existe en la base de datos';
        }
        return $result;
    }
    public static function updateLogin($e, $origen = '', $id = '')
    {
        $id_usuario = session('idUsuario');
        $arrayUsuario = [
            'fecha_login_usuario' => date('Y-m-d H:i:s'),
            'estado_login_usuario' => $e
        ];
        if ($origen == 'API')
            $id_usuario = $id;
        Usuarios::where('id_usuario', $id_usuario)->update($arrayUsuario);
    }
    public static function getUsuario($id = '')
    {
        DB::enableQueryLog();
        $usuario = Usuarios::leftjoin('db_catalogos as cargo', 'db_usuarios.id_cargo_usuario', '=', 'cargo.id_catalogo')
            ->where(function ($q) use ($id) {
                if ($id != '') {
                    $q->where('db_usuarios.id_usuario', $id);
                } else {
                    $q->where('db_usuarios.id_empresas_usuario', 'like', '%' . session('idEmpresa') . '%');
                }
            });
        if ($id != '')
            $usuario = $usuario->leftjoin('db_ciudades as c', 'db_usuarios.id_ciudad_usuario', '=', 'c.id_ciudad')
                ->leftjoin('db_archivos as i', function ($q) {
                    $q->on('id_usuario', 'i.id_usuario_creacion_archivo')
                        ->where('i.tipo_archivo', '=', 'usuarioFoto');
                })->first([
                    'id_usuario',
                    'uuid_usuario',
                    'usuario',
                    'nombre_usuario',
                    'apellido_usuario',
                    'email_usuario',
                    'telefono_usuario',
                    'celular_usuario',
                    'identificacion_usuario',
                    'id_usuario_creacion_archivo',
                    'clave_cambiada_usuario',
                    'fecha_login_usuario',
                    'cargo.nombre_catalogo as cargo_usuario',
                    'id_cargo_usuario',
                    'c.nombre_ciudad',
                    'c.id_provincia_ciudad',
                    'c.id_pais_ciudad',
                    'id_ciudad_usuario',
                    'direccion_usuario',
                    'estado_usuario',
                    'ext_archivo',
                    'archivo',
                    'facebook_usuario',
                    'youtube_usuario',
                    'linkedin_usuario',
                    'twitter_usuario',
                    'acerca_usuario',
                    'id_usuario_creacion_usuario',
                    'id_usuario_modificacion_usuario',
                    'id_genero_usuario',
                    'fecha_nacimiento_usuario',
                    'id_empresas_usuario',
                    'id_tipo_uso_usuario',
                    'crea_cuentas_usuario'
                ]);
        else
            $usuario = $usuario->leftjoin('db_usuarios as u', 'db_usuarios.id_usuario_creacion_usuario', 'u.id_usuario')->get([
                'db_usuarios.id_usuario',
                'db_usuarios.usuario',
                'db_usuarios.nombre_usuario',
                'db_usuarios.apellido_usuario',
                'db_usuarios.identificacion_usuario',
                'db_usuarios.telefono_usuario',
                'db_usuarios.email_usuario',
                'db_usuarios.direccion_usuario',
                'db_usuarios.celular_usuario',
                'db_usuarios.created_at_usuario',
                'db_usuarios.updated_at_usuario',
                'db_usuarios.estado_usuario',
                'cargo.nombre_catalogo as cargo_usuario',
                'db_usuarios.id_empresas_usuario',
                'u.usuario as usuario_creacion'
            ]);
        return $usuario;
    }
    public static function getLogin($r, $origen = '')
    {
        $mensaje = '';
        $arrayResultado = [];
        $remember_me = $r->has('remember') ? true : false;
        $usuario = Usuarios::where('usuario', $r->usuario)->first();
        if ($origen == 'API') {
            if ($usuario != '') {
                if ($usuario->estado_usuario == 0) {
                    $arrayResultado['status'] = false;
                    $arrayResultado['code'] = 400;
                    $arrayResultado['description'] = 'danger|Usuario desactivado';
                } else {
                    $usuario = $usuario->leftjoin('db_archivos as a', 'db_usuarios.id_usuario', 'a.id_usuario_creacion_archivo')->where('clave_usuario', md5($r->usuario . Utilidades::keyLock() . $r->clave_usuario))->first(['db_usuarios.id_usuario', 'db_usuarios.usuario', 'db_usuarios.nombre_usuario', 'db_usuarios.apellido_usuario', 'db_usuarios.email_usuario', 'a.api_archivo as archivo']);
                    if ($usuario != '') {
                        $token = Utilidades::getJwt($usuario);
                        $saldos = Usuarios::selectRaw(
                            'IFNULL(SUM(ingreso_movimiento),0) AS ingreso,
                            IFNULL(SUM(egreso_movimiento),0) AS egreso,
                            IFNULL(SUM(ingreso_movimiento),0) +IFNULL(SUM(egreso_movimiento),0) AS saldo'
                        )
                            ->leftjoin('db_movimientos_billetera as m', 'db_usuarios.id_usuario', '=', 'm.id_cliente_movimiento')
                            ->where('db_usuarios.id_usuario', $usuario->id_usuario)
                            ->get();
                        $movimientos = MovimientosBilletera::where('id_cliente_movimiento', $usuario->id_usuario)->orderBy('fecha_movimiento', 'DESC')->limit(20)->get();
                        $arrayResultado['status'] = true;
                        $arrayResultado['code'] = 200;
                        $arrayResultado['description'] = 'success|Login correcto';
                        $arrayResultado['data'] = ['token' => $token, 'id' => $usuario->id_usuario, 'usuario' => $usuario->usuario, 'nombre' => $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario, 'saldos' => $saldos, 'movimientos' => $movimientos, 'avatar' => $usuario->archivo];
                        Usuarios::updateLogin(1, 'API', $usuario->id_usuario);
                    } else {
                        $arrayResultado['status'] = false;
                        $arrayResultado['code'] = 400;
                        $arrayResultado['description'] = 'danger|El usuario/contraseña son incorrectos';
                    }
                }
            } else {
                $arrayResultado['status'] = false;
                $arrayResultado['code'] = 401;
                $arrayResultado['description'] = 'danger|Usuario no existe en la base de datos';
            }
            return $arrayResultado;
        } else {
            if ($usuario != '') {
                if ($usuario->estado_usuario == 0) {
                    $mensaje = 'danger|Usuario desactivado';
                }
                $usuario = $usuario->where('clave_usuario', md5($r->usuario . Utilidades::keyLock() . $r->clave_usuario))->first(['id_usuario']);
                if ($usuario != '') {
                    session([
                        'idUsuario' => $usuario->id_usuario,
                        'periodo' => date('Y')
                    ]);
                    Usuarios::updateLogin(1);
                    $mensaje = '';
                } else {
                    $mensaje = 'danger|Usuario y/o contraseña incorrectos';
                }
            } else {
                $mensaje = 'danger|Usuario no existe en la base de datos';
            }
        }
        return $mensaje;
    }
    public static function apiLogout($r)
    {
        $arrayResultado = [];
        try {
            $arrayResultado['status'] = true;
            $arrayResultado['code'] = 200;
            $arrayResultado['description'] = 'success|Logout correcto';
            Usuarios::updateLogin(0, 'API', $r->id_usuario);
        } catch (Exception $e) {
            $arrayResultado['status'] = false;
            $arrayResultado['code'] = 500;
            $arrayResultado['description'] = 'error|Error al salir de la aplicación';
        }
        return $arrayResultado;
    }
}
