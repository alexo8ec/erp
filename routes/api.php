<?php

use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiOperadorasController;
use App\Http\Controllers\Api\ApiPantallasController;
use App\Http\Controllers\Api\ApiSaldosController;

$router->group(['prefix' => 'v1'], function ($router) {
    $router->post('login', [ApiLoginController::class, 'login']);
    $router->post('logout', [ApiLoginController::class, 'logout']);
    $router->post('pantallas', [ApiPantallasController::class, 'index']);
    $router->get('valoroperadoras', [ApiOperadorasController::class, 'valoroperadoras']);
    $router->post('saverecarga', [ApiOperadorasController::class, 'saverecarga']);
    $router->get('saldo', [ApiSaldosController::class, 'saldo']);
});
