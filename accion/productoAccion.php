<?php

require_once 'app/error.php';

class ProductoAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/producto/listar' && $params) {
                    Route::get('/producto/listar/:id', 'productoController@buscar', $params);
                } else
                if ($ruta == '/producto/listar') {
                    Route::get('/producto/listar', 'productoController@listar');
                } else
                if ($ruta == '/producto/datatable') {
                    Route::get('/producto/listar', 'productoController@dataTable');
                } else
                if ($ruta == '/producto/search' && $params) {
                    Route::get('/producto/listar/:texto', 'productoController@search', $params);
                } else
                if ($ruta == '/producto/verImg' && $params) {
                    Route::get('/producto/verImg/:img', 'productoController@verImg', $params);
                } else
                if ($ruta == '/producto/contar') {
                    Route::get('/producto/contar', 'productoController@contar');
                } else
                if ($ruta == '/producto/grafica_stock') {
                    Route::get('/producto/grafica_stock', 'productoController@graficaStock');
                } else
                if( $ruta == '/producto/agotarse'){
                    Route::get('/producto/agotarse/:categoria_id/:limite', 'productoController@agotarse', $params);
                }
                else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'post':
                if ($ruta == '/producto/save') {
                    Route::post('/producto/save', 'productoController@guardar');
                } else
                if ($ruta == '/producto/fichero') {
                    Route::post('/producto/fichero', 'productoController@subirFichero', true);
                } else {
                    ErrorClass::e('404', 'No se encuentra la url');
                }
                break;

            case 'delete':
                if ($params) {
                    if ($ruta == '/producto/eliminar') {
                        Route::delete('/producto/eliminar/:id/:estado', 'productoController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;

            case 'put':
                if ($params) {
                    if ($ruta == '/producto/editar') {
                        Route::put('/producto/editar/:id', 'productoController@editar');
                    }else 
                    if($ruta == '/producto/editarimg' && $params){
                    Route::put('/producto/editarimg/:id', 'productoController@editarimg',$params);
                    }
                }/* else if($ruta == '/producto/editarimg' && $params){ 
                    Route::put('/producto/editarimg/:id', 'productoController@editarimg',$params);
                } */else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
                break;
        }
    }
}
