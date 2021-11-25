<?php

require_once 'app/error.php';

class MaterialAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                /* if ($ruta == '/categoria') {
                    Route::get('/categoria/listar', 'categoriaController@listar');
                } else
                if ($ruta == '/categoria/listar') {
                    Route::get('/categoria/listar', 'categoriaController@listar');
                } else
                if ($ruta == '/categoria/page' && $params) {
                    Route::get('/categoria/page/:id', 'categoriaController@paginar', $params);
                }else
                if($ruta == '/categoria/contar'){
                    Route::get('/categoria/contar', 'categoriaController@contar');
                }else
                if($ruta == '/categoria/grafica_stock_productos'){
                    Route::get('/categoria/grafica_stock_productos', 'categoriaController@grafica_stock_productos');
                } */
                break;
                

            case 'post':
                if ($ruta == '/material/save') {
                    Route::post('/material/save', 'materialController@guardar');
                }
                break;

            case 'delete':
                /* if ($params) {
                    if ($ruta == '/categoria/eliminar') {
                        Route::delete('/categoria/eliminar/:id', 'categoriaController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                } */
                break;
        }
    }
}
