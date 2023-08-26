<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BilleteraController;
use App\Http\Controllers\CajaBancosController;
use App\Http\Controllers\CatalogoCampanaController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\ControlAccesoController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\GestorCorreosController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\ReparacionesController;
use App\Http\Controllers\TributacionController;
use App\Http\Controllers\UtilidadesController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', [LanguageController::class, 'swap'])->name('lang.swap');
$router->group(['prefix' => ''], function ($router) {
    /*Inicio*/
    $router->get('/', [InicioController::class, 'index']);
    $router->match(['get', 'post'], 'inicio/{submodulo}', [InicioController::class, 'index']);
    /*Admin*/
    $router->get('admin/', [AdminController::class, 'index']);
    $router->match(['get', 'post'], 'admin/{submodulo}', [AdminController::class, 'index']);
    /*Empresas*/
    $router->get('empresas/', [EmpresasController::class, 'index']);
    $router->match(['get', 'post'], 'empresas/{submodulo}', [EmpresasController::class, 'index']);
    /*Utilidades*/
    $router->get('utilidades/', [UtilidadesController::class, 'index']);
    $router->match(['get', 'post'], 'utilidades/{submodulo}', [UtilidadesController::class, 'index']);
    /*GestorCorreos*/
    $router->get('gestorcorreos/', [GestorCorreosController::class, 'index']);
    $router->match(['get', 'post'], 'gestorcorreos/{submodulo}', [GestorCorreosController::class, 'index']);
    /*Inventario*/
    $router->get('inventario/', [InventarioController::class, 'index']);
    $router->match(['get', 'post'], 'inventario/{submodulo}', [InventarioController::class, 'index']);
    /*Opciones*/
    $router->get('opciones/', [OpcionesController::class, 'index']);
    $router->match(['get', 'post'], 'opciones/{submodulo}', [OpcionesController::class, 'index']);
    /*Configuraciones*/
    $router->get('configuraciones/', [ConfiguracionesController::class, 'index']);
    $router->match(['get', 'post'], 'configuraciones/{submodulo}', [ConfiguracionesController::class, 'index']);
    /*Catalogo*/
    $router->get('catalogocampana/', [CatalogoCampanaController::class, 'index']);
    $router->match(['get', 'post'], 'catalogocampana/{submodulo}', [CatalogoCampanaController::class, 'index']);
    /*Contabilidad*/
    $router->get('contabilidad/', [ContabilidadController::class, 'index']);
    $router->match(['get', 'post'], 'contabilidad/{submodulo}', [ContabilidadController::class, 'index']);
    /*Crm*/
    $router->get('crm/', [CrmController::class, 'index']);
    $router->match(['get', 'post'], 'crm/{submodulo}', [CrmController::class, 'index']);
    /*Estadisticas*/
    $router->get('estadisticas/', [EstadisticasController::class, 'index']);
    $router->match(['get', 'post'], 'estadisticas/{submodulo}', [EstadisticasController::class, 'index']);
    /*Compras*/
    $router->get('compras/', [ComprasController::class, 'index']);
    $router->match(['get', 'post'], 'compras/{submodulo}', [ComprasController::class, 'index']);
    /*Tributacion*/
    $router->get('tributacion/', [TributacionController::class, 'index']);
    $router->match(['get', 'post'], 'tributacion/{submodulo}', [TributacionController::class, 'index']);
    /*Ventas*/
    $router->get('ventas/', [VentasController::class, 'index']);
    $router->match(['get', 'post'], 'ventas/{submodulo}', [VentasController::class, 'index']);
    /*Caja-Bancos*/
    $router->get('cajabancos/', [CajaBancosController::class, 'index']);
    $router->match(['get', 'post'], 'cajabancos/{submodulo}', [CajaBancosController::class, 'index']);
    /*Reparaciones*/
    $router->get('reparaciones/', [ReparacionesController::class, 'index']);
    $router->match(['get', 'post'], 'reparaciones/{submodulo}', [ReparacionesController::class, 'index']);
    /*Control de acceso*/
    $router->get('controlacceso/', [ControlAccesoController::class, 'index']);
    $router->match(['get', 'post'], 'controlacceso/{submodulo}', [ControlAccesoController::class, 'index']);
     /*Billetera*/
     $router->get('billetera/', [BilleteraController::class, 'index']);
     $router->match(['get', 'post'], 'billetera/{submodulo}', [BilleteraController::class, 'index']);
});
