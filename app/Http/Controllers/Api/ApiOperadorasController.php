<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductosTelefonicos;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class ApiOperadorasController extends Controller
{
    private $controlador = 'apioperadoras';
    public function valoroperadoras(Request $r)
    {
        return Utilidades::valorOperadoras($r);
    }
    public function saverecarga(Request $r)
    {
        return ProductosTelefonicos::saverecarga($r);
    }
}
