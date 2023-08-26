<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioEmails extends Model
{
    protected $table = 'db_envio_emails';
    protected $primaryKey  = 'id_email';
    const CREATED_AT = 'created_at_email';
    const UPDATED_AT = 'updated_at_email';
}
