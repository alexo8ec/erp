<?php

namespace App\Http\Controllers;

use App\Models\Bodegas;
use App\Models\Catalogos;
use App\Models\Categorias;
use App\Models\Empresas;
use App\Models\Establecimientos;
use App\Models\Info;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\PlanCuenta;
use App\Models\Productos;
use App\Models\SubCategorias;
use App\Models\TipoImpuesto;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    private $controlador = 'inventario';
    public function index(Request $r)
    {
        config(['data' => []]);
        if (session('idUsuario') == '')
            return redirect('/');
        else {
            config(['data' => []]);
            $info = Info::getInfo();
            $data['info'] = $info;
            $data['controlador'] = $this->controlador;
            $data['submodulo'] = $r->submodulo;
            $data['empresa'] = Empresas::getEmpresas(session('idEmpresa'));
            $data['usuario'] = Usuarios::getUsuario(session('idUsuario'));
            $data['modulos'] = Modulos::getModulos();
            $data['permisos'] = PermisosUsuario::getPermisos(session('idUsuario'));
            $data['title'] = trans('modulos.' . $this->controlador) . ' | ' . $info->nombre_info . ' V' . $info->mayor_info . '.' . $info->menor_info;
            if ($r->submodulo == 'productos') {
                $data['tabla'] = true;
                $data['img'] = true;
                $data['titulo_tabla'] = 'Lista de productos';
                $data['tipoProducto'] = Catalogos::traerCatalogo('tipoproducto');
                $data['categorias'] = Categorias::getCategorias();
                $data['marcas'] = Catalogos::traerCatalogo('marcas');
                $data['presentacion'] = Catalogos::traerCatalogo('presentacion');
                $data['iva'] = TipoImpuesto::getImpuestos('iva');
                $data['ice'] = TipoImpuesto::getImpuestos('ice');
                $data['irbpnr'] = TipoImpuesto::getImpuestos('irbpnr');
                $data['deducibles'] = Catalogos::traerCatalogo('deducibles');
                $data['colores'] = Catalogos::traerCatalogo('colores');
                $data['tallas'] = Catalogos::traerCatalogo('tallas');
                $data['productos'] = Productos::getProductos();
                $data['contenido'] = $this->controlador . '.view_productos';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'bodegas') {
                $data['tabla'] = true;
                $data['puntos'] = Establecimientos::getPuntos();
                $data['titulo_tabla'] = 'Lista de bodegas';
                $data['contenido'] = $this->controlador . '.view_bodegas';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'traerSubcategoria') {
                return SubCategorias::traerSubcategoria($r);
            } elseif ($r->submodulo == 'traerPlanCuenta') {
                return PlanCuenta::verplan($r);
            } elseif ($r->submodulo == 'saveProducto') {
                return Productos::saveProducto($r);
            } elseif ($r->submodulo == 'saveBodega') {
                return Bodegas::saveBodega($r);
            } elseif ($r->submodulo == 'productosjs') {
                $data = Productos::getProductos();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'bodegasjs') {
                $data = Bodegas::getBodegas();
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                return json_encode($results);
            } elseif ($r->submodulo == 'validarCodigo') {
                return Productos::validarCodigo($r);
            } elseif ($r->submodulo == 'verProducto') {
                return Productos::getProductos($r->id);
            } elseif ($r->submodulo == 'moveProducto') {
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Movimiento de productos';
                $data['desde'] = $r->desde;
                $data['hasta'] = $r->hasta;
                $data['movimientos'] = Productos::getMovimientos($r);
                $data['contenido'] = $this->controlador . '.view_movimiento_producto';
                config(['data' => $data]);
                return view('layout.view_child');
            } else {
                return view('errors/401', $data);
            }
        }
    }
}
