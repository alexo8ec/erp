<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivos extends Model
{
    protected $table = 'db_archivos';
    protected $primaryKey  = 'id_archivo';
    const CREATED_AT = 'created_at_archivo';
    const UPDATED_AT = 'updated_at_archivo';
}
