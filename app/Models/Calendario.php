<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    protected $table = 'db_calendario';
    protected $primaryKey  = 'id_calendario';
    const CREATED_AT = 'created_at_calendario';
    const UPDATED_AT = 'updated_at_calendario';
    public static function getCalendario($r)
    {
        $datos = $r->registro;
        switch ($r->a) {
            case 'listar':
                return Calendario::where('id_empresa_calendario', session('idEmpresa'))
                    ->where('id_usuario_calendario', session('idUsuario'))
                    ->where('start', '>=', $r->start)
                    ->where('end', '<=', $r->end)
                    ->get([
                        'id_calendario',
                        'title',
                        'descripcion',
                        'start',
                        'end',
                        'textColor',
                        'backgroundColor'
                    ]);
                break;
            case 'modificar':
                $datos['id_empresa_calendario'] = session('idEmpresa');
                $datos['id_usuario_modificacion_calendario'] = session('idUsuario');
                Calendario::where('id_calendario', $datos['id_calendario'])->update($datos);
                return Calendario::where('id_empresa_calendario', session('idEmpresa'))
                    ->where('id_usuario_calendario', session('idUsuario'))
                    ->where('start', '>=', $datos['start'])
                    ->where('end', '<=', $datos['end'])
                    ->get([
                        'id_calendario',
                        'title',
                        'descripcion',
                        'start',
                        'end',
                        'textColor',
                        'backgroundColor'
                    ]);
                break;
            case 'agregar':
                $datos['id_empresa_calendario'] = session('idEmpresa');
                $datos['id_usuario_creacion_calendario'] = session('idUsuario');
                $datos['id_usuario_modificacion_calendario'] = session('idUsuario');
                Calendario::insert($datos);
                return Calendario::where('id_empresa_calendario', session('idEmpresa'))
                    ->where('id_usuario_calendario', session('idUsuario'))
                    ->where('start', '>=', $datos['start'])
                    ->where('end', '<=', $datos['end'])
                    ->get([
                        'id_calendario',
                        'title',
                        'descripcion',
                        'start',
                        'end',
                        'textColor',
                        'backgroundColor'
                    ]);
                break;
            case 'borrar':
                Calendario::where('id_calendario', $datos['id_calendario'])->delete($datos);
                return Calendario::where('id_empresa_calendario', session('idEmpresa'))
                    ->where('id_usuario_calendario', session('idUsuario'))
                    ->where('start', '>=', $datos['start'])
                    ->where('end', '<=', $datos['end'])
                    ->get([
                        'id_calendario',
                        'title',
                        'descripcion',
                        'start',
                        'end',
                        'textColor',
                        'backgroundColor'
                    ]);
                break;
        }
    }
}
