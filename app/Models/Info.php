<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table = 'db_info';
    protected $primaryKey  = 'id_info';
    const CREATED_AT = 'created_at_info';
    const UPDATED_AT = 'updated_at_info';

    public static function getInfo()
    {
        return Info::orderBy('created_at_info', 'desc')->first();
    }
}
