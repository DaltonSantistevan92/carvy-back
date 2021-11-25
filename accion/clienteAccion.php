<?php

require_once 'app/error.php';

class ClienteAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/cliente/listar' && $params) {
                    Route::get('/cliente/listar/:id', 'clienteController@buscar', $params);
                } else
                if ($ruta == '/cliente/listar') {
                    Route::get('/cliente/listar', 'clienteController@listar');
                } else
                if ($ruta == '/cliente/listar_vehiculo') {
                    Route::get('/cliente/listar_vehiculo', 'clienteController@listarvehiculodatatable');
                } else
                if ($ruta == '/cliente/datatable') {
                    Route::get('/cliente/datatable', 'clienteController@datatable');
                } else
                if ($ruta == '/cliente/vehiculo' && $params) {
                    Route::get('/cliente/vehiculo/:id', 'clienteController@getVehiculo', $params);
                } else
                if ($ruta == '/cliente/buscar' && $params) {
                    Route::get('/cliente/buscar/:id', 'clienteController@buscarCliente', $params);
                } else
                if ($ruta == '/cliente/search' && $params) {
                    Route::get('/cliente/search/:texto', 'clienteController@searchCliente', $params);
                } else
                if ($ruta == '/cliente/contar') {
                    Route::get('/cliente/contar', 'clienteController@contar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/cliente/save') {
                    Route::post('/cliente/save', 'clienteController@guardar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'delete':
                if ($params) {
                    if ($ruta == '/cliente/eliminar') {
                        Route::delete('/cliente/eliminar/:id', 'clienteController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;

            case 'put':
                if ($params) {
                    if ($ruta == '/cliente/editar') {
                        Route::put('/cliente/editar/:id', 'clienteController@editar', $params);
                    } else {
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                }
                break;
        }
    }
}
