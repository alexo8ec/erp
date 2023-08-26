<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SustentoTributario extends Model
{
    protected $table = 'db_sustento_tributario';
    protected $primaryKey  = 'id_sustento';
    const CREATED_AT = 'created_at_sustento';
    const UPDATED_AT = 'updated_at_sustento';

    public static function getsustento()
    {
        return SustentoTributario::all();
    }
    public static function getsustentotributario($r)
    {
        $dtipo = explode(' - ', $r->id);

        $tipoDoc = TipoDocumento::where('codigo_sri', $dtipo[0])->first();
        $idtipodoc = $r->codsustento;
        $info = '';
        if ($tipoDoc != '') {
            $datos = explode(',', $tipoDoc->codigo_sustento);
            $sustento = SustentoTributario::where(function ($q) use ($datos) {
                for ($i = 0; $i < count($datos); $i++) {
                    if ($i == 0)
                        $q->where('codigo_sustento', $datos[$i]);
                    else
                        $q->orWhere('codigo_sustento', $datos[$i]);
                }
            })->get();
            if (count($sustento) > 0) {
                $info .= '<option value="">--Sustento tributario--</option>';
                foreach ($sustento as $row) {
                    $select = '';
                    if ($idtipodoc == $row->id_sustento) $select = 'selected';
                    $info .= '<option value="' . $row->id_sustento . '" ' . $select . '>' . $row->codigo_sustento . ' | ' . $row->nombre_sustento . '</option>';
                }
            } else $info .= '<option value="">No existe Sustento</option>';
        } else $info .= '<option value="">No existe Sustento</option>';
        return $info;
    }
}
