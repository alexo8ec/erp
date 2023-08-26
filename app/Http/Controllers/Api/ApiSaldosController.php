<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MovimientosBilletera;
use Illuminate\Http\Request;

class ApiSaldosController extends Controller
{
    private $controlador = 'apisaldos';
    public function saldo(Request $r)
    {
        return MovimientosBilletera::getSaldo($r);
    }
}
