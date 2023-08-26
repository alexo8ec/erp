<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'db_tipo_documentos';
    protected $primaryKey  = 'id_tipo_doc';

    public static function getTipoDocumentos()
    {
        return TipoDocumento::where('id_uso', 5)
        ->whereNotNull('codigo_sustento')
        ->get();
    }
}
