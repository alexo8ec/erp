<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditorias extends Model
{
    protected $table = 'db_auditorias';
    protected $primaryKey  = 'id_auditoria';

    public static function saveAuditoria($req, $query = null)
    {
        $consulta = '';
        $parametros = '';
        $tiempo = '';
        if ($query != null) {
            $consulta = $query[0]['query'];
            $parametros = $query[0]['bindings'];
            $tiempo = $query[0]['time'];
        }
        $res = Utilidades::detect();
        $navegador = $res['browser'];
        $sistema = $res['os'];
        if (isset($res['version']))
            $version = $res['version'];
        $ip = Utilidades::getRealIP();
        $ubic = explode('|', $req->d);
        $arrayAuditoria = [
            'controlador' => $req->c,
            'submodulo' => $req->s,
            'modelo' => $req->m,
            'vista' => $req->v,
            'query' => $consulta,
            'parametros' => json_encode($parametros),
            'tiempo_consulta' => $tiempo,
            'id_usuario_auditoria' => session('idUsuario') != '' ? session('idUsuario') : 0,
            'id_empresa_auditoria' => session('idEmpresa') != '' ? session('idEmpresa') : 0,
            'ip' => $ip,
            'navegador' => $navegador,
            'sistema' => $sistema,
            'version' => $version,
            'fecha_creacion_auditoria' => date('Y-m-d H:i:s'),
            'id_usuario_creacion_auditoria' => session('idUsuario') != '' ? session('idUsuario') : 0,
            'id_usuario_modificacion_auditoria' => session('idEmpresa') != '' ? session('idEmpresa') : 0,
            'latitud' => isset($ubic[0]) ? $ubic[0] : '',
            'longitud' => isset($ubic[1]) ? $ubic[1] : '',
            'agente' => $_SERVER['HTTP_USER_AGENT'],
            'observacion_auditoria' => $req->o,
            'obj_json' => json_encode($req->post())
        ];
        Auditorias::insert($arrayAuditoria);
    }
}
