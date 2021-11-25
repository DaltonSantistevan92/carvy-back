<?php

require_once 'app/error.php';

class CategoriaAccion 
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/categoria/listar' && $params) {
                    Route::get('/categoria/listar/:id', 'categoriaController@one', $params);
                }else
                if ($ruta == '/categoria') {
                    Route::get('/categoria/listar', 'categoriaController@listar');
                } else
                if ($ruta == '/categoria/listar') {
                    Route::get('/categoria/listar', 'categoriaController@listar');
                }else
                if ($ruta == '/categoria/buscar_categoria_producto' && $params) {
                    Route::get('/categoria/buscar_categoria_producto/:id', 'categoriaController@buscar_categoria_producto', $params);
                }else
                if ($ruta == '/categoria/page' && $params) {
                    Route::get('/categoria/page/:id', 'categoriaController@paginar', $params);
                }else
                if($ruta == '/categoria/contar'){
                    Route::get('/categoria/contar', 'categoriaController@contar');
                }else
                if($ruta == '/categoria/grafica_porcentaje'){
                    Route::get('/categoria/grafica_porcentaje', 'categoriaController@grafica_porcentaje');
                }
                else
                if($ruta == '/categoria/grafica_stock_productos'){
                    Route::get('/categoria/grafica_stock_productos', 'categoriaController@grafica_stock_productos');
                }
                break;
                

            case 'post':
                if ($ruta == '/categoria/save') {
                    Route::post('/categoria/save', 'categoriaController@guardar');
                }
                break;

            case 'delete':
                if ($params) {
                    if ($ruta == '/categoria/eliminar') {
                        Route::delete('/categoria/eliminar/:id', 'categoriaController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break; 

            case 'put':
                if($params){
                    if($ruta == '/categoria/editar'){
                        Route::put('/categoria/editar/:id', 'categoriaController@editar', $params);
                    }else{
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                }
            break;
        }
    }
}
