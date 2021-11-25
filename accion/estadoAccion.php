<?php

require_once 'app/error.php';

class EstadoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/estado/listar' && $params) {
                    Route::get('/estado/listar/:id', 'estadoController@buscar', $params);
                } else
                if ($ruta == '/estado/listar') {
                    Route::get('/estado/listar', 'estadoController@listar');
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                /*else
                if ($ruta == '/categoria/page' && $params) {
                Route::get('/categoria/page/:id', 'categoriaController@paginar', $params);
                }*/
                break;

            case 'post':
            /* if ($ruta == '/categoria/save') {
            Route::post('/categoria/save', 'categoriaController@guardar');
            }
            break; */

            case 'delete':
                /*  if ($params) {
        if ($ruta == '/categoria/eliminar') {
        Route::delete('/categoria/eliminar/:id', 'categoriaController@eliminar', $params);
        }
        } else {
        ErrorClass::e('400', 'No ha enviado parámetros por la url');
        }
        break; */
        }
    }
}
