<?php

require_once 'app/error.php';

class ProveedorAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {
        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/proveedor/listar' && $params) {
                    Route::get('/proveedor/listar/:id', 'proveedorController@buscar', $params);
                } else
                if ($ruta == '/proveedor/listar') {
                    Route::get('/proveedor/listar', 'proveedorController@listar');
                } else
                if ($ruta == '/proveedor/datatable') {
                    Route::get('/proveedor/listar', 'proveedorController@datatable');
                } else
                if ($ruta == '/proveedor/search' & $params) {
                    Route::get('/proveedor/listar/:texto', 'proveedorController@search', $params);
                } else
                if ($ruta == '/proveedor/contar') {
                    Route::get('/proveedor/contar', 'proveedorController@contar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/proveedor/save') {
                    Route::post('/proveedor/save', 'proveedorController@guardar');
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'put':
                if ($params) {
                    if ($ruta == '/proveedor/editar') {
                        Route::put('/proveedor/editar/:id', 'proveedorController@editar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;

                case 'delete':
                    if ($params) {
                        if ($ruta == '/proveedor/eliminar') {
                            Route::delete('/proveedor/eliminar/:id', 'proveedorController@eliminar', $params);
                        }
                    } else {
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                    break;
        }
    }
}
