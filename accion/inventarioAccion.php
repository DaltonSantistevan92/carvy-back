<?php

require_once 'app/error.php';

class InventarioAccion
{

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) {
            case 'get':
                if ($ruta == '/inventario/kardex' && $params) {
                    Route::get('/inventario/kardex/:id_producto/:desde/:hasta', 'inventarioController@kardex', $params);
                }else
                if ($ruta == '/inventario/kar' && $params) {
                    Route::get('/inventario/kar/:id_producto/:desde/:hasta', 'inventarioController@kar', $params);
                } 
                break; 
                

            case 'post':
                
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

            case 'put':
                /* if($params){
                    if($ruta == '/categoria/editar'){
                        Route::put('/categoria/editar/:id', 'categoriaController@editar', $params);

                    }else{
                        ErrorClass::e('400', 'No ha enviado parámetros por la url');
                    }
                } */
                break;
        }
    }
}
