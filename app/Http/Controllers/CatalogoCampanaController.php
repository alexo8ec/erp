<?php

namespace App\Http\Controllers;

use App\Models\Catalogos;
use App\Models\Categorias;
use App\Models\Empresas;
use App\Models\Info;
use App\Models\LiderlistCabecera;
use App\Models\LiderlistDetalle;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Productos;
use App\Models\SubCategorias;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class CatalogoCampanaController extends Controller
{
    private $controlador = 'catalogocampana';
    public function index(Request $r)
    {
        if (session('idUsuario') == '')
            return redirect('/');
        config(['data' => []]);
        $info = Info::getInfo();
        $data['info'] = $info;
        $data['controlador'] = $this->controlador;
        $data['submodulo'] = $r->submodulo;
        $data['usuario'] = Usuarios::getUsuario(session('idUsuario'));
        $data['modulos'] = Modulos::getModulos();
        $data['permisos'] = PermisosUsuario::getPermisos(session('idUsuario'));
        $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
        if (session('idEmpresa') != '')
            $data['empresa'] = Empresas::getEmpresas(session('idEmpresa'));
        if ($r->submodulo == 'liderlist') {
            $data['tabla'] = true;
            $data['titulo_tabla'] = 'LiderList';
            $data['contenido'] = $this->controlador . '.view_liderlist';
            config(['data' => $data]);
            return view('layout.view_child');
        } elseif ($r->submodulo == 'saveLiderlist') {
            return LiderlistCabecera::saveLiderlist($r);
        } elseif ($r->submodulo == 'saveDetalleLiderlist') {
            return LiderlistDetalle::saveDetalleLiderlist($r);
        } elseif ($r->submodulo == 'datelleLiderlist') {
            $data['tabla'] = true;
            $data['img'] = true;
            $data['id'] = $r->id;
            $data['categorias'] = Categorias::getCategorias();
            $data['referencias'] = Productos::getProductos();
            $data['colores'] = Catalogos::traerCatalogo('colores');
            $data['tallas'] = Catalogos::traerCatalogo('tallas');
            $data['titulo_tabla'] = 'Detalle LiderList';
            $data['contenido'] = $this->controlador . '.view_detalle_liderlist';
            config(['data' => $data]);
            return view('layout.view_child');
        } elseif ($r->submodulo == 'traerSubcategoria') {
            return SubCategorias::traerSubcategoria($r);
        } elseif ($r->submodulo == 'liderlistjs') {
            $data = LiderlistCabecera::getLiderlist();
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            return json_encode($results);
        } elseif ($r->submodulo == 'datelleLiderlistjs') {
            $data = LiderlistDetalle::getLiderlistDetalle($r->id);
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            return json_encode($results);
        } else {
            return view('errors/401', $data);
        }
    }
}
