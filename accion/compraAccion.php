<?php

require_once 'app/error.php';
 
class CompraAccion
{ 

    public function index($metodo_http, $ruta, $params = null)
    {

        switch ($metodo_http) { 

            case 'get':
                if ($ruta == '/compra/listar'&& $params) {
                    Route::get('/compra/listar/:id', 'compraController@buscar', $params);
                } else
                if ($ruta == '/compra/listar' ) {
                    Route::get('/compra/listar/', 'compraController@listar');
                }else
                if ($ruta == '/compra/datatable' && $params) {
                    Route::get('/compra/datatable/:opcion', 'compraController@listarTabla', $params);
                }else
                if($ruta == '/compra/total'){
                    Route::get('/compra/total', 'compraController@total');
                }else
                if($ruta == '/compra/grafica_compra'){
                    Route::get('/compra/grafica_compra', 'compraController@grafica_compra');
                }
                else
                if($ruta == '/compra/grafica_general'){
                    Route::get('/compra/grafica_general', 'compraController@grafica_general');
                }   
                break;

            case 'post':
                if ($ruta == '/compra/save') {
                    Route::post('/compra/save', 'compraController@guardar');
                }
                break;

            case 'delete':
                /*  if($params){
                if($ruta == '/categoria/eliminar'){
                Route::delete('/categoria/eliminar/:id', 'categoriaController@eliminar', $params);
                }
                }else{
                ErrorClass::e('400', 'No ha enviado parámetros por la url');
                } */
                break;
        }
    }
}
