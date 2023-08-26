<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use App\Models\Info;
use App\Models\MetodoPago;
use App\Models\Modulos;
use App\Models\PermisosUsuario;
use App\Models\Proveedores;
use App\Models\SustentoTributario;
use App\Models\TipoDocumento;
use App\Models\Tributacion;
use App\Models\Usuarios;
use App\Models\Utilidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TributacionController extends Controller
{
    private $controlador = 'tributacion';
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
            if ($r->submodulo == 'importardocumentos') {
                if (!Utilidades::verificarPermisos($r))
                    return view('errors/401', $data);
                $data['tabla'] = true;
                $data['titulo_tabla'] = 'Opciones';
                $data['usuarios'] = Usuarios::getUsuario();
                $data['proveedores'] = Proveedores::getProveedores();
                $data['tipodocumento'] = TipoDocumento::getTipoDocumentos();
                $data['sustentoTributario'] = SustentoTributario::getsustento();
                $data['formapago'] = MetodoPago::getMetodoPagos();
                $data['contenido'] = $this->controlador . '.view_importar_documentos';
                config(['data' => $data]);
                return view('layout.view_child');
            } elseif ($r->submodulo == 'importDocs') {
                Tributacion::getImportarDocumentos($r);
            } elseif ($r->submodulo == 'traerDocumentoSri') {
                return Tributacion::traerDocumentoSri($r->clave);
            } elseif ($r->submodulo == 'saveCompraXml') {
                return Tributacion::saveCompraXml($r);
            } elseif ($r->submodulo == 'getDatosSriImport') {
                $data = DB::table('db_importacion_documento_sri')
                    ->where('id_empresa', session('idEmpresa'))
                    ->get();
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
}
