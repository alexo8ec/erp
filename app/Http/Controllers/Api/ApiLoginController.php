<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class ApiLoginController extends Controller
{
    private $controlador = 'apilogin';
    public function login(Request $r)
    {
        return Usuarios::getLogin($r, 'API');
    }
    public function logout(Request $r)
    {
        return Usuarios::apiLogout($r);
    }
}
