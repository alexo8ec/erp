<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EstadoEnvios extends Model
{
    public static function getDatos($r)
    {
        $info = Info::getInfo();
        $datos = EnvioEmails::where('estado_email', 1)->get();
        if (count($datos) > 0) {
            foreach ($datos as $row) {
                try {
                    if ($row->id_usuario_email != 0) {
                        $usuario = Usuarios::getUsuario($row->id_usuario_email);
                        $body_email = json_decode($row->body_email);
                        $messageData = [
                            'usuario' => $usuario,
                            'clave' => $body_email->clave
                        ];
                        $subject = 'Su contraseña ha sido reseteada correctamente | ' . $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario;
                        Mail::send('plantillas/email/view_resetear_clave', $messageData, function ($mail) use ($subject, $usuario, $info) {
                            $mail->from('noreply@facturalgo.com', 'Gestor de correos - ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info);
                            $mail->to(strtolower($usuario->email_usuario), $usuario->nombre_usuario . ' ' . $usuario->apellido_usuario);
                            $mail->subject($subject);
                        });
                        $arrayCorreo = [
                            'estado_email' => 0
                        ];
                        EnvioEmails::where('id_email', $row->id_email)->update($arrayCorreo);
                        echo 'Envio a ' . $usuario->usuario . ' Email: ' . $usuario->email_usuario . '<br/>';
                    }
                } catch (Exception $e) {
                    $arrayError = [
                        'error' => $e->getMessage(),
                        'linea' => $e->getLine()
                    ];
                    return json_encode($arrayError);
                }
            }
        }
    }
}
