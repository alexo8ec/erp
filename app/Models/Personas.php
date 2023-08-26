<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personas extends Model
{
    protected $table = 'db_personas';
    protected $primaryKey  = 'id_persona';
    const CREATED_AT = 'created_at_persona';
    const UPDATED_AT = 'updated_at_persona';
}
