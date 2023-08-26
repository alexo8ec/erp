<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogNotas extends Model
{
    protected $table = 'db_log_notas_reparacion';
    protected $primaryKey  = 'id_log_nota';
    const CREATED_AT = 'created_at_log';
    const UPDATED_AT = 'updated_at_log';
}
