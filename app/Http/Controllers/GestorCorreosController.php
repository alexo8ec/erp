<?php

namespace App\Http\Controllers;

use App\Models\EstadoEnvios;
use Illuminate\Http\Request;

class GestorCorreosController extends Controller
{
    public function index(Request $r)
    {
       return EstadoEnvios::getDatos($r);
    }
}
