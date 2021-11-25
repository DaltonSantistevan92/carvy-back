<?php

require_once 'app/error.php';

class VehiculoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/vehiculo/listar' && $params) {
                    Route::get('/vehiculo/listar/:id', 'vehiculoController@buscar', $params);
                } else
                if ($ruta == '/vehiculo/listar') {
                    Route::get('/vehiculo/listar', 'vehiculoController@listar');
                } else
                if ($ruta == '/vehiculo/libre' && $params) {
                    Route::get('/vehiculo/libre/:libre', 'vehiculoController@libre', $params);
                } else
                if ($ruta == '/vehiculo/cliente' && $params) {
                    Route::get('/vehiculo/cliente/:id', 'vehiculoController@clienteVehiculo', $params);
                } else
                if ($ruta == '/vehiculo/search' && $params) {
                    Route::get('/vehiculo/search/:id_cliente', 'vehiculoController@searchVehiculo', $params);
                } else
                if ($ruta == '/vehiculo/buscar' && $params) {
                    Route::get('/vehiculo/buscar/:id', 'vehiculoController@buscarVehiculo', $params);
                } else
                if ($ruta == '/vehiculo/contar') {
                    Route::get('/vehiculo/contar', 'vehiculoController@contar');
                }
                else
                if($ruta == '/vehiculo/reparados'){
                    Route::get('/vehiculo/reparados/:inicio/:fin/:marca_id', 'vehiculoController@reparados', $params);
                }
                else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/vehiculo/save') {
                    Route::post('/vehiculo/save', 'vehiculoController@guardar');
                } else
                if ($ruta == '/vehiculo/saveClienteVehiculo') {
                    Route::post('/vehiculo/saveClienteVehiculo', 'vehiculoController@guardarClienteVehiculo');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'put':
                if ($params) {
                    if ($ruta == '/vehiculo/editar') {
                        Route::put('/vehiculo/editar/:id', 'vehiculoController@editar', $params);
                    } else {
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                }
                break;

            case 'delete':
                if ($params) {
                    if ($ruta == '/vehiculo/eliminarClienteVehiculo') {
                        Route::delete('/categoria/eliminarClienteVehiculo/:id_vehiculo/:id_cliente', 'vehiculoController@eliminarClienteVehiculo', $params);
                    }else 
                    if($ruta == '/vehiculo/eliminar' && $params){
                        Route::delete('/categoria/eliminar/:id', 'vehiculoController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;
        }
    }
}
