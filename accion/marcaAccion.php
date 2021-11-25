<?php

require_once 'app/error.php';

class MarcaAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/marca/listar' && $params) {
                    Route::get('/marca/listar/:id', 'marcaController@buscar',$params);
                }else
                if ($ruta == '/marca/listar') {
                    Route::get('/marca/listar', 'marcaController@listar');
                }else
                if ($ruta == '/marca/datatable') {
                    Route::get('/marca/datatable', 'marcaController@datatable');
                } else
                if ($ruta == '/marca/search' && $params) {
                    Route::get('/marca/search/:texto', 'marcaController@searchMarca', $params);
                }else
                if( $ruta == '/marca/todos'){
                    Route::get('/marca/todos', 'marcaController@todos');
                }
                else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }

            break;

            case 'post':
                if ($ruta == '/marca/save') {
                    Route::post('/marca/save', 'marcaController@save');
                }else{
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }

            break;

            case 'put':
                if($params){
                    if($ruta == '/marca/editar'){
                        Route::put('/marca/editar/:id', 'marcaController@editar', $params);
                    }else{
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                }
            break;
         
            case 'delete':
                if ($params) {
                    if ($ruta == '/marca/eliminar') {
                        Route::delete('/marca/eliminar/:id', 'marcaController@eliminar', $params);
                    }
                } else {
                    ErrorClass::e('400', 'No ha enviado parámetros por la url');
                }
            break;     
        }
    }
}
