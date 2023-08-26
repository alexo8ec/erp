<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ArchivosElectronicos extends Model
{
    protected $table = 'db_archivos_electronicos';
    protected $primaryKey  = 'id_archivo';
    const CREATED_AT = 'created_at_archivo';
    const UPDATED_AT = 'updated_at_archivo';
}
