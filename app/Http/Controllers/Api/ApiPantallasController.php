<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class ApiPantallasController extends Controller
{
    private $controlador = 'apipantallas';
    public function index(Request $r)
    {
        return Utilidades::pantallas($r);
    }
}
