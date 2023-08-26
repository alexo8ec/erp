<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Establecimientos extends Model
{
    protected $table = 'db_establecimientos';
    protected $primaryKey  = 'id_establecimiento';
    const CREATED_AT = 'created_at_establecimiento';
    const UPDATED_AT = 'updated_at_establecimiento';

    private static $modelo = 'Establecimientos';
    public static function getDireccionEstablecimiento($ruc, $esta, $emis)
    {
        $dEmpresa = Empresas::getEmpresasRuc($ruc);
        return Establecimientos::where('id_empresa_establecimiento', $dEmpresa->id_empresa)
            ->where('establecimiento', $esta)
            ->where('emision_establecimiento', $emis)
            ->first();
    }
    public static function cambiarEstadoEstablecimiento($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $arrayeEstablecimiento = [
                'estado_establecimiento' => $r->estado
            ];
            Establecimientos::where('id_establecimiento', $r->id)->update($arrayeEstablecimiento);
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            DB::commit();
            $cont++;
        } catch (Exception $e) {
            DB::rollback();
            $r->c = 'empresas';
            $r->s = 'cambiarEstadoEstablecimiento';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = Establecimientos::$modelo;
            $r->o = 'Error al actualizar la establecimiento: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine()
            ];
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = [
                'code' => 200,
                'state' => true,
                'data' => '',
                'message' => 'ok|Datos guardados correctamente...'
            ];
            return json_encode($result);
        }
    }
    public static function saveEstablecimiento($r)
    {
        $datos = $r->input();
        $origin = $datos;
        unset($datos['_token']);
        unset($datos['d']);
        $cont = 0;
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $datos['id_empresa_establecimiento'] = session('idEmpresa');
            if ($datos['id_establecimiento'] != '') {
                $cont++;
                $emp = Establecimientos::where('id_establecimiento', $datos['id_establecimiento'])
                    ->first(['uuid_establecimiento']);
                if ($emp->uuid_establecimiento == '')
                    $datos['uuid_establecimiento'] = Uuid::uuid1();
                Establecimientos::where('id_establecimiento', $datos['id_establecimiento'])->update($datos);
                $r->c = 'establecimiento';
                $r->s = 'saveEstablecimiento';
                $r->d = $origin['d'];
                $r->m = Establecimientos::$modelo;
                $r->o = 'Se actualizo le establecimiento No.: ' . $datos['id_establecimiento'];
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            } else {
                $cont++;
                $datos['uuid_establecimiento'] = Uuid::uuid1();
                Establecimientos::insert($datos);
                $id = Establecimientos::latest('id_establecimiento')->first('id_establecimiento');
                $r->c = 'establecimiento';
                $r->s = 'saveEstablecimiento';
                $r->d = $origin['d'];
                $r->m = Establecimientos::$modelo;
                $r->o = 'Se creo una establecimiento';
                Auditorias::saveAuditoria($r, DB::getQueryLog());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $r->c = 'establecimiento';
            $r->s = 'saveEstablecimiento';
            $r->d = isset($origin['d']) ? $origin['d'] : '';
            $r->m = Establecimientos::$modelo;
            $r->o = 'Error al guardar la entidad: ' . $e->getMessage();
            Auditorias::saveAuditoria($r, DB::getQueryLog());
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'no|' . $e->getMessage() . '|Linea: ' . $e->getLine());
            return json_encode($result);
        }
        if ($cont > 0) {
            $result = array('code' => 200, 'state' => true, 'data' => '', 'message' => 'ok|Datos guardados correctamente...');
            return json_encode($result);
        }
    }
    public static function getEstablecimientos($id = '')
    {
        if ($id == '')
            return Establecimientos::where('id_empresa_establecimiento', session('idEmpresa'))->get();
        else
            return Establecimientos::leftJoin('db_ciudades as c', 'db_establecimientos.id_ciudad_establecimiento', '=', 'c.id_ciudad')
                ->where('id_establecimiento', $id)
                ->first();
    }
    public static function getPuntos()
    {
        return Establecimientos::leftjoin('db_ciudades as c', 'db_establecimientos.id_ciudad_establecimiento', '=', 'c.id_ciudad')
            ->where('id_empresa_establecimiento', session('idEmpresa'))
            /*->where('establecimiento', session('estab'))
            ->where('emision_establecimiento', session('emisi'))*/
            ->orderBy('establecimiento', 'asc')
            ->orderBy('emision_establecimiento', 'asc')
            ->get();
    }
    public static function getPuntoEmision($establecimiento)
    {
        $emision = 1;
        $puntos = Establecimientos::where('establecimiento_factura', $establecimiento)->orderBY('emision_factura', 'desc')->first('emision_factura');
        if ($puntos != '')
            $emision = (int)$puntos->emision_factura + 1;
        $emision = str_pad($emision, 0, 2, STR_PAD_LEFT);
    }
    public static function getEmisiones($id = '')
    {
        return Emisiones::get();
    }
}
