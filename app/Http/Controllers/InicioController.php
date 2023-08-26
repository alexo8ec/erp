<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Usuarios;
use App\Models\Utilidades;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    private $controlador = 'inicio';
    public function index(Request $r)
    {
        if (session('idUsuario') != '')
            return redirect('admin');
        $info = Info::getInfo();
        $data['info'] = $info;
        if ($r->submodulo == 'login') {
            $mensaje = Usuarios::getLogin($r);
            if ($mensaje != '') {
                return redirect('/')->with(['message' => $mensaje]);
            } else {
                return redirect('admin');
            }
        }  elseif ($r->submodulo == 'resetclave') {
            $data['title'] = 'Reseteo de su ccontraseña | ' . $info->nombre_info . ' v' . $info->mayor_info . '.' . $info->menor_info;
            return view('login.view_reset_clave', $data);
        } elseif ($r->submodulo == 'enviarclave') {
            $mensaje = Usuarios::resetearClave($r);
            return redirect('/inicio/resetclave')->with(['message' => $mensaje]);
        } else {
            $data['title'] = 'Bienvenid@ | Identifiquese';
            return view('login.view_login', $data);
        }
        
    }
}
